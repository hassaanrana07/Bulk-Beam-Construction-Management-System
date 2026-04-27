<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    inquiries: Array
});

const search = ref('');
const selectedInquiry = ref(null);

const filteredInquiries = computed(() => {
    if (!search.value) return props.inquiries;
    const lowerSearch = search.value.toLowerCase();
    return props.inquiries.filter(i => 
        i.name.toLowerCase().includes(lowerSearch) || 
        i.email.toLowerCase().includes(lowerSearch) ||
        i.subject.toLowerCase().includes(lowerSearch) ||
        i.status.toLowerCase().includes(lowerSearch)
    );
});

const updateStatus = (inquiry, status) => {
    router.patch(route('admin.inquiries.update', inquiry.id), { status }, {
        preserveScroll: true,
        onSuccess: () => {
             if (selectedInquiry.value && selectedInquiry.value.id === inquiry.id) {
                 selectedInquiry.value.status = status;
             }
        }
    });
};

const deleteInquiry = (id) => {
    if (confirm('Are you sure you want to purge this inquiry from infrastructure?')) {
        router.delete(route('admin.inquiries.destroy', id), {
            preserveScroll: true,
            onSuccess: () => selectedInquiry.value = null
        });
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <AdminLayout :key="$page.url">
        <Head title="Technical Inquiries" />

        <ModuleHeader 
            title="Inquiry Repository"
            v-model:search="search"
        >
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mt-2">
                    Monitoring {{ inquiries.length }} active structural communication nodes.
                </p>
            </template>
        </ModuleHeader>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Table Section -->
            <div class="lg:col-span-2 bg-black border border-zinc-900 overflow-hidden shadow-2xl rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-zinc-900/50 border-b border-zinc-900">
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">Node Identity</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">Subject Vector</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">Status</th>
                                <th class="px-6 py-4 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 text-right">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-900">
                            <tr v-for="inquiry in filteredInquiries" :key="inquiry.id" 
                                @click="selectedInquiry = inquiry"
                                :class="[
                                    selectedInquiry?.id === inquiry.id ? 'bg-zinc-900/80 border-l-4 border-l-orange-600' : 'hover:bg-zinc-900/30',
                                    'transition-all cursor-pointer group'
                                ]"
                            >
                                <td class="px-6 py-4">
                                    <div class="font-black text-xs uppercase tracking-tight text-white group-hover:text-orange-600 transition-colors">{{ inquiry.name }}</div>
                                    <div class="text-[8px] text-zinc-600 font-bold uppercase tracking-[0.2em] mt-1">{{ inquiry.email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-[10px] font-black uppercase tracking-tight text-zinc-400 group-hover:text-white truncate max-w-[200px]">{{ inquiry.subject }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="[
                                        inquiry.status === 'new' ? 'text-orange-600 border-orange-900/50 bg-orange-900/10' : 
                                        inquiry.status === 'contacted' ? 'text-blue-500 border-blue-900/50 bg-blue-900/10' : 
                                        'text-green-500 border-green-900/50 bg-green-900/10',
                                        'px-2 py-1 text-[8px] font-black uppercase tracking-widest border rounded-lg'
                                    ]">{{ inquiry.status }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-[10px] font-black uppercase text-zinc-600">
                                    {{ formatDate(inquiry.created_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="filteredInquiries.length === 0" class="py-24 text-center">
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600">No communication nodes detected.</p>
                    </div>
                </div>
            </div>

            <!-- Detail View -->
            <div class="lg:col-span-1 space-y-6">
                <div v-if="selectedInquiry" class="bg-zinc-950 border border-zinc-900 p-8 rounded-lg animate-in fade-in slide-in-from-right-4">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            <h3 class="text-xl font-black uppercase tracking-tighter text-white">{{ selectedInquiry.name }}</h3>
                            <p class="text-[9px] font-black text-orange-600 uppercase tracking-widest mt-1">{{ selectedInquiry.email }}</p>
                        </div>
                        <button @click="deleteInquiry(selectedInquiry.id)" class="p-2 text-zinc-700 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <h4 class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 mb-4">Subject Matter</h4>
                            <p class="text-sm font-bold uppercase text-white bg-zinc-900 p-4 border border-zinc-800 rounded-lg">{{ selectedInquiry.subject }}</p>
                        </div>

                        <div>
                            <h4 class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 mb-4">Communication Payload</h4>
                            <div class="text-xs text-zinc-400 leading-relaxed font-medium bg-zinc-900/50 p-6 border border-zinc-900 rounded-lg h-64 overflow-y-auto whitespace-pre-wrap">
                                {{ selectedInquiry.message }}
                            </div>
                        </div>

                        <div class="pt-8 border-t border-zinc-900">
                            <h4 class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 mb-6">Status Optimization</h4>
                            <div class="grid grid-cols-3 gap-2">
                                <button v-for="s in ['new', 'contacted', 'resolved']" :key="s" 
                                    @click="updateStatus(selectedInquiry, s)"
                                    :class="[
                                        selectedInquiry.status === s ? 'bg-orange-600 text-black' : 'bg-zinc-900 text-zinc-500 hover:text-white',
                                        'py-3 text-[9px] font-black uppercase tracking-widest transition-all rounded-lg'
                                    ]"
                                >
                                    {{ s }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div v-else class="h-full bg-zinc-950/50 border border-dashed border-zinc-900 flex flex-col items-center justify-center p-12 text-center rounded-lg">
                    <div class="w-12 h-12 border border-zinc-800 flex items-center justify-center mb-6">
                        <span class="text-zinc-800 text-2xl font-black italic">!</span>
                    </div>
                    <p class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-700">Select a communication node for tactical analysis.</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
