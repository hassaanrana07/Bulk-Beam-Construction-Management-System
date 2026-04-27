<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    logs: Object,
});

const formatDate = (date) => new Date(date).toLocaleString();
</script>

<template>
    <AdminLayout>
        <Head title="Audit Archive | Operational Core" />

        <ModuleHeader title="System Audit Logs">
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mt-2">Historical archive of all system actions and modifications.</p>
            </template>
            <template #actions>
                <a :href="route('admin.audit-logs.export-csv')" class="px-6 py-3 border border-zinc-900 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 hover:text-white hover:border-orange-600 transition-all rounded-lg">
                    Export Audit CSV
                </a>
            </template>
        </ModuleHeader>

        <div class="bg-black border border-zinc-900 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-zinc-900/50 text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500 border-b border-zinc-800">
                            <th class="px-8 py-6">Operator</th>
                            <th class="px-8 py-6">Action Protocol</th>
                            <th class="px-8 py-6">Entity Target</th>
                            <th class="px-8 py-6">IP Address</th>
                            <th class="px-8 py-6 text-right">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-900">
                        <tr v-for="log in logs.data" :key="log.id" class="hover:bg-zinc-950/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 bg-zinc-900 border border-zinc-800 flex items-center justify-center text-[10px] font-black rounded uppercase">{{ log.user?.name.charAt(0) }}</div>
                                    <span class="text-[10px] font-black text-white uppercase">{{ log.user?.name || 'SYSTEM' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-2 py-1 bg-zinc-900 text-orange-500 text-[8px] font-black uppercase tracking-widest rounded border border-zinc-800">{{ log.action }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-tighter">{{ log.model_type?.split('\\').pop() }} #{{ log.model_id }}</p>
                            </td>
                            <td class="px-8 py-6 text-[10px] font-mono text-zinc-600">{{ log.ip_address }}</td>
                            <td class="px-8 py-6 text-right text-[9px] font-mono text-zinc-500 uppercase">{{ formatDate(log.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Simple Pagination Info -->
            <div class="p-6 bg-zinc-950/50 border-t border-zinc-900 flex justify-between items-center">
                <p class="text-[9px] font-black uppercase tracking-widest text-zinc-700">Displaying {{ logs.from }}-{{ logs.to }} of {{ logs.total }} Audit Nodes</p>
                <div class="flex gap-2">
                    <!-- Pagination links omitted for brevity, standard Inertia pagination can be added -->
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
