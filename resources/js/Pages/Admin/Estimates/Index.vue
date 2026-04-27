<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, Head } from '@inertiajs/vue3';

const props = defineProps({
    estimates: Object
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(value);
};

const getStatusColor = (status) => {
    switch (status) {
        case 'draft': return 'bg-zinc-100 text-zinc-600';
        case 'sent': return 'bg-blue-100 text-blue-600';
        case 'approved': return 'bg-green-100 text-green-600';
        case 'rejected': return 'bg-red-100 text-red-600';
        default: return 'bg-zinc-100 text-zinc-600';
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Project Estimates" />

        <div class="p-6 max-w-[1600px] mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-4">
                <div>
                    <h1 class="text-4xl font-black tracking-tighter uppercase text-zinc-900 dark:text-white">
                        Project <span class="text-orange-600">Estimates.</span>
                    </h1>
                    <p class="text-zinc-500 text-sm font-bold uppercase tracking-widest mt-2 italic">Structural Financial Archive</p>
                </div>
                <Link :href="route('admin.estimates.create')" class="px-8 py-4 bg-zinc-900 dark:bg-white text-white dark:text-black font-black uppercase text-xs tracking-[0.3em] shadow-2xl hover:bg-orange-600 dark:hover:bg-orange-600 dark:hover:text-white transition-all transform hover:scale-105 rounded-xl flex items-center gap-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    New Estimate
                </Link>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-100 dark:border-zinc-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-400">Reference</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-400">Project</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-400">Date</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-400 text-right">Amount</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-400 text-center">Status</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-400 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            <tr v-for="estimate in estimates.data" :key="estimate.id" class="group hover:bg-zinc-50/50 dark:hover:bg-zinc-800/20 transition-all duration-300">
                                <td class="px-8 py-6">
                                    <span class="text-sm font-black tracking-tight text-zinc-900 dark:text-white group-hover:text-orange-600 transition-colors italic">
                                        #{{ estimate.reference_number }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black uppercase tracking-tight">{{ estimate.portfolio?.title || estimate.project_title || 'Untitled Project' }}</span>
                                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">{{ estimate.portfolio ? estimate.portfolio.project_type : 'Custom Concept' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-sm font-bold text-zinc-500 uppercase">{{ new Date(estimate.estimate_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }}</td>
                                <td class="px-8 py-6 text-right">
                                    <span class="text-lg font-black tracking-tighter text-zinc-900 dark:text-white italic">{{ formatCurrency(estimate.total_amount) }}</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span :class="['px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg', getStatusColor(estimate.status)]">
                                        {{ estimate.status }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex justify-center gap-4">
                                        <Link :href="route('admin.estimates.edit', estimate.id)" class="text-zinc-400 hover:text-orange-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="estimates.data.length === 0">
                                <td colspan="6" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <svg class="w-16 h-16 mb-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="text-sm font-black uppercase tracking-[0.4em]">Historical Archive Empty</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
