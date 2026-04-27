<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    Chart as ChartJS,
    Title, Tooltip, Legend,
    BarElement, CategoryScale, LinearScale,
    PointElement, LineElement, ArcElement, Filler
} from 'chart.js';
import { Bar, Doughnut } from 'vue-chartjs';

ChartJS.register(
    Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale,
    PointElement, LineElement, ArcElement, Filler
);

const props = defineProps({
    portfolios: Array,
    stats: Object,
    chart_data: Object
});

const baseOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        x: { grid: { display: false }, ticks: { color: '#71717a', font: { size: 9, weight: '700' } } },
        y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#71717a', font: { size: 9, weight: '700' } } }
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 0
    }).format(value || 0);
};
</script>

<template>
    <AdminLayout>
        <Head title="Revenue Matrix | Financial Core" />

        <ModuleHeader title="Revenue Inflow Tracking">
            <template #subtitle>
                <div class="flex flex-col gap-2">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600">Monitoring capital influx and outstanding receivables across all projects.</p>
                </div>
            </template>
        </ModuleHeader>

        <!-- Dynamic Visualization Matrix -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <div class="lg:col-span-2 bg-black border border-zinc-900 p-8 rounded-2xl relative overflow-hidden group">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-white italic">Inflow Protocol Stream</h3>
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                            <span class="text-[8px] font-black uppercase text-zinc-500 tracking-widest">Received</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-zinc-800 rounded-full"></div>
                            <span class="text-[8px] font-black uppercase text-zinc-500 tracking-widest">Expected</span>
                        </div>
                    </div>
                </div>
                <div class="h-64">
                    <Bar v-if="chart_data?.revenue_stream" :data="{
                        labels: chart_data.revenue_stream.labels,
                        datasets: [
                            { label: 'Received', data: chart_data.revenue_stream.received, backgroundColor: '#10b981', borderRadius: 4 },
                            { label: 'Expected', data: chart_data.revenue_stream.expected, backgroundColor: '#27272a', borderRadius: 4 }
                        ]
                    }" :options="baseOptions" />
                </div>
            </div>
            <div class="bg-black border border-zinc-900 p-8 rounded-2xl">
                 <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-white mb-10 italic">Portfolio Integrity</h3>
                 <div class="h-64 flex items-center justify-center">
                    <Doughnut v-if="chart_data?.revenue_by_status" :data="{
                        labels: chart_data.revenue_by_status.labels,
                        datasets: [{
                            data: chart_data.revenue_by_status.values,
                            backgroundColor: ['#f97316', '#10b981', '#3b82f6', '#ef4444'],
                            borderWidth: 0,
                            hoverOffset: 12
                        }]
                    }" :options="{ responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false } } }" />
                 </div>
                 <div class="mt-8 space-y-3">
                    <div v-for="(label, i) in chart_data?.revenue_by_status.labels" :key="label" class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-1.5 rounded-full" :style="{ backgroundColor: ['#f97316', '#10b981', '#3b82f6', '#ef4444'][i] }"></div>
                            <span class="text-[9px] font-black uppercase text-zinc-500 tracking-widest">{{ label }}</span>
                        </div>
                        <span class="text-[9px] font-black text-white italic">{{ formatCurrency(chart_data?.revenue_by_status.values[i]) }}</span>
                    </div>
                 </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div v-for="stat in [
                { label: 'Total Received', val: formatCurrency(stats?.total_received), icon: '📈', color: 'text-emerald-500' },
                { label: 'Expected Revenue', val: formatCurrency(stats?.total_expected), icon: '📊', color: 'text-white' },
                { label: 'Pending Arrears', val: formatCurrency(stats?.total_pending), icon: '⏳', color: 'text-red-500' },
            ]" :key="stat.label" class="bg-black border border-zinc-900 p-8 rounded-xl shadow-2xl relative overflow-hidden group">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">{{ stat.label }}</span>
                    <span class="text-xl">{{ stat.icon }}</span>
                </div>
                <h3 class="text-3xl font-black italic tracking-tighter" :class="stat.color">{{ stat.val }}</h3>
                <div v-if="stat.label === 'Total Received'" class="mt-4">
                    <div class="w-full bg-zinc-900 h-1 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full" :style="{ width: stats?.avg_collection_rate + '%' }"></div>
                    </div>
                    <p class="text-[8px] font-black text-zinc-600 uppercase tracking-widest mt-2">Collection Rate: {{ stats?.avg_collection_rate?.toFixed(1) }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-black border border-zinc-100/5 shadow-2xl rounded-2xl overflow-hidden">
            <div class="p-8 border-b border-zinc-900">
                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white">Revenue Sources Protocol</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-zinc-900/30 text-[9px] font-black uppercase tracking-widest text-zinc-500 border-b border-zinc-900">
                            <th class="px-8 py-6">Project / Client Node</th>
                            <th class="px-8 py-6 text-center">Protocol Status</th>
                            <th class="px-8 py-6 text-right">Expected</th>
                            <th class="px-8 py-6 text-right">Inflow (Rec.)</th>
                            <th class="px-8 py-6 text-right">Delta (Pending)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-900">
                        <tr v-for="p in portfolios" :key="p.id" class="hover:bg-zinc-900/40 transition-colors">
                            <td class="px-8 py-6">
                                <p class="text-xs font-black text-white uppercase tracking-tight">{{ p.title }}</p>
                                <p class="text-[8px] text-zinc-600 font-bold uppercase mt-1">ID: #{{ p.id }} · {{ new Date(p.created_at).toLocaleDateString() }}</p>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-3 py-1 bg-zinc-900 border border-zinc-800 text-[8px] font-black uppercase tracking-widest text-zinc-400 rounded-lg">
                                    {{ p.execution_status }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right font-mono text-xs text-zinc-400">
                                {{ formatCurrency(p.expected_revenue) }}
                            </td>
                            <td class="px-8 py-6 text-right font-mono text-xs text-emerald-500 font-black">
                                {{ formatCurrency(p.received_payment) }}
                            </td>
                            <td class="px-8 py-6 text-right font-mono text-xs" :class="p.pending_payment > 0 ? 'text-red-500' : 'text-zinc-600'">
                                {{ formatCurrency(p.pending_payment) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
