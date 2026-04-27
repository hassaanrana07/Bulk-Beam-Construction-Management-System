<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    lead: Object
});

const form = useForm({
    status: props.lead.status,
    internal_notes: props.lead.internal_notes || '',
});

const updateLead = () => {
    form.put(route('admin.leads.update', props.lead.id));
};
</script>

<template>
    <AdminLayout>
        <Head :title="`Audit: ${lead.name}`" />

        <ModuleHeader :title="`Lead: ${lead.name}`">
            <template #subtitle>
                <Link :href="route('admin.leads.index')" class="text-[10px] font-black text-zinc-600 hover:text-orange-600 uppercase tracking-[0.3em] flex items-center gap-2 mt-2 transition-colors">
                    <span>←</span> Return to Pipeline Overview
                </Link>
            </template>
        </ModuleHeader>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-10">
                <!-- Core Data -->
                <div class="bg-black border border-zinc-900 p-10 space-y-12 shadow-2xl">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500 border-b border-zinc-900 pb-4 mb-8 italic">Metadata Analysis</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-2">
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600">Email Vector</p>
                                <p class="text-sm font-bold text-white tracking-wide border-b border-zinc-900 pb-2">{{ lead.email }}</p>
                            </div>
                            <div class="space-y-2" v-if="lead.phone">
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600">Communication Node</p>
                                <p class="text-sm font-bold text-white tracking-wide border-b border-zinc-900 pb-2">{{ lead.phone }}</p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600">Acquisition Source</p>
                                <p class="text-xs font-black text-orange-600 uppercase tracking-[0.2em] border-b border-zinc-900 pb-2">{{ lead.source || 'Direct Channel' }}</p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600">Transmission Timestamp</p>
                                <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest border-b border-zinc-900 pb-2">{{ new Date(lead.created_at).toLocaleString() }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500 border-b border-zinc-900 pb-4 mb-8 italic">Inquiry Payload</h3>
                        <div class="p-8 bg-zinc-950 border-l-2 border-orange-600 font-medium text-zinc-400 leading-relaxed italic">
                            "{{ lead.message || 'No manual message provided in this transmission.' }}"
                        </div>
                    </div>

                    <div v-if="lead.form_data">
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500 border-b border-zinc-900 pb-4 mb-8 italic">Structured Object Data</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="(val, key) in lead.form_data" :key="key" class="p-6 bg-zinc-950 border border-zinc-900">
                                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2">{{ key }}</p>
                                <p class="text-xs font-bold text-white tracking-wide">{{ val }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Management Sidebar -->
            <div class="lg:col-span-1 space-y-10">
                <div class="bg-black border border-zinc-900 p-10 space-y-10 shadow-2xl sticky top-32">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-orange-600 mb-6 italic">Pipeline Workflow</h3>
                        <select v-model="form.status" @change="updateLead" class="w-full bg-zinc-950 border border-zinc-900 text-white text-xs font-black uppercase tracking-[0.2em] py-4 px-6 focus:ring-0 focus:border-orange-600 transition-colors">
                            <option class="bg-black" v-for="status in ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost']" :key="status" :value="status">
                                {{ status }}
                            </option>
                        </select>
                    </div>

                    <div class="border-t border-zinc-900 pt-8">
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-orange-600 mb-6 italic">Lead Scoring Matrix</h3>
                        <div class="flex items-center gap-6">
                            <div class="text-6xl font-black tracking-tighter text-white">{{ lead.lead_score || 0 }}</div>
                            <div class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600 leading-relaxed">
                                {{ lead.lead_score >= 50 ? 'HIGH PRIORITY VECTOR' : 'STANDARD INQUIRY' }}
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-zinc-900 pt-8">
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-orange-600 mb-6 italic">Internal Strategic Notes</h3>
                        <textarea v-model="form.internal_notes" @blur="updateLead" rows="6" class="w-full bg-zinc-950 border border-zinc-900 text-zinc-300 text-xs font-medium leading-relaxed p-6 focus:ring-0 focus:border-orange-600 transition-colors placeholder-zinc-800" placeholder="strategic observations..."></textarea>
                        <p class="text-[8px] font-bold text-zinc-600 uppercase tracking-[0.3em] mt-3 text-right flex items-center justify-end gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            Auto-saving active
                        </p>
                    </div>

                    <div class="border-t border-zinc-900 pt-8">
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500 mb-6 italic">Infrastructure Context</h3>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600">Origin IP Address</p>
                                <code class="text-xs font-bold text-orange-600 bg-zinc-950 px-2 py-1 border border-zinc-900">{{ lead.ip_address || '0.0.0.0' }}</code>
                            </div>
                            <div class="space-y-2">
                                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600">Client User-Agent</p>
                                <p class="text-[9px] font-bold text-zinc-500 truncate font-mono">{{ lead.user_agent || 'Unknown Infrastructure' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
