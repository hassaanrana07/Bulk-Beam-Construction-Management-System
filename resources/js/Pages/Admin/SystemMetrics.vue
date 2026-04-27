<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    user_role: String,
    stats: Object,
    recent_logs: Array,
});

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
};
</script>

<template>
    <AdminLayout>
        <Head title="System Metrics | Operational Core" />

        <ModuleHeader title="System Intelligence Matrix">
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mt-2">
                    Monitoring technical performance, server health, and real-time operational logs.
                </p>
            </template>
        </ModuleHeader>

        <div class="space-y-10">
            <!-- Hardware Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="(val, label) in {
                    'CPU Load': stats.cpu_load,
                    'Memory Usage': stats.memory_usage,
                    'Disk Space': stats.disk_usage,
                    'Database Size': stats.db_size
                }" :key="label" class="bg-black border border-zinc-900 p-8 rounded-xl group hover:border-orange-600/50 transition-all">
                    <p class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 mb-2">{{ label }}</p>
                    <p class="text-2xl font-black text-white italic tracking-tighter">{{ val }}</p>
                    <div class="mt-4 h-1 bg-zinc-900 rounded-full overflow-hidden">
                        <div class="h-full bg-orange-600 w-1/3 group-hover:w-1/2 transition-all duration-1000"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- System Pulse -->
                <div class="lg:col-span-1 bg-black border border-zinc-900 p-8 rounded-xl space-y-6">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-white border-b border-zinc-900 pb-4">System Pulse</h3>
                    <div class="space-y-4">
                        <div v-for="(v, l) in {
                            'Active Sessions': stats.active_sessions,
                            'Critical Errors': stats.error_count,
                            'Last Backup': stats.last_backup,
                            'Socket Status': 'Healthy'
                        }" :key="l" class="flex justify-between items-center py-2 border-b border-zinc-900/50">
                            <span class="text-[9px] font-black uppercase tracking-widest text-zinc-500">{{ l }}</span>
                            <span class="text-[10px] font-black text-orange-500 italic">{{ v }}</span>
                        </div>
                    </div>
                    <button class="w-full py-4 border border-zinc-800 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600 hover:text-white hover:border-orange-600 transition-all rounded-lg">
                        Run Hardware Diagnostic
                    </button>
                </div>

                <!-- Live Audit Stream -->
                <div class="lg:col-span-2 bg-black border border-zinc-900 p-8 rounded-xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-white mb-8 border-b border-zinc-900 pb-4">Live Audit Stream</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left border-b border-zinc-900">
                                    <th class="pb-4 text-[9px] font-black uppercase tracking-widest text-zinc-600">Technician</th>
                                    <th class="pb-4 text-[9px] font-black uppercase tracking-widest text-zinc-600">Protocol Action</th>
                                    <th class="pb-4 text-right text-[9px] font-black uppercase tracking-widest text-zinc-600">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-900">
                                <tr v-for="log in recent_logs" :key="log.id" class="hover:bg-zinc-950/50 transition-colors">
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-7 h-7 bg-zinc-900 border border-zinc-800 flex items-center justify-center text-[10px] font-black rounded uppercase">{{ log.user?.name.charAt(0) }}</div>
                                            <span class="text-[10px] font-black text-white uppercase">{{ log.user?.name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <span class="px-2 py-1 bg-zinc-900 text-orange-500 text-[8px] font-black uppercase tracking-widest rounded border border-zinc-800">{{ log.action }}</span>
                                    </td>
                                    <td class="py-4 text-right text-[9px] font-mono text-zinc-600 uppercase">{{ formatDate(log.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
