<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed, inject } from 'vue';
import {
    Chart as ChartJS,
    Title, Tooltip, Legend,
    BarElement, CategoryScale, LinearScale,
    PointElement, LineElement, ArcElement, Filler
} from 'chart.js';
import { Bar, Line, Doughnut } from 'vue-chartjs';

ChartJS.register(
    Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale,
    PointElement, LineElement, ArcElement, Filler
);

const page = usePage();
const settings = computed(() => page.props.settings || {});
const currency = computed(() => settings.value?.currency || 'USD');

const props = defineProps({
    user_role:            String,
    stats:                Object,
    chart_data:           Object,
    recent_activity:      Array,
    recent_announcements: Array,
    // Manager
    projects:             Array,
    upcoming_deadlines:   Array,
    // Finance
    recent_expenses:      Array,
    purchase_queue:       Array,
    // Editor / Support
    recent_blogs:         Array,
    recent_inquiries:     Array,
    recent_leads:         Array,
    tasks:                Array,
    attendance:           Array,
    last_attendance:      Object,
    team_members:         Array,
});

const isDark = inject('isDark', ref(true));
const financeTab = ref('overview'); // overview, revenue, profit, team
// ── Chart helpers ─────────────────────────────────────────────────────────────
const textColor    = computed(() => isDark.value ? '#94a3b8' : '#64748b');
const tooltipBg    = computed(() => isDark.value ? '#111827' : '#ffffff');
const tooltipText  = computed(() => isDark.value ? '#ffffff' : '#0F172A');
const gridColor    = computed(() => isDark.value ? 'rgba(255,255,255,0.03)' : 'rgba(0,0,0,0.04)');

const baseOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    animation: { duration: 800, easing: 'easeInOutQuart' },
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: tooltipBg.value,
            titleColor:       tooltipText.value,
            bodyColor:        tooltipText.value,
            borderColor:      isDark.value ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.08)',
            borderWidth: 1,
            padding: 12,
            cornerRadius: 6,
            displayColors: false,
        }
    },
    scales: {
        x: { grid: { display: false }, ticks: { color: textColor.value, font: { size: 9, weight: '700' } } },
        y: { grid: { color: gridColor.value }, ticks: { color: textColor.value, font: { size: 9, weight: '700' } } }
    }
}));

const donutOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true, position: 'bottom',
            labels: { color: textColor.value, font: { size: 9, family: 'monospace' }, padding: 14, boxWidth: 12 }
        },
        tooltip: {
            backgroundColor: tooltipBg.value,
            titleColor: tooltipText.value,
            bodyColor: tooltipText.value,
        }
    },
    cutout: '65%'
}));

const yearlyOptions = computed(() => ({
    ...baseOptions.value,
    plugins: {
        ...baseOptions.value.plugins,
        legend: { display: true, position: 'top', labels: { color: textColor.value, font: { size: 9, family: 'monospace' }, boxWidth: 12, padding: 16 } }
    }
}));

// ── Utility ───────────────────────────────────────────────────────────────────
const formatCurrency = (val) => {
    const curr = currency.value || 'USD';
    try {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: curr, maximumFractionDigits: 0 }).format(val || 0);
    } catch {
        return `${curr} ${Number(val || 0).toLocaleString()}`;
    }
};

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

const statusDot = (s) => {
    const m = { ongoing: 'bg-primary', completed: 'bg-emerald-500', delayed: 'bg-red-500', blocked: 'bg-red-500', in_progress: 'bg-blue-400', todo: 'bg-slate-400', pending: 'bg-amber-500' };
    return m[s?.toLowerCase()] || 'bg-slate-500';
};
const statusText = (s) => {
    const m = { ongoing: 'text-primary', completed: 'text-emerald-500', delayed: 'text-red-500', blocked: 'text-red-500', in_progress: 'text-blue-400', todo: 'text-slate-400', pending: 'text-amber-500' };
    return m[s?.toLowerCase()] || 'text-slate-500';
};

// Role helpers
const role = computed(() => props.user_role || '');
const isSuperAdmin = computed(() => role.value === 'Super Admin');
const isManager    = computed(() => ['Manager', 'Admin Manager'].includes(role.value));
const isFinance    = computed(() => ['Finance Manager', 'Finance Support', 'Financial Support', 'Finance', 'Manager', 'Admin Manager'].includes(role.value));
</script>

<template>
    <AdminLayout>
        <Head title="Operational Hub" />
        <div class="p-6">
            <!-- ═══════════════════════════════════════════════
                 SUPER ADMIN DASHBOARD
            ════════════════════════════════════════════════ -->
            <template v-if="isSuperAdmin">
                <div class="space-y-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-black uppercase tracking-[0.15em] text-primary">Super Admin Nexus</h1>
                            <p class="text-[10px] text-zinc-500 uppercase tracking-widest mt-1">Complete system-wide intelligence & financial overview</p>
                        </div>
                        <span class="px-3 py-1.5 bg-primary/10 border border-primary/30 text-primary text-[9px] font-black uppercase tracking-widest rounded">
                            {{ stats?.system_health || 'Optimal' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                        <div v-for="kpi in [
                            { label: 'Total Projects',    val: stats?.total_projects,    icon: 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z', color: 'bg-amber-500/10 text-amber-500', bar: 'bg-amber-500', trend: '+12%' },
                            { label: 'Active Projects',   val: stats?.active_projects,   icon: 'M13 10V3L4 14h7v7l9-11h-7z', color: 'bg-orange-500/10 text-orange-500', bar: 'bg-orange-500', trend: '-5%' },
                            { label: 'Completed',         val: stats?.completed_projects,icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', color: 'bg-emerald-500/10 text-emerald-500', bar: 'bg-emerald-500', trend: '+20%' },
                            { label: 'System Users',      val: stats?.user_count,        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', color: 'bg-blue-500/10 text-blue-500', bar: 'bg-blue-500', trend: '+8%' },
                        ]" :key="kpi.label" class="bg-[#111827] border border-white/5 p-8 rounded-2xl relative overflow-hidden group hover:border-white/10 transition-all duration-300 shadow-2xl">
                            <div class="flex items-center justify-between mb-8">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center" :class="kpi.color">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="kpi.icon" /></svg>
                                </div>
                                <div class="flex items-center gap-1 text-[10px] font-black" :class="kpi.trend.startsWith('+') ? 'text-emerald-500' : 'text-red-500'">
                                    <svg class="w-3 h-3" :class="{'rotate-180': kpi.trend.startsWith('-')}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    {{ kpi.trend }}
                                </div>
                            </div>
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2">{{ kpi.label }}</p>
                            <p class="text-4xl font-black text-white tracking-tighter">{{ kpi.val ?? '—' }}</p>
                            
                            <!-- Accent Bar at bottom -->
                            <div class="absolute bottom-0 left-0 w-full h-1 bg-slate-800">
                                <div class="h-full w-1/3 transition-all duration-1000" :class="kpi.bar"></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-8 bg-primary rounded-full"></div>
                            <h2 class="text-sm font-black uppercase tracking-[0.25em] text-primary">Financial Matrix & Revenue Inflow</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="kpi in [
                                { label: 'Overall Revenue (Projected)', val: formatCurrency(stats?.total_expected), accent: 'text-amber-500', icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', sub: 'Total Contract Value' },
                                { label: 'Incoming Payments (Received)', val: formatCurrency(stats?.total_revenue), accent: 'text-emerald-500', icon: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', sub: `${stats?.collection_rate?.toFixed(1)}% Collection Rate` },
                                { label: 'Pending Arrears (Receivables)', val: formatCurrency(stats?.total_pending), accent: 'text-orange-500', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', sub: 'Outstanding Invoices' },
                                { label: 'Net Company Profit', val: formatCurrency(stats?.total_profit), accent: 'text-blue-500', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', sub: `${stats?.profit_margin?.toFixed(1)}% Avg. Margin` },
                                { label: 'Overall Fiscal Loss', val: formatCurrency(stats?.total_loss || 0), accent: 'text-red-500', icon: 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6', sub: 'Deficit / Overheads' },
                                { label: 'Operational Expenses', val: formatCurrency(stats?.total_expenses), accent: 'text-slate-400', icon: 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', sub: 'Total Asset Consumption' },
                            ]" :key="kpi.label" class="bg-[#111827] border border-white/5 p-8 rounded-2xl hover:border-white/10 transition-all duration-500 group shadow-2xl relative">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="px-2.5 py-1 bg-white/5 rounded-lg text-[8px] font-black uppercase tracking-widest text-slate-500 group-hover:text-white transition-colors">KPI ANALYTICS</div>
                                    <svg class="w-5 h-5 text-slate-600 group-hover:text-white transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="kpi.icon" /></svg>
                                </div>
                                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 truncate">{{ kpi.label }}</p>
                                <p class="text-3xl font-black italic tracking-tighter" :class="kpi.accent">{{ kpi.val ?? '—' }}</p>
                                <p class="text-[8px] text-slate-600 mt-4 uppercase tracking-widest font-bold group-hover:text-slate-500 transition-colors flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="kpi.accent.replace('text', 'bg')"></span>
                                    {{ kpi.sub }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                            <h2 class="text-sm font-black uppercase tracking-[0.25em] text-white">Revenue Operational Intelligence</h2>
                        </div>
                        <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-xl relative overflow-hidden group hover:border-blue-600/30 transition-all">
                             <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6 italic">Inflow Protocol Stream (Expected vs Received)</h3>
                             <div class="h-80">
                                <Bar v-if="chart_data?.revenue_stream" :data="{
                                    labels: chart_data.revenue_stream.labels,
                                    datasets: [
                                        { label: 'Received Inflow', data: chart_data.revenue_stream.received, backgroundColor: '#10b981', borderRadius: 4 },
                                        { label: 'Expected Revenue', data: chart_data.revenue_stream.expected, backgroundColor: '#27272a', borderRadius: 4 }
                                    ]
                                }" :options="baseOptions" />
                             </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                         <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-xl relative overflow-hidden group hover:border-orange-600/30 transition-all">
                              <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6 italic">Strategic Profit vs Loss Trajectory</h3>
                              <div class="h-[350px]">
                                 <Line v-if="chart_data?.profit_loss_trend" :data="{
                                     labels: chart_data.profit_loss_trend.labels,
                                     datasets: [
                                         { 
                                             label: 'Net Profit', 
                                             data: chart_data.profit_loss_trend.profit, 
                                             borderColor: '#10b981', 
                                             backgroundColor: 'rgba(16, 185, 129, 0.1)', 
                                             fill: true,
                                             tension: 0.4
                                         },
                                         { 
                                             label: 'Fiscal Loss', 
                                             data: chart_data.profit_loss_trend.loss, 
                                             borderColor: '#ef4444', 
                                             backgroundColor: 'rgba(239, 68, 68, 0.1)', 
                                             fill: true,
                                             tension: 0.4
                                         }
                                     ]
                                 }" :options="baseOptions" />
                              </div>
                         </div>
                         <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-xl relative overflow-hidden group hover:border-emerald-600/30 transition-all">
                              <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6 italic">Fiscal Collection Matrix (Rec. vs Pending)</h3>
                              <div class="h-[350px]">
                                 <Bar v-if="chart_data?.collection_matrix" :data="{
                                     labels: chart_data.collection_matrix.labels,
                                     datasets: [
                                         { label: 'Inflow (Received)', data: chart_data.collection_matrix.received, backgroundColor: '#10b981', borderRadius: 4 },
                                         { label: 'Delta (Pending)', data: chart_data.collection_matrix.pending, backgroundColor: '#ef4444', borderRadius: 4 }
                                     ]
                                 }" :options="baseOptions" />
                              </div>
                         </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-xl lg:col-span-2">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6 italic">Operational Asset Consumption</h3>
                            <div class="h-64">
                                <Bar v-if="chart_data?.financial_overview" :data="{
                                    labels: chart_data.financial_overview.labels,
                                    datasets: [
                                        { label: 'Revenue', data: chart_data.financial_overview.revenue, backgroundColor: '#3b82f6', borderRadius: 4 },
                                        { label: 'Expenses', data: chart_data.financial_overview.expenses, backgroundColor: '#ef4444', borderRadius: 4 }
                                    ]
                                }" :options="baseOptions" />
                            </div>
                        </div>
                        <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-xl">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6 italic">Product Strategy Matrix</h3>
                            <div class="h-64 flex items-center justify-center">
                                <Doughnut v-if="chart_data?.product_stats" :data="{
                                    labels: chart_data.product_stats.labels,
                                    datasets: [{
                                        data: chart_data.product_stats.values,
                                        backgroundColor: ['#f97316', '#10b981', '#3b82f6', '#ef4444'],
                                        borderWidth: 0,
                                        hoverOffset: 12
                                    }]
                                }" :options="donutOptions" />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-xl">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6">Audit Stream</h3>
                            <div class="space-y-3 max-h-[400px] overflow-y-auto custom-scrollbar pr-2">
                                <div v-for="log in recent_activity" :key="log.id" class="p-4 bg-black/40 border border-zinc-800 rounded-lg group hover:border-primary/30 transition-all">
                                    <div class="flex justify-between items-start">
                                        <p class="text-[9px] font-black uppercase text-white tracking-wider">{{ log.action }}</p>
                                        <p class="text-[8px] text-zinc-600 uppercase font-black tracking-widest">{{ formatDate(log.created_at) }}</p>
                                    </div>
                                    <p class="text-[8px] text-zinc-500 mt-1 uppercase font-bold tracking-widest">{{ log.user?.name || 'System' }} · Remote Admin Access</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-xl relative overflow-hidden">
                             <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6 italic">Revenue distribution by status</h3>
                             <div class="h-64 flex items-center justify-center">
                                 <Doughnut v-if="chart_data?.revenue_by_status" :data="{
                                     labels: chart_data.revenue_by_status.labels,
                                     datasets: [{
                                         data: chart_data.revenue_by_status.values,
                                         backgroundColor: ['#f97316', '#10b981', '#3b82f6', '#ef4444'],
                                         borderWidth: 0,
                                     }]
                                 }" :options="donutOptions" />
                             </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- ═══════════════════════════════════════════════
                 FINANCE & MANAGEMENT DASHBOARD
            ════════════════════════════════════════════════ -->
            <template v-else-if="isFinance">
                <div class="space-y-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-black uppercase tracking-[0.15em] text-primary">Operational Hub</h1>
                            <p class="text-[10px] text-zinc-500 uppercase tracking-widest mt-1">{{ user_role }} — Strategic performance & fiscal oversight</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 mb-10 border-b border-slate-100 pb-1">
                        <button v-for="tab in [
                            { id: 'overview', label: 'Overview Control' },
                            { id: 'revenue',  label: 'Revenue Matrix' },
                            { id: 'profit',   label: 'Profit Ledger' },
                            { id: 'team',     label: 'Finance Team' }
                        ]" :key="tab.id" @click="financeTab = tab.id" :class="financeTab === tab.id ? 'text-primary border-b-2 border-primary pb-3' : 'text-zinc-400 pb-3'" class="text-[10px] font-black uppercase tracking-[0.3em]">
                            {{ tab.label }}
                        </button>
                    </div>

                    <!-- Overview Control -->
                    <div v-if="financeTab === 'overview'" class="space-y-10 animate-fade-in">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div v-for="kpi in [
                                { label: 'Overall Revenue (Projected)', val: formatCurrency(stats?.total_expected), color: 'border-l-primary', icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', sub: 'Total Contract Value' },
                                { label: 'Incoming Payments (Received)', val: formatCurrency(stats?.total_revenue), color: 'border-l-emerald-500', icon: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', sub: `${stats?.collection_rate?.toFixed(1)}% Collection Rate` },
                                { label: 'Pending Arrears (Receivables)', val: formatCurrency(stats?.total_pending), color: 'border-l-amber-500', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', sub: 'Outstanding Invoices' },
                                { label: 'Net Company Profit', val: formatCurrency(stats?.total_profit), color: 'border-l-primary', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', sub: `${stats?.profit_margin?.toFixed(1)}% Avg. Margin` },
                                { label: 'Overall Fiscal Loss', val: formatCurrency(stats?.total_loss), color: 'border-l-red-500', icon: 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6', sub: 'Deficit / Overheads' },
                                { label: 'Total Active Products', val: Math.round(stats?.project_count), color: 'border-l-zinc-400', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', sub: `${stats?.ongoing_count} Ongoing · ${stats?.pending_count} Pending` },
                            ]" :key="kpi.label" class="bg-white border border-slate-100 border-l-4 p-8 rounded-xl hover:border-primary transition-all duration-500 shadow-sm overflow-hidden group" :class="kpi.color">
                                <div class="flex justify-between items-start mb-4">
                                    <p class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-400 group-hover:text-primary transition-colors">{{ kpi.label }}</p>
                                    <svg class="w-5 h-5 text-zinc-400 group-hover:text-primary group-hover:scale-110 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="kpi.icon" /></svg>
                                </div>
                                <p class="text-3xl font-black text-slate-900 italic tracking-tighter group-hover:text-primary transition-colors">{{ kpi.val ?? '—' }}</p>
                                <p class="text-[8px] text-zinc-400 mt-3 uppercase tracking-widest font-bold group-hover:text-zinc-500 transition-colors">{{ kpi.sub }}</p>
                            </div>
                        </div>

                        <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-xl relative overflow-hidden group hover:border-blue-600/30 transition-all">
                             <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6 italic">Inflow Protocol Stream (Expected vs Received)</h3>
                             <div class="h-80">
                                <Bar v-if="chart_data?.revenue_stream" :data="{
                                    labels: chart_data.revenue_stream.labels,
                                    datasets: [
                                        { label: 'Received Inflow', data: chart_data.revenue_stream.received, backgroundColor: '#10b981', borderRadius: 4 },
                                        { label: 'Expected Revenue', data: chart_data.revenue_stream.expected, backgroundColor: '#27272a', borderRadius: 4 }
                                    ]
                                }" :options="baseOptions" />
                             </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                             <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-xl relative overflow-hidden group hover:border-orange-600/30 transition-all">
                                  <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6 italic">Strategic Profit vs Loss per Product</h3>
                                  <div class="h-[350px]">
                                     <Bar v-if="chart_data?.profit_trend" :data="{
                                         labels: chart_data.profit_trend.labels,
                                         datasets: [
                                             { 
                                                 label: 'Project Profit', 
                                                 data: chart_data.profit_trend.profit, 
                                                 backgroundColor: '#10b981', 
                                                 borderRadius: 4
                                             },
                                             { 
                                                 label: 'Project Costs', 
                                                 data: chart_data.profit_trend.expenses, 
                                                 backgroundColor: '#ef4444', 
                                                 borderRadius: 4
                                             }
                                         ]
                                     }" :options="baseOptions" />
                                  </div>
                             </div>
                             <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-xl relative overflow-hidden group hover:border-emerald-600/30 transition-all">
                                  <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6 italic">Fiscal Collection Matrix (Monthly)</h3>
                                  <div class="h-[350px]">
                                     <Bar v-if="chart_data?.collection_matrix" :data="{
                                         labels: chart_data.collection_matrix.labels,
                                         datasets: [
                                             { label: 'Inflow (Received)', data: chart_data.collection_matrix.received, backgroundColor: '#10b981', borderRadius: 4 },
                                             { label: 'Delta (Pending)', data: chart_data.collection_matrix.pending, backgroundColor: '#ef4444', borderRadius: 4 }
                                         ]
                                     }" :options="baseOptions" />
                                  </div>
                             </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-xl lg:col-span-2">
                                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6 italic">Operational Asset Consumption</h3>
                                <div class="h-72">
                                    <Bar v-if="chart_data?.financial_overview" :data="{
                                        labels: chart_data.financial_overview.labels,
                                        datasets: [
                                            { label: 'Revenue', data: chart_data.financial_overview.revenue, backgroundColor: '#3b82f6', borderRadius: 4 },
                                            { label: 'Expenses', data: chart_data.financial_overview.expenses, backgroundColor: '#ef4444', borderRadius: 4 }
                                        ]
                                    }" :options="baseOptions" />
                                </div>
                            </div>
                            <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-xl">
                                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-8 border-b border-zinc-800/50 pb-4">Product Strategy Matrix</h3>
                                <div class="h-64 flex items-center justify-center">
                                    <Doughnut v-if="chart_data?.product_stats" :data="{
                                        labels: chart_data.product_stats.labels,
                                        datasets: [{
                                            data: chart_data.product_stats.values,
                                            backgroundColor: ['#f97316', '#10b981', '#3b82f6', '#ef4444'],
                                            borderWidth: 0,
                                            hoverOffset: 12
                                        }]
                                    }" :options="donutOptions" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Matrix -->
                    <div v-if="financeTab === 'revenue'" class="space-y-10 animate-fade-in">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 p-8 rounded-xl">
                                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6">Revenue Inflow Stream</h3>
                                <div class="h-80">
                                    <Line v-if="chart_data?.financial_overview" :data="{
                                        labels: chart_data.financial_overview.labels,
                                        datasets: [{
                                            label: 'Inflow',
                                            data: chart_data.financial_overview.revenue,
                                            borderColor: '#10b981',
                                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                            fill: true,
                                            tension: 0.4
                                        }]
                                    }" :options="baseOptions" />
                                </div>
                            </div>
                            <div class="bg-zinc-900 border border-zinc-800 p-8 rounded-xl">
                                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6">Top Revenue Sources</h3>
                                <div class="space-y-6">
                                    <div v-for="p in projects?.slice(0, 5)" :key="p.id" class="space-y-2">
                                        <div class="flex justify-between items-center">
                                            <span class="text-[9px] font-black uppercase text-zinc-500 truncate w-32">{{ p.title }}</span>
                                            <span class="text-[10px] font-black text-emerald-500">{{ formatCurrency(p.received_payment) }}</span>
                                        </div>
                                        <div class="w-full bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-emerald-500 h-full" :style="{ width: (p.received_payment / stats.total_revenue * 100) + '%' }"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Revenue Inflow Tracking Table -->
                        <div class="bg-zinc-900/50 border border-zinc-800 rounded-xl overflow-hidden">
                            <div class="p-8 border-b border-zinc-800 flex justify-between items-center">
                                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white">Revenue Sources Protocol</h3>
                                <Link :href="route('admin.revenue.index')" class="text-[8px] font-black uppercase text-primary hover:text-primary/80 tracking-widest">View Full Ledger</Link>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="bg-black/20 text-[8px] font-black uppercase tracking-widest text-zinc-600 border-b border-zinc-800">
                                            <th class="px-8 py-4">Project Node</th>
                                            <th class="px-8 py-4 text-right">Inflow (Rec.)</th>
                                            <th class="px-8 py-4 text-right">Expected</th>
                                            <th class="px-8 py-4 text-right">Delta (Pending)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-900/50">
                                        <tr v-for="p in projects?.slice(0, 8)" :key="p.id" class="hover:bg-black/20 transition-colors">
                                            <td class="px-8 py-4">
                                                <p class="text-[10px] font-black text-white uppercase">{{ p.title }}</p>
                                            </td>
                                            <td class="px-8 py-4 text-right">
                                                <p class="text-[10px] font-black text-emerald-500">{{ formatCurrency(p.received_payment) }}</p>
                                            </td>
                                            <td class="px-8 py-4 text-right text-[9px] text-zinc-500 font-mono">
                                                {{ formatCurrency(p.expected_revenue) }}
                                            </td>
                                            <td class="px-8 py-4 text-right text-[9px] font-black" :class="p.pending_payment > 0 ? 'text-red-500' : 'text-zinc-700'">
                                                {{ formatCurrency(p.pending_payment) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Profit Ledger -->
                    <div v-if="financeTab === 'profit'" class="space-y-10 animate-fade-in">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="bg-zinc-900 border border-zinc-800 p-8 rounded-xl flex flex-col justify-between">
                                <div>
                                    <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-8 border-b border-zinc-800 pb-4">Efficiency Metrics</h3>
                                    <div class="space-y-10">
                                        <div>
                                            <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Avg. Project Profit</p>
                                            <p class="text-3xl font-black text-white italic group-hover:text-orange-500 transition-colors">{{ formatCurrency(stats?.average_profit) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black text-zinc-600 uppercase tracking-widest mb-1">Gross Profit Margin</p>
                                            <p class="text-3xl font-black text-orange-500">{{ stats?.profit_margin?.toFixed(1) }}%</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-10 p-4 bg-orange-600/10 border border-orange-600/20 rounded-lg">
                                    <p class="text-[8px] font-black uppercase text-primary tracking-widest">Performance Insight: Net profit is trending {{ stats?.profit_margin > 20 ? 'optimal' : 'stable' }}.</p>
                                </div>
                            </div>
                            <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 p-8 rounded-xl">
                                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6">Profit vs Loss Trajectory</h3>
                                <div class="h-[400px]">
                                    <Line v-if="chart_data?.profit_loss_trend" :data="{
                                        labels: chart_data.profit_loss_trend.labels,
                                        datasets: [
                                            { 
                                                label: 'Net Profit', 
                                                data: chart_data.profit_loss_trend.profit, 
                                                borderColor: '#10b981', 
                                                backgroundColor: 'rgba(16, 185, 129, 0.1)', 
                                                fill: true,
                                                tension: 0.4
                                            },
                                            { 
                                                label: 'Fiscal Loss', 
                                                data: chart_data.profit_loss_trend.loss, 
                                                borderColor: '#ef4444', 
                                                backgroundColor: 'rgba(239, 68, 68, 0.1)', 
                                                fill: true,
                                                tension: 0.4
                                            }
                                        ]
                                    }" :options="baseOptions" />
                                </div>
                            </div>
                        </div>

                        <div class="bg-zinc-900/50 border border-zinc-800 p-8 rounded-xl">
                             <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-6">Profit vs Consumption Distribution</h3>
                             <div class="h-80">
                                 <Bar v-if="chart_data?.profit_trend" :data="{
                                     labels: chart_data.profit_trend.labels,
                                     datasets: [
                                         { label: 'Net Profit', data: chart_data.profit_trend.profit, backgroundColor: '#f97316', borderRadius: 6 },
                                         { label: 'Asset Consumption', data: chart_data.profit_trend.expenses, backgroundColor: '#27272a', borderRadius: 6 }
                                     ]
                                 }" :options="baseOptions" />
                             </div>
                        </div>
                    </div>

                    <!-- Finance Team Management -->
                    <div v-if="financeTab === 'team'" class="space-y-10 animate-fade-in">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white italic">Active Finance Operatives</h3>
                            <Link :href="route('admin.users.create')" class="px-4 py-2 bg-zinc-900 border border-zinc-800 text-[8px] font-black uppercase text-zinc-400 hover:text-white hover:border-orange-600 transition-all rounded">
                                Deploy New Finance Staff
                            </Link>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div v-for="member in team_members" :key="member.id" class="bg-zinc-900 border border-zinc-800 p-8 rounded-xl flex items-center gap-5 group hover:border-primary transition-all">
                                <div class="w-16 h-16 bg-black border border-zinc-800 flex items-center justify-center font-black text-2xl text-white rounded-lg group-hover:bg-primary transition-all">{{ member.name.charAt(0) }}</div>
                                <div>
                                    <h3 class="text-sm font-black text-white uppercase">{{ member.name }}</h3>
                                    <p class="text-[9px] font-bold text-emerald-500 uppercase mt-1">{{ member.email }}</p>
                                    <div class="flex gap-2 mt-3">
                                        <span class="px-2 py-0.5 bg-zinc-950 border border-zinc-800 text-[7px] font-black uppercase text-zinc-500">{{ member.roles?.[0]?.name || 'Finance Dept' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="!team_members?.length" class="py-20 text-center border-2 border-dashed border-slate-100 rounded-3xl text-slate-300 text-[10px] font-black uppercase tracking-widest italic">
                            No finance department staff identified in the operational matrix.
                        </div>
                    </div>
                </div>
            </template>

            <!-- ═══════════════════════════════════════════════
                 FALLBACK DASHBOARD
            ════════════════════════════════════════════════ -->
            <template v-else>
                <div class="flex flex-col items-center justify-center py-48 gap-6 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center border border-slate-100">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5z" /></svg>
                    </div>
                    <div class="space-y-2">
                        <p class="text-[12px] font-black uppercase tracking-[0.3em] text-slate-900">Operational Access Restricted</p>
                        <p class="text-[9px] text-slate-400 uppercase tracking-widest font-black">Role: {{ user_role || 'Unknown Entity' }}</p>
                    </div>
                    <p class="text-[9px] text-slate-400 uppercase tracking-[0.2em] max-w-xs leading-relaxed">Please authenticate with security clearance or contact the systems administrator for dashboard deployment.</p>
                </div>
            </template>
        </div>
    </AdminLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 2px; }
.custom-scrollbar::-webkit-scrollbar-track { background-color: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #27272a; border-radius: 999px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #f97316; }
</style>
