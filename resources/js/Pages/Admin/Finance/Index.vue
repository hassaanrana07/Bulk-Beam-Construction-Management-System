<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
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
    products: Array,
    stats: Object,
    chart_data: Object
});

const selectedIds = ref([]);
const toggleSelectAll = (event) => {
    if (event.target.checked) {
        selectedIds.value = props.products.map(p => p.id);
    } else {
        selectedIds.value = [];
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 0
    }).format(value || 0);
};

const getStatusColor = (status) => {
    const colors = {
        'Completed': 'bg-green-600',
        'In Progress': 'bg-blue-600',
        'Planning': 'bg-zinc-700',
        'On Hold': 'bg-red-600'
    };
    return colors[status] || 'bg-zinc-800';
};

const deleteProduct = (id) => {
    if (confirm('Are you sure you want to purge this product from the fiscal matrix?')) {
        router.delete(route('admin.portfolios.destroy', id));
    }
};

const bulkDownload = () => {
    // Create a hidden form to submit via POST for bulk PDF
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = route('admin.finance.report');
    form.target = '_blank';

    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
    }

    if (selectedIds.value.length) {
        selectedIds.value.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });
    }

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
};

const baseOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        x: { grid: { display: false }, ticks: { color: '#71717a', font: { size: 9, weight: '700' } } },
        y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#71717a', font: { size: 9, weight: '700' } } }
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Financial Intelligence" />

        <ModuleHeader title="Finance & Revenue">
            <template #actions>
                <div class="flex gap-4">
                    <Link :href="route('admin.portfolios.create')" class="px-6 py-3 bg-primary text-white text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-primary transition-all skew-x-[-3deg]">
                        <span class="skew-x-[3deg] flex items-center gap-2">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                             Add New Product
                        </span>
                    </Link>
                    <button 
                        @click="bulkDownload"
                        class="px-6 py-3 bg-white text-black text-[10px] font-black uppercase tracking-widest hover:bg-primary hover:text-white transition-all skew-x-[-3deg]"
                    >
                        <span class="skew-x-[3deg] flex items-center gap-2">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                             Full Fiscal Audit PDF
                        </span>
                    </button>
                </div>
            </template>
        </ModuleHeader>

        <!-- Dynamic Visualization Matrix -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <div class="lg:col-span-2 bg-black border border-zinc-900 p-8 rounded-2xl relative overflow-hidden group">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-white italic">Profit Performance Cluster</h3>
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2"><div class="w-2 h-2 bg-primary rounded-full"></div><span class="text-[8px] font-black uppercase text-zinc-500 tracking-widest">Net Profit</span></div>
                        <div class="flex items-center gap-2"><div class="w-2 h-2 bg-zinc-800 rounded-full"></div><span class="text-[8px] font-black uppercase text-zinc-500 tracking-widest">Investment</span></div>
                    </div>
                </div>
                <div class="h-64">
                    <Bar v-if="chart_data?.profit_performance" :data="{
                        labels: chart_data.profit_performance.labels,
                        datasets: [
                            { label: 'Net Profit', data: chart_data.profit_performance.profit, backgroundColor: '#1656D1', borderRadius: 4 },
                            { label: 'Capital', data: chart_data.profit_performance.budget, backgroundColor: '#27272a', borderRadius: 4 }
                        ]
                    }" :options="baseOptions" />
                </div>
            </div>
            <div class="bg-black border border-zinc-900 p-8 rounded-2xl">
                 <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-white mb-10 italic">Efficiency Radar</h3>
                 <div class="h-64 flex items-center justify-center">
                    <Doughnut v-if="chart_data?.efficiency_radar" :data="{
                        labels: chart_data.efficiency_radar.labels,
                        datasets: [{
                            data: chart_data.efficiency_radar.values,
                            backgroundColor: ['#10b981', '#1656D1', '#3b82f6'],
                            borderWidth: 0,
                        }]
                    }" :options="{ responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false } } }" />
                 </div>
                 <div class="mt-8 space-y-3">
                    <div v-for="(label, i) in chart_data?.efficiency_radar.labels" :key="label" class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-1.5 rounded-full" :style="{ backgroundColor: ['#10b981', '#1656D1', '#3b82f6'][i] }"></div>
                            <span class="text-[9px] font-black uppercase text-zinc-500 tracking-widest">{{ label }} Avg.</span>
                        </div>
                        <span class="text-[9px] font-black text-white italic">{{ formatCurrency(chart_data?.efficiency_radar.values[i]) }}</span>
                    </div>
                 </div>
            </div>
        </div>

        <!-- Financial Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div v-for="stat in [
                { label: 'Gross Revenue', val: formatCurrency(stats?.total_revenue), sub: `Exp: ${formatCurrency(stats?.total_expected)}`, color: 'text-white' },
                { label: 'Capital Investment', val: formatCurrency(stats?.total_invested), sub: 'Total Budget Allocated', color: 'text-white' },
                { label: 'Net Profit', val: formatCurrency(stats?.total_profit), sub: `${stats?.profit_margin?.toFixed(1)}% Avg. Margin`, color: 'text-green-500' },
                { label: 'Arrears / Pending', val: formatCurrency(stats?.total_pending), sub: 'Outstanding Receivables', color: 'text-red-600' },
            ]" :key="stat.label" class="bg-black border border-zinc-900 p-6 shadow-2xl skew-x-[-2deg] overflow-hidden group hover:border-primary transition-all">
                <div class="skew-x-[2deg]">
                    <p class="text-[9px] font-black text-zinc-500 uppercase tracking-[0.3em] mb-4">{{ stat.label }}</p>
                    <h3 class="text-2xl font-black tracking-tighter truncate" :class="stat.color">{{ stat.val }}</h3>
                    <p class="text-[8px] font-black text-zinc-600 uppercase tracking-widest mt-4">{{ stat.sub }}</p>
                </div>
            </div>
        </div>

        <!-- Detailed Product Revenue Table -->
        <div class="bg-black border border-zinc-900 shadow-2xl overflow-hidden">
            <div class="p-8 border-b border-zinc-900 flex justify-between items-center">
                <h3 class="text-xs font-black uppercase tracking-[0.4em] text-white italic">Product Asset Ledger</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-zinc-900 bg-zinc-950">
                            <th class="px-6 py-6 w-10">
                                <input type="checkbox" @change="toggleSelectAll" :checked="selectedIds.length === products.length && products.length > 0" class="bg-zinc-950 border-zinc-800 text-primary focus:ring-0 rounded-none cursor-pointer">
                            </th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-widest text-zinc-500">Product/Project</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-widest text-zinc-500">Capital (Budget)</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-widest text-zinc-500">Revenue (Rec.)</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-widest text-zinc-500">Profit/Loss</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-widest text-zinc-500">Status</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-widest text-zinc-500 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-900" v-if="products.length > 0">
                        <tr v-for="product in products" :key="product.id" class="hover:bg-zinc-900/50 transition-colors group">
                            <td class="px-6 py-6 text-center">
                                <input type="checkbox" v-model="selectedIds" :value="product.id" class="bg-zinc-950 border-zinc-800 text-primary focus:ring-0 rounded-none cursor-pointer">
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-xs font-black text-white uppercase tracking-tight group-hover:text-primary transition-colors">{{ product.title }}</p>
                            </td>
                            <td class="px-8 py-6 font-mono text-xs text-zinc-400">
                                {{ formatCurrency(product.total_budget || 0) }}
                            </td>
                            <td class="px-8 py-6 font-mono text-xs text-zinc-400">
                                {{ formatCurrency(product.received_payment || 0) }}
                                <p class="text-[8px] text-zinc-600 font-black uppercase tracking-widest mt-1">Expected: {{ formatCurrency(product.expected_revenue || 0) }}</p>
                            </td>
                            <td class="px-8 py-6 font-mono text-xs" :class="product.project_profit >= 0 ? 'text-green-500' : 'text-red-600'">
                                {{ formatCurrency(product.project_profit || 0) }}
                            </td>
                            <td class="px-8 py-6">
                                <span :class="[getStatusColor(product.execution_status), 'text-[8px] font-black uppercase tracking-widest px-3 py-1 text-white']">
                                    {{ product.execution_status }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-3">
                                    <Link :href="route('admin.portfolios.edit', product.id)" class="text-zinc-500 hover:text-white transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </Link>
                                    <button @click="deleteProduct(product.id)" class="text-zinc-500 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                     <a :href="route('admin.portfolios.pdf', product.id)" class="text-zinc-500 hover:text-primary transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="7" class="px-8 py-12 text-center">
                                <p class="text-[10px] font-black uppercase tracking-[0.4em] text-zinc-600 italic">No records found within the fiscal matrix</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
