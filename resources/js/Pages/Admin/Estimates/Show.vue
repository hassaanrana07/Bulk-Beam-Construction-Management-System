<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    estimate: Object
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(value);
};

const getStatusColor = (status) => {
    switch (status) {
        case 'draft': return 'border-zinc-200 text-zinc-500 bg-zinc-50';
        case 'approved': return 'border-green-200 text-green-600 bg-green-50';
        case 'sent': return 'border-blue-200 text-blue-600 bg-blue-50';
        case 'rejected': return 'border-red-200 text-red-600 bg-red-50';
        default: return 'border-zinc-200 text-zinc-500';
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="'Estimate #' + estimate.reference_number" />

        <div class="p-6 max-w-[1200px] mx-auto min-h-screen">
            <!-- Strategic Command Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-16 gap-8">
                <div class="space-y-2">
                    <span :class="['px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-2', getStatusColor(estimate.status)]">
                        Protocol: {{ estimate.status }}
                    </span>
                    <h1 class="text-5xl font-black tracking-tighter uppercase text-zinc-900 dark:text-white mt-4 italic">
                        EST <span class="text-orange-600">Archive.</span>
                    </h1>
                    <p class="text-zinc-500 text-[10px] font-black uppercase tracking-[0.4em]">Reference: {{ estimate.reference_number }} // Index Point: {{ new Date(estimate.estimate_date).toLocaleDateString() }}</p>
                </div>
                <div class="flex gap-4">
                    <Link :href="route('admin.estimates.edit', estimate.id)" class="px-8 py-4 bg-zinc-900 dark:bg-white text-white dark:text-black font-black uppercase text-[10px] tracking-widest shadow-2xl hover:bg-orange-600 transition-all rounded-xl">
                        Modify Vector
                    </Link>
                    <button @click="window.print()" class="px-8 py-4 border-2 border-zinc-200 dark:border-zinc-800 text-zinc-500 font-black uppercase text-[10px] tracking-widest hover:border-orange-600 hover:text-orange-600 transition-all rounded-xl">
                        Physical Archive (Print)
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <div class="lg:col-span-2 space-y-12">
                    <!-- Project Segment Info -->
                    <div class="bg-white dark:bg-zinc-900 p-12 rounded-[2.5rem] shadow-sm border border-zinc-100 dark:border-zinc-800 flex items-center justify-between group overflow-hidden relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-orange-600/[0.02] to-transparent pointer-events-none"></div>
                        <div class="relative z-10">
                            <span class="text-[9px] font-black uppercase tracking-[0.4em] text-orange-600 block mb-2">Subject Project</span>
                            <h2 class="text-3xl font-black uppercase tracking-tighter leading-none italic">{{ estimate.portfolio?.title || estimate.project_title || 'CUSTOM PROJECT' }}</h2>
                            <p class="text-[10px] font-bold text-zinc-400 mt-2 uppercase tracking-widest">{{ estimate.portfolio?.project_type || 'Custom Concept' }}</p>
                        </div>
                        <div class="relative z-10 text-right">
                            <span class="text-[9px] font-black uppercase tracking-[0.4em] text-zinc-400 block mb-2">Assigned Territory</span>
                            <h3 class="text-sm font-black uppercase italic">{{ estimate.portfolio?.location || 'SECTOR 0' }}</h3>
                        </div>
                    </div>

                    <!-- Detailed Line Items (Print Optimized Layout) -->
                    <div class="space-y-16">
                        <!-- Materials Table -->
                        <div v-if="estimate.materials?.length">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-2 h-8 bg-orange-600"></div>
                                <h3 class="text-sm font-black uppercase tracking-[0.4em]">Section 01: Structural Inventory</h3>
                            </div>
                            <table class="w-full text-left text-sm font-bold uppercase tracking-tight">
                                <thead class="border-b-2 border-zinc-100 dark:border-zinc-800 pb-4">
                                    <tr class="text-[9px] font-black tracking-widest text-zinc-400">
                                        <th class="py-4">Designation</th>
                                        <th class="py-4 text-center">Quant.</th>
                                        <th class="py-4 text-right">Unit Val.</th>
                                        <th class="py-4 text-right">Segment Sum</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-900">
                                    <tr v-for="item in estimate.materials" :key="item.id">
                                        <td class="py-5">{{ item.item_name }} <span class="block text-[9px] text-zinc-400 tracking-widest mt-1">{{ item.description }}</span></td>
                                        <td class="py-5 text-center">{{ item.quantity }} {{ item.unit }}</td>
                                        <td class="py-5 text-right font-black tabular-nums">{{ formatCurrency(item.unit_cost) }}</td>
                                        <td class="py-5 text-right font-black italic tabular-nums">{{ formatCurrency(item.total) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Labor Table -->
                        <div v-if="estimate.labor?.length">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-2 h-8 bg-blue-600"></div>
                                <h3 class="text-sm font-black uppercase tracking-[0.4em]">Section 02: Elite Personnel</h3>
                            </div>
                            <table class="w-full text-left text-sm font-bold uppercase tracking-tight">
                                <thead class="border-b-2 border-zinc-100 dark:border-zinc-800 pb-4">
                                    <tr class="text-[9px] font-black tracking-widest text-zinc-400">
                                        <th class="py-4">Role Designation</th>
                                        <th class="py-4 text-center">Cohort (Count x Days)</th>
                                        <th class="py-4 text-right">Daily Factor</th>
                                        <th class="py-4 text-right">Labor Index</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-900">
                                    <tr v-for="item in estimate.labor" :key="item.id">
                                        <td class="py-5">{{ item.worker_type }}</td>
                                        <td class="py-5 text-center">{{ item.count }} PAX // {{ item.days }} DAYS</td>
                                        <td class="py-5 text-right font-black tabular-nums">{{ formatCurrency(item.daily_rate) }}</td>
                                        <td class="py-5 text-right font-black italic tabular-nums">{{ formatCurrency(item.total) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Equipment Table -->
                        <div v-if="estimate.equipment?.length">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-2 h-8 bg-green-600"></div>
                                <h3 class="text-sm font-black uppercase tracking-[0.4em]">Section 03: Heavy Assets</h3>
                            </div>
                            <table class="w-full text-left text-sm font-bold uppercase tracking-tight">
                                <thead class="border-b-2 border-zinc-100 dark:border-zinc-800 pb-4">
                                    <tr class="text-[9px] font-black tracking-widest text-zinc-400">
                                        <th class="py-4">Asset Identification</th>
                                        <th class="py-4 text-center">Operational Interval</th>
                                        <th class="py-4 text-right">Hourly Rate</th>
                                        <th class="py-4 text-right">Segment Load</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-50 dark:divide-zinc-900">
                                    <tr v-for="item in estimate.equipment" :key="item.id">
                                        <td class="py-5">{{ item.equipment_name }}</td>
                                        <td class="py-5 text-center">{{ item.hours }} HOURS</td>
                                        <td class="py-5 text-right font-black tabular-nums">{{ formatCurrency(item.hourly_rate) }}</td>
                                        <td class="py-5 text-right font-black italic tabular-nums">{{ formatCurrency(item.total) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Strategic Summary Console -->
                <div class="lg:col-span-1">
                    <div class="summary-panel sticky top-24 bg-zinc-900 text-white rounded-[3rem] p-12 shadow-[0_50px_100px_rgba(0,0,0,0.5)] space-y-12">
                        <div class="border-b border-zinc-800 pb-10">
                            <span class="text-[9px] font-black uppercase tracking-[0.4em] text-zinc-500 block mb-2">Internal Index</span>
                            <h2 class="text-4xl font-black uppercase tracking-tighter italic">Total <span class="text-orange-600">Vector.</span></h2>
                        </div>

                        <div class="space-y-6 pt-2">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Inventory Sub</span>
                                <span class="text-sm font-black tabular-nums">{{ formatCurrency(estimate.material_cost) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Personnel Index</span>
                                <span class="text-sm font-black tabular-nums">{{ formatCurrency(estimate.labor_cost) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Machinery Ops</span>
                                <span class="text-sm font-black tabular-nums">{{ formatCurrency(estimate.equipment_cost) }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4">
                                <span class="text-[10px] font-black uppercase tracking-widest text-zinc-500">Auxiliary Load</span>
                                <span class="text-sm font-black tabular-nums">{{ formatCurrency(estimate.other_cost) }}</span>
                            </div>
                            
                            <div class="py-10 border-y border-zinc-800 space-y-6">
                                <div class="flex justify-between items-center text-xs opacity-50">
                                    <span class="uppercase tracking-widest">Regulatory Levy ({{ estimate.tax_percent }}%)</span>
                                    <span class="font-black tabular-nums">+{{ formatCurrency(estimate.total_amount * (estimate.tax_percent/100)) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs opacity-50">
                                    <span class="uppercase tracking-widest">Contingency Buffer ({{ estimate.contingency_percent }}%)</span>
                                    <span class="font-black tabular-nums">+{{ formatCurrency(estimate.total_amount * (estimate.contingency_percent/100)) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs opacity-50">
                                    <span class="uppercase tracking-widest">Profit Extraction ({{ estimate.profit_percent }}%)</span>
                                    <span class="font-black tabular-nums">+{{ formatCurrency(estimate.total_amount * (estimate.profit_percent/100)) }}</span>
                                </div>
                            </div>

                            <div class="pt-8">
                                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-white/40 block mb-4">Total Structural Projection</span>
                                <div class="bg-orange-600 p-10 rounded-[2rem] shadow-2xl relative group overflow-hidden">
                                    <div class="absolute inset-0 bg-white/10 translate-y-full hover:translate-y-0 transition-transform"></div>
                                    <span class="text-3xl xl:text-4xl font-black tracking-tighter leading-none italic transform transition-transform group-hover:scale-105 inline-block break-all">{{ formatCurrency(estimate.total_amount) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-10 text-center">
                            <p class="text-[8px] font-black uppercase tracking-[0.5em] text-zinc-600 italic">Brick & Beam // Strategic Procurement Intel</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
@media print {
    .p-6 {
        padding: 0;
    }
    .AdminLayout {
        background: white !important;
    }
    nav, footer, button, .Link {
        display: none !important;
    }
    .shadow-sm, .summary-panel {
        box-shadow: none !important;
    }
    .rounded-3xl, .summary-panel {
        border-radius: 0 !important;
    }
    .bg-zinc-900 {
        background: transparent !important;
        color: black !important;
    }
    .text-white {
        color: black !important;
    }
    .bg-orange-600 {
        background: transparent !important;
        border-top: 4px solid black;
        color: black !important;
    }
    .sticky {
        position: static !important;
    }
}
</style>
