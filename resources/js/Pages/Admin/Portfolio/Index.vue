<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    projects: Array
});

const search = ref('');

const filteredProjects = computed(() => {
    if (!search.value) return props.projects;
    const lowerSearch = search.value.toLowerCase();
    return props.projects.filter(project => 
        project.title.toLowerCase().includes(lowerSearch) || 
        (project.location && project.location.toLowerCase().includes(lowerSearch)) ||
        (project.project_type && project.project_type.toLowerCase().includes(lowerSearch))
    );
});

const formatCurrency = (val) => {
    if (val === null || val === undefined) return '$0';
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 0
    }).format(val);
};

const deleteProject = (id) => {
    if (confirm('Decommission this project permanently?')) {
        router.delete(route('admin.portfolios.destroy', id), {
            preserveScroll: true,
            preserveState: true,
        });
    }
};

const selectedIds = ref([]);
const toggleSelectAll = (event) => {
    if (event.target.checked) {
        selectedIds.value = filteredProjects.value.map(p => p.id);
    } else {
        selectedIds.value = [];
    }
};

const bulkDownload = () => {
    if (selectedIds.value.length === 0) return;
    
    // Using a form submission for POST request to handle many IDs and avoid 419/414 errors
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = route('admin.portfolios.bulk-pdf');
    form.target = '_blank';
    
    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;
        form.appendChild(tokenInput);
    }
    
    selectedIds.value.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
};
</script>

<template>
    <AdminLayout>
        <Head title="Project Matrix" />

        <ModuleHeader 
            title="Portfolio Management" 
            v-model:search="search"
        >
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mt-2">Centralized command for infrastructure projects & financial auditing.</p>
            </template>
            <template #actions>
                <div class="flex gap-4">
                    <button 
                        @click="bulkDownload"
                        v-if="selectedIds.length > 0"
                        class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all rounded-xl shadow-lg shadow-slate-900/10"
                    >
                        <span class="flex items-center gap-2">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                             Bulk Audit ({{ selectedIds.length }})
                        </span>
                    </button>
                    <Link :href="route('admin.portfolios.create')" class="px-8 py-3 bg-primary text-white text-[10px] font-black uppercase tracking-[0.2em] hover:bg-slate-900 transition-all shadow-lg shadow-primary/20 active:scale-95 flex items-center justify-center gap-2 border border-transparent h-full whitespace-nowrap rounded-xl">
                        <span>+</span> New Deployment
                    </Link>
                </div>
            </template>
        </ModuleHeader>

        <div class="bg-white border border-slate-200 p-10 space-y-8 shadow-xl shadow-slate-200/50 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left rounded-xl overflow-hidden">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-6 w-10">
                                <input type="checkbox" @change="toggleSelectAll" :checked="selectedIds.length === filteredProjects.length && filteredProjects.length > 0" class="bg-white border-slate-300 text-primary focus:ring-0 rounded-md cursor-pointer">
                            </th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-slate-400">Asset Identity</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 text-center">Revenue / Budget</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 text-center">Execution Status</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-slate-400">Status Node</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 text-right">Directives</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="project in filteredProjects" :key="project.id" class="hover:bg-primary/[0.02] transition-colors group">
                            <td class="px-6 py-6 text-center">
                                <input type="checkbox" v-model="selectedIds" :value="project.id" class="bg-white border-slate-300 text-primary focus:ring-0 rounded-md cursor-pointer">
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-black text-xs uppercase tracking-tight text-slate-900 group-hover:text-primary transition-colors italic">{{ project.title }}</div>
                                <div class="text-[8px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-1 italic">{{ project.location || 'GLOBAL_NODE' }}</div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="text-[10px] font-black text-slate-900 italic tracking-tighter">{{ formatCurrency(project.received_payment) }}</div>
                                <div class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mt-1 border-t border-slate-100 pt-1 italic">Budget: {{ formatCurrency(project.total_budget) }}</div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span :class="[
                                    project.execution_status === 'Completed' ? 'text-green-600 bg-green-50 border-green-100' : 'text-primary bg-primary/5 border-primary/10',
                                    'text-[9px] font-black px-4 py-2 border rounded-lg uppercase tracking-[0.1em] italic'
                                ]">{{ project.execution_status }}</span>
                                <div v-if="project.execution_status === 'Completed' && project.completion_date" class="text-[8px] text-slate-400 mt-2 font-black italic uppercase tracking-tighter">End: {{ new Date(project.completion_date).toLocaleDateString() }}</div>
                                <div v-else-if="project.start_date" class="text-[8px] text-slate-400 mt-2 font-black italic uppercase tracking-tighter">Start: {{ new Date(project.start_date).toLocaleDateString() }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div :class="[
                                        project.status === 'published' ? 'bg-primary shadow-lg shadow-primary/40' : 'bg-slate-200',
                                        'w-2 h-2 rounded-full'
                                    ]"></div>
                                    <span :class="[
                                        project.status === 'published' ? 'text-slate-900' : 'text-slate-400',
                                        'text-[9px] font-black uppercase tracking-[0.2em] italic'
                                    ]">{{ project.status }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right space-x-6">
                                <a :href="route('admin.portfolios.pdf', project.id)" target="_blank" class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-300 hover:text-primary transition-colors italic">Report</a>
                                <Link :href="route('admin.portfolios.edit', project.id)" class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-300 hover:text-slate-900 transition-colors italic">Modify</Link>
                                <button @click="deleteProject(project.id)" class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-300 hover:text-red-500 transition-colors italic">Purge</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Empty State -->
                <div v-if="filteredProjects.length === 0" class="py-24 flex flex-col items-center justify-center text-center bg-slate-50/30 rounded-2xl">
                    <div class="w-16 h-16 bg-slate-100 flex items-center justify-center mb-6 rounded-2xl">
                        <span class="text-slate-300 text-2xl font-black italic">?</span>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400">No architectural assets archived in matrix.</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
