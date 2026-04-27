<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    leads: Array
});

const search = ref('');

const filteredLeads = computed(() => {
    if (!search.value) return props.leads;
    const lowerSearch = search.value.toLowerCase();
    return props.leads.filter(lead => 
        lead.name.toLowerCase().includes(lowerSearch) || 
        lead.email.toLowerCase().includes(lowerSearch) ||
        (lead.status && lead.status.toLowerCase().includes(lowerSearch))
    );
});

const getScoreColor = (score) => {
    if (score >= 70) return 'text-green-600';
    if (score >= 40) return 'text-primary';
    return 'text-slate-400';
};
</script>

<template>
    <AdminLayout>
        <Head title="Lead Pipeline" />

        <ModuleHeader 
            title="Lead Infrastructure"
            v-model:search="search"
        >
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mt-2 italic">
                    Monitoring {{ leads.length }} high-intent construction inquiries across all channels.
                </p>
            </template>
        </ModuleHeader>

        <div class="bg-white border border-slate-200 shadow-xl shadow-slate-200/50 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 italic">Contact Identity</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 italic text-center">Source Channel</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 italic text-center">Lead Score</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 italic text-center">Status Node</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 italic text-right">Verification</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="lead in filteredLeads" :key="lead.id" class="hover:bg-primary/[0.02] transition-colors group cursor-pointer" @click="$inertia.visit(route('admin.leads.show', lead.id))">
                            <td class="px-8 py-6">
                                <div class="font-black text-xs uppercase tracking-tight text-slate-900 group-hover:text-primary transition-colors italic">{{ lead.name }}</div>
                                <div class="text-[8px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-1">{{ lead.email }}</div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-[8px] font-black text-primary border border-primary/10 bg-primary/5 px-3 py-1 uppercase tracking-widest inline-block skew-x-[-5deg] rounded">{{ lead.source || 'Direct' }}</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div :class="[getScoreColor(lead.lead_score), 'text-lg font-black tracking-tighter italic']">
                                    {{ lead.lead_score || 0 }}
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span :class="[
                                    lead.status === 'new' ? 'text-primary border-primary/20 bg-primary/5' : 'text-slate-400 border-slate-100 bg-slate-50',
                                    'px-3 py-1 text-[9px] font-black uppercase tracking-widest border rounded-lg italic'
                                ]">{{ lead.status }}</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <Link :href="route('admin.leads.show', lead.id)" class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-white bg-slate-50 px-4 py-2 hover:bg-primary border border-slate-100 hover:border-primary transition-all rounded-xl shadow-inner italic">Audit Data</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Empty State -->
                <div v-if="filteredLeads.length === 0" class="py-24 flex flex-col items-center justify-center text-center bg-slate-50/30 rounded-2xl">
                    <div class="w-16 h-16 bg-slate-100 flex items-center justify-center mb-6 rounded-2xl">
                        <span class="text-slate-300 text-2xl font-black italic">?</span>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400">No signals detected in pipeline matrix.</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
