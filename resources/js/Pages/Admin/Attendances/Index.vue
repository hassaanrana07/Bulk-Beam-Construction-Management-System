<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    attendances: Array,
    users: Array,
    stats: Object,
});

const clockIn = () => {
    router.post(route('admin.attendances.clock-in'));
};

const clockOut = () => {
    router.post(route('admin.attendances.clock-out'));
};

const getStatusColor = (s) => {
    const colors = { present: 'text-emerald-500', absent: 'text-red-500', late: 'text-orange-500', on_leave: 'text-zinc-500' };
    return colors[s] || 'text-zinc-500';
};
</script>

<template>
    <AdminLayout>
        <Head title="Attendance | Operational Core" />

        <ModuleHeader title="Personnel Attendance Matrix">
            <template #actions>
                <div class="flex gap-4">
                    <button @click="clockIn" class="px-6 py-3 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-emerald-700 transition-all">Clock In</button>
                    <button @click="clockOut" class="px-6 py-3 bg-red-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-red-700 transition-all">Clock Out</button>
                </div>
            </template>
        </ModuleHeader>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-10">
            <div v-for="(val, label) in {
                'Today Present': stats.present_today,
                'Late Arrivals': stats.late_today,
                'On Leave': stats.on_leave,
                'Avg Clock-in': stats.avg_clock_in
            }" :key="label" class="bg-black border border-zinc-900 p-8 rounded-xl shadow-xl">
                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2">{{ label }}</p>
                <p class="text-3xl font-black text-white italic tracking-tighter">{{ val }}</p>
            </div>
        </div>

        <div class="bg-black border border-zinc-900 rounded-xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-zinc-900/50 text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500 border-b border-zinc-800">
                            <th class="px-8 py-6">Member</th>
                            <th class="px-8 py-6">Date</th>
                            <th class="px-8 py-6">Clock In</th>
                            <th class="px-8 py-6">Clock Out</th>
                            <th class="px-8 py-6">Status</th>
                            <th class="px-8 py-6 text-right">Activity Log</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-900">
                        <tr v-for="att in attendances" :key="att.id" class="hover:bg-zinc-950/50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 bg-zinc-900 border border-zinc-800 flex items-center justify-center text-[10px] uppercase font-black rounded">{{ att.user?.name.charAt(0) }}</div>
                                    <span class="text-[10px] font-black text-white uppercase">{{ att.user?.name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-[10px] font-bold text-zinc-500 uppercase">{{ att.date }}</td>
                            <td class="px-8 py-6 text-[10px] font-black text-emerald-500 italic">{{ att.clock_in || '--:--' }}</td>
                            <td class="px-8 py-6 text-[10px] font-black text-red-500 italic">{{ att.clock_out || '--:--' }}</td>
                            <td class="px-8 py-6">
                                <span :class="getStatusColor(att.status)" class="text-[9px] font-black uppercase tracking-widest">{{ att.status }}</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <span class="text-[8px] font-black text-zinc-700 opacity-0 group-hover:opacity-100 uppercase tracking-widest transition-opacity">Operational</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
