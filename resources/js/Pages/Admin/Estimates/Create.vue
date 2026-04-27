<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, computed, watch, onMounted } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';

const props = defineProps({
    portfolios: Array,
    defaultReference: String,
    estimate: Object,
    isEditing: Boolean
});

const form = useForm({
    portfolio_id: props.estimate?.portfolio_id || '',
    project_title: props.estimate?.project_title || '',
    reference_number: props.estimate?.reference_number || props.defaultReference,
    estimate_date: props.estimate?.estimate_date || new Date().toISOString().split('T')[0],
    materials: props.estimate?.materials || [],
    labor: props.estimate?.labor || [],
    equipment: props.estimate?.equipment || [],
    other_costs_details: props.estimate?.other_costs_details || {
        miscellaneous: 0,
        transport: 0,
        permit_fees: 0
    },
    tax_percent: props.estimate?.tax_percent || 0,
    contingency_percent: props.estimate?.contingency_percent || 0,
    profit_percent: props.estimate?.profit_percent || 0,
    material_cost: props.estimate?.material_cost || 0,
    labor_cost: props.estimate?.labor_cost || 0,
    equipment_cost: props.estimate?.equipment_cost || 0,
    other_cost: props.estimate?.other_cost || 0,
    total_amount: props.estimate?.total_amount || 0,
    status: props.estimate?.status || 'draft'
});

const isCustomProject = ref(!!props.estimate?.project_title && !props.estimate?.portfolio_id);

// --- Project Details ---
const selectedProject = computed(() => {
    if (isCustomProject.value) return null;
    return props.portfolios.find(p => p.id === form.portfolio_id);
});

watch(isCustomProject, (val) => {
    if (val) form.portfolio_id = '';
    else form.project_title = '';
});

// --- Materials Logic ---
// ... (rest of logic)

const addMaterial = () => {
    form.materials.push({
        item_name: '',
        description: '',
        quantity: 1,
        unit: 'pcs',
        unit_cost: 0,
        total: 0
    });
};

const removeMaterial = (index) => {
    form.materials.splice(index, 1);
};

const materialTotal = computed(() => {
    return form.materials.reduce((sum, item) => {
        item.total = item.quantity * item.unit_cost;
        return sum + item.total;
    }, 0);
});

// --- Labor Logic ---
const addLabor = () => {
    form.labor.push({
        worker_type: '',
        count: 1,
        days: 1,
        daily_rate: 0,
        total: 0
    });
};

const removeLabor = (index) => {
    form.labor.splice(index, 1);
};

const laborTotal = computed(() => {
    return form.labor.reduce((sum, item) => {
        item.total = item.count * item.days * item.daily_rate;
        return sum + item.total;
    }, 0);
});

// --- Equipment Logic ---
const addEquipment = () => {
    form.equipment.push({
        equipment_name: '',
        hours: 1,
        hourly_rate: 0,
        total: 0
    });
};

const removeEquipment = (index) => {
    form.equipment.splice(index, 1);
};

const equipmentTotal = computed(() => {
    return form.equipment.reduce((sum, item) => {
        item.total = item.hours * item.hourly_rate;
        return sum + item.total;
    }, 0);
});

// --- Other Costs Logic ---
const otherCostsTotal = computed(() => {
    return (Number(form.other_costs_details.miscellaneous) || 0) + 
           (Number(form.other_costs_details.transport) || 0) + 
           (Number(form.other_costs_details.permit_fees) || 0);
});

// --- Summary Calculations ---
const subtotal = computed(() => {
    return materialTotal.value + laborTotal.value + equipmentTotal.value + otherCostsTotal.value;
});

const taxAmount = computed(() => subtotal.value * (form.tax_percent / 100));
const contingencyAmount = computed(() => subtotal.value * (form.contingency_percent / 100));
const profitAmount = computed(() => subtotal.value * (form.profit_percent / 100));

const grandTotal = computed(() => {
    return subtotal.value + taxAmount.value + contingencyAmount.value + profitAmount.value;
});

// Sync numeric fields for server-side
watch(materialTotal, (val) => form.material_cost = val);
watch(laborTotal, (val) => form.labor_cost = val);
watch(equipmentTotal, (val) => form.equipment_cost = val);
watch(otherCostsTotal, (val) => form.other_cost = val);
watch(grandTotal, (val) => form.total_amount = val);

const submit = () => {
    if (props.isEditing) {
        form.put(route('admin.estimates.update', props.estimate.id));
    } else {
        form.post(route('admin.estimates.store'));
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(value);
};
</script>

<template>
    <AdminLayout>
        <Head :title="isEditing ? 'Edit Estimate' : 'New Estimate'" />

        <div class="p-6 max-w-[1600px] mx-auto min-h-screen bg-zinc-50/50 dark:bg-transparent text-white">
            <!-- Header Section -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <Link :href="route('admin.estimates.index')" class="p-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg hover:text-orange-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                        </Link>
                        <h1 class="text-3xl font-black tracking-tighter uppercase text-zinc-900 dark:text-white">
                            {{ isEditing ? 'Modification' : 'Generation' }} <span class="text-orange-600">Protocol.</span>
                        </h1>
                    </div>
                    <p class="text-zinc-500 text-[10px] font-black uppercase tracking-[0.4em] ml-12 italic">Ref: {{ form.reference_number }} // Cost Indexing Engine</p>
                </div>
                <div class="flex flex-wrap gap-4 w-full lg:w-auto">
                    <button class="flex-1 lg:flex-none px-8 py-4 border-2 border-zinc-200 dark:border-zinc-800 text-zinc-500 font-black uppercase text-[10px] tracking-[0.2em] hover:border-orange-600 hover:text-orange-600 transition-all rounded-xl">
                        Export Data
                    </button>
                    <button @click="submit" :disabled="form.processing" class="flex-1 lg:flex-none px-10 py-4 bg-zinc-900 dark:bg-white text-white dark:text-black font-black uppercase text-[10px] tracking-[0.2em] shadow-2xl hover:bg-orange-600 dark:hover:bg-orange-600 dark:hover:text-white transition-all transform hover:scale-[1.02] rounded-xl flex items-center justify-center gap-3">
                        <svg v-if="form.processing" class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        Commit Estimate
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-10">
                <div class="xl:col-span-3 space-y-10">
                    <!-- Project Initialization -->
                    <div class="bg-zinc-900 p-10 rounded-3xl shadow-sm border border-zinc-800 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-8 opacity-[0.03] group-hover:opacity-[0.05] transition-opacity pointer-events-none text-white">
                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        </div>
                        
                        <div class="flex gap-8 mb-8 border-b border-zinc-800 pb-4">
                            <button @click="isCustomProject = false" :class="!isCustomProject ? 'text-orange-600 border-b-2 border-orange-600' : 'text-zinc-500'" class="pb-2 text-[10px] font-black uppercase tracking-widest transition-all">Existing Portfolio</button>
                            <button @click="isCustomProject = true" :class="isCustomProject ? 'text-orange-600 border-b-2 border-orange-600' : 'text-zinc-500'" class="pb-2 text-[10px] font-black uppercase tracking-widest transition-all">New Concept</button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-end">
                            <div class="md:col-span-5" v-if="!isCustomProject">
                                <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 mb-4">Project Vector</label>
                                <select v-model="form.portfolio_id" class="w-full bg-white text-black border-none rounded-2xl px-6 py-4 text-base font-black focus:ring-4 focus:ring-orange-600/20 transition-all uppercase tracking-tight">
                                    <option value="" class="text-zinc-500">Target Null</option>
                                    <option v-for="p in portfolios" :key="p.id" :value="p.id">{{ p.title }}</option>
                                </select>
                            </div>
                            <div class="md:col-span-5" v-else>
                                <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 mb-4">Project Title (New Concept)</label>
                                <input v-model="form.project_title" type="text" placeholder="ENTER NEW PROJECT NAME" class="w-full bg-white text-black border-none rounded-2xl px-6 py-4 text-base font-black focus:ring-4 focus:ring-orange-600/20 transition-all uppercase tracking-tight">
                            </div>

                            <div class="md:col-span-3">
                                <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500 mb-3">Time Reference</label>
                                <input v-model="form.estimate_date" type="date" class="w-full bg-white text-black border-none rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-orange-600/20 transition-all">
                            </div>
                            <div v-if="selectedProject" class="md:col-span-4 border-l border-zinc-800 pl-10">
                                <span class="text-[9px] font-black uppercase tracking-widest text-orange-600 block mb-2">Live Status</span>
                                <h3 class="text-sm font-black uppercase italic text-white">{{ selectedProject.project_type }}</h3>
                                <p class="text-[10px] font-bold text-zinc-400 mt-1 uppercase">LOC: {{ selectedProject.location || 'GLOBAL' }}</p>
                            </div>
                            <div v-else-if="isCustomProject" class="md:col-span-4 border-l border-zinc-800 pl-10">
                                <span class="text-[9px] font-black uppercase tracking-widest text-emerald-500 block mb-2">Simulation Mode</span>
                                <h3 class="text-sm font-black uppercase italic text-white">Custom Projection</h3>
                                <p class="text-[10px] font-bold text-zinc-400 mt-1 uppercase">Non-Indexed Context</p>
                            </div>
                        </div>
                    </div>

                    <!-- Segment: Materials -->
                    <div class="bg-zinc-900 rounded-3xl shadow-sm border border-zinc-800 overflow-hidden">
                        <div class="p-10 border-b border-zinc-800 flex justify-between items-center bg-zinc-800/10">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-orange-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-orange-600/20">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-black uppercase tracking-tighter leading-none text-white">Structural <span class="text-orange-600">Inventory.</span></h2>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-zinc-500 mt-2 italic">Material Procurement Node</p>
                                </div>
                            </div>
                            <button @click="addMaterial" class="px-6 py-3 bg-orange-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-white hover:text-black transition-all flex items-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                Index Item
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-zinc-950">
                                    <tr>
                                        <th class="px-10 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600">Identification</th>
                                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-28 text-center">Quant.</th>
                                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-28 text-center">Unit</th>
                                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-40 text-right">Unit Val.</th>
                                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-40 text-right">Total</th>
                                        <th class="px-10 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-20 text-center">Opt</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-800">
                                    <tr v-for="(item, index) in form.materials" :key="index" class="group transition-colors odd:bg-zinc-900 even:bg-zinc-800/20">
                                        <td class="px-10 py-6">
                                            <input v-model="item.item_name" type="text" class="w-full bg-transparent border-none focus:ring-0 text-sm font-black uppercase tracking-tight p-0 text-white placeholder:text-zinc-700" placeholder="ITEM IDENTIFIER">
                                            <input v-model="item.description" type="text" class="w-full bg-transparent border-none focus:ring-0 text-[10px] font-bold text-zinc-500 uppercase tracking-widest p-0 mt-1 placeholder:text-zinc-800" placeholder="Technical Specs">
                                        </td>
                                        <td class="px-6 py-6"><input v-model="item.quantity" type="number" min="1" class="w-full bg-white text-black border-none rounded-lg p-3 text-sm font-black text-center focus:ring-2 focus:ring-orange-600"></td>
                                        <td class="px-6 py-6"><input v-model="item.unit" type="text" class="w-full bg-transparent border-none focus:ring-0 text-xs font-black text-center uppercase tracking-widest text-zinc-400" placeholder="PCS"></td>
                                        <td class="px-6 py-6"><input v-model="item.unit_cost" type="number" step="0.01" min="0" class="w-full bg-white text-black border-none rounded-lg p-3 text-sm font-black text-right focus:ring-2 focus:ring-orange-600"></td>
                                        <td class="px-6 py-6 text-base font-black text-right italic text-orange-600">{{ formatCurrency(item.total) }}</td>
                                        <td class="px-10 py-6 text-center">
                                            <button @click="removeMaterial(index)" class="w-10 h-10 flex items-center justify-center text-zinc-300 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10 rounded-xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Segment: Labor & Professional -->
                    <div class="bg-zinc-900 rounded-3xl shadow-sm border border-zinc-800 overflow-hidden">
                        <div class="p-10 border-b border-zinc-800 flex justify-between items-center bg-zinc-800/10">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/20">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-black uppercase tracking-tighter leading-none text-white">Elite <span class="text-orange-600">Personnel.</span></h2>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-zinc-500 mt-2 italic">Workforce Deployment Matrix</p>
                                </div>
                            </div>
                            <button @click="addLabor" class="px-6 py-3 bg-blue-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-white hover:text-black transition-all flex items-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                Deploy Talent
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-zinc-950">
                                    <tr>
                                        <th class="px-10 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600">Designation</th>
                                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-32 text-center">Cohort Size</th>
                                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-32 text-center">Duration (D)</th>
                                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-40 text-right">Daily Rate</th>
                                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-40 text-right">Total</th>
                                        <th class="px-10 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-20 text-center">Opt</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-800">
                                    <tr v-for="(item, index) in form.labor" :key="index" class="group transition-colors odd:bg-zinc-900 even:bg-zinc-800/20">
                                        <td class="px-10 py-6"><input v-model="item.worker_type" type="text" class="w-full bg-transparent border-none focus:ring-0 text-sm font-black uppercase tracking-tight p-0 text-white placeholder:text-zinc-700" placeholder="TECHNICAL SPECIALIST"></td>
                                        <td class="px-6 py-6"><input v-model="item.count" type="number" min="1" class="w-full bg-white text-black border-none rounded-lg p-3 text-sm font-black text-center focus:ring-2 focus:ring-orange-600"></td>
                                        <td class="px-6 py-6"><input v-model="item.days" type="number" min="1" class="w-full bg-white text-black border-none rounded-lg p-3 text-sm font-black text-center focus:ring-2 focus:ring-orange-600"></td>
                                        <td class="px-6 py-6"><input v-model="item.daily_rate" type="number" step="0.01" min="0" class="w-full bg-white text-black border-none rounded-lg p-3 text-sm font-black text-right focus:ring-2 focus:ring-orange-600"></td>
                                        <td class="px-6 py-6 text-base font-black text-right italic text-orange-600">{{ formatCurrency(item.total) }}</td>
                                        <td class="px-10 py-6 text-center">
                                            <button @click="removeLabor(index)" class="w-10 h-10 flex items-center justify-center text-zinc-300 hover:text-red-600 rounded-xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Segment: Heavy Equipment -->
                    <div class="bg-zinc-900 rounded-3xl shadow-sm border border-zinc-800 overflow-hidden">
                        <div class="p-10 border-b border-zinc-800 flex justify-between items-center bg-zinc-800/10">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-green-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-green-600/20">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-black uppercase tracking-tighter leading-none text-white">Heavy <span class="text-orange-600">Assets.</span></h2>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-zinc-500 mt-2 italic">Logistics & Machinery Allocation</p>
                                </div>
                            </div>
                            <button @click="addEquipment" class="px-6 py-3 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-white hover:text-black transition-all flex items-center gap-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                Acquire Asset
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-zinc-950">
                                    <tr>
                                        <th class="px-10 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600">Asset Ident.</th>
                                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-40 text-center">Operational Hrs</th>
                                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-40 text-right">Hourly Rate</th>
                                        <th class="px-6 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-40 text-right">Segment Total</th>
                                        <th class="px-10 py-5 text-[9px] font-black uppercase tracking-widest text-orange-600 w-20 text-center">Opt</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-800">
                                    <tr v-for="(item, index) in form.equipment" :key="index" class="group transition-colors odd:bg-zinc-900 even:bg-zinc-800/20">
                                        <td class="px-10 py-6"><input v-model="item.equipment_name" type="text" class="w-full bg-transparent border-none focus:ring-0 text-sm font-black uppercase tracking-tight p-0 text-white placeholder:text-zinc-700" placeholder="INDUSTRIAL MACHINERY"></td>
                                        <td class="px-6 py-6"><input v-model="item.hours" type="number" min="1" class="w-full bg-white text-black border-none rounded-lg p-3 text-sm font-black text-center focus:ring-2 focus:ring-orange-600"></td>
                                        <td class="px-6 py-6"><input v-model="item.hourly_rate" type="number" step="0.01" min="0" class="w-full bg-white text-black border-none rounded-lg p-3 text-sm font-black text-right focus:ring-2 focus:ring-orange-600"></td>
                                        <td class="px-6 py-6 text-base font-black text-right italic text-orange-600">{{ formatCurrency(item.total) }}</td>
                                        <td class="px-10 py-6 text-center">
                                            <button @click="removeEquipment(index)" class="w-10 h-10 flex items-center justify-center text-zinc-300 hover:text-red-600 rounded-xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Auxiliary Parameters -->
                    <div class="bg-zinc-900 rounded-3xl p-10 shadow-sm border border-zinc-800">
                        <h2 class="text-2xl font-black uppercase tracking-tighter mb-10 text-white">Auxiliary <span class="text-orange-600">Expenditures.</span></h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                            <div class="space-y-4">
                                <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">Miscellaneous Vector</label>
                                <div class="relative group">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-zinc-600 text-xs font-black transition-colors group-focus-within:text-orange-600">$</span>
                                    <input v-model="form.other_costs_details.miscellaneous" type="number" min="0" class="w-full bg-white text-black border-none rounded-2xl pl-12 pr-6 py-5 text-sm font-black focus:ring-4 focus:ring-orange-600/20 transition-all">
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">Deployment Logistics</label>
                                <div class="relative group">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-zinc-600 text-xs font-black transition-colors group-focus-within:text-orange-600">$</span>
                                    <input v-model="form.other_costs_details.transport" type="number" min="0" class="w-full bg-white text-black border-none rounded-2xl pl-12 pr-6 py-5 text-sm font-black focus:ring-4 focus:ring-orange-600/20 transition-all">
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">Compliance & Permits</label>
                                <div class="relative group">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-zinc-600 text-xs font-black transition-colors group-focus-within:text-orange-600">$</span>
                                    <input v-model="form.other_costs_details.permit_fees" type="number" min="0" class="w-full bg-white text-black border-none rounded-2xl pl-12 pr-6 py-5 text-sm font-black focus:ring-4 focus:ring-orange-600/20 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Strategic Summary Console -->
                <div class="xl:col-span-1">
                    <div class="sticky top-28 space-y-8">
                        <div class="bg-zinc-900 text-white rounded-3xl p-8 shadow-2xl relative overflow-hidden group">
                            <!-- Background Texture -->
                            <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                            
                            <div class="relative z-10 space-y-8">
                                <div class="border-b border-zinc-800 pb-8">
                                    <span class="text-[9px] font-black uppercase tracking-[0.4em] text-orange-600 block mb-2">Internal Report</span>
                                    <h2 class="text-3xl font-black uppercase tracking-tighter italic">Strategic <span class="text-zinc-600">Console.</span></h2>
                                </div>

                                <div class="space-y-5">
                                    <div class="flex justify-between items-center group/row">
                                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500 group-hover/row:text-zinc-300 transition-colors">Material Assets</span>
                                        <span class="text-sm font-black tabular-nums">{{ formatCurrency(materialTotal) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center group/row">
                                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500 group-hover/row:text-zinc-300 transition-colors">Workforce Val.</span>
                                        <span class="text-sm font-black tabular-nums">{{ formatCurrency(laborTotal) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center group/row">
                                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500 group-hover/row:text-zinc-300 transition-colors">Equipment Ops</span>
                                        <span class="text-sm font-black tabular-nums">{{ formatCurrency(equipmentTotal) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center group/row">
                                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500 group-hover/row:text-zinc-300 transition-colors">Auxiliary Load</span>
                                        <span class="text-sm font-black tabular-nums">{{ formatCurrency(otherCostsTotal) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-8 border-t border-zinc-800">
                                        <span class="text-xs font-black uppercase tracking-[0.3em] text-white">Aggregated Base</span>
                                        <span class="text-2xl font-black tracking-tighter italic text-orange-600">{{ formatCurrency(subtotal) }}</span>
                                    </div>
                                </div>

                                <div class="space-y-8 pt-8 border-t border-zinc-800">
                                    <div class="space-y-4">
                                        <div class="flex justify-between">
                                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">Tax Index (%)</label>
                                            <span class="text-xs font-black text-orange-600 tabular-nums">+{{ formatCurrency(taxAmount) }}</span>
                                        </div>
                                        <input v-model="form.tax_percent" type="range" min="0" max="30" class="w-full h-1 bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-orange-600">
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex justify-between">
                                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">Contingency Buffer (%)</label>
                                            <span class="text-xs font-black text-orange-600 tabular-nums">+{{ formatCurrency(contingencyAmount) }}</span>
                                        </div>
                                        <input v-model="form.contingency_percent" type="range" min="0" max="25" class="w-full h-1 bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-orange-600">
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex justify-between">
                                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">Profit Extraction (%)</label>
                                            <span class="text-xs font-black text-orange-600 tabular-nums">+{{ formatCurrency(profitAmount) }}</span>
                                        </div>
                                        <input v-model="form.profit_percent" type="range" min="0" max="50" class="w-full h-1 bg-zinc-800 rounded-lg appearance-none cursor-pointer accent-orange-600">
                                    </div>
                                </div>

                                <div class="bg-orange-600 -mx-8 -mb-8 p-10 mt-10 group/terminal overflow-hidden rounded-b-3xl">
                                    <p class="text-[9px] font-black uppercase tracking-[0.5em] text-white/50 mb-3 group-hover/terminal:text-white/80 transition-colors">Grand Projection Output</p>
                                    <p class="text-3xl xl:text-4xl font-black tracking-tighter leading-none italic transform group-hover/terminal:scale-105 transition-transform origin-left break-all">{{ formatCurrency(grandTotal) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status Toggle Console -->
                        <div class="bg-zinc-900 p-8 rounded-3xl border border-zinc-800 shadow-2xl">
                            <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500 mb-6 text-center italic">Protocol Status</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button v-for="s in ['draft', 'approved', 'sent', 'rejected']" :key="s" 
                                    @click="form.status = s"
                                    :class="['px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest border transition-all', 
                                    form.status === s ? 'bg-orange-600 text-white border-orange-600 shadow-lg shadow-orange-600/20' : 'bg-transparent border-zinc-800 text-zinc-500 hover:border-zinc-700 hover:text-white']">
                                    {{ s }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type="number"] {
  -moz-appearance: textfield;
  appearance: textfield;
}

/* Custom Range Thumb Styling for Orange Theme */
input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  height: 16px;
  width: 16px;
  border-radius: 50%;
  background: #ff6b00;
  cursor: pointer;
  box-shadow: 0 0 10px rgba(255, 107, 0, 0.4);
  margin-top: -6px;
}

input[type="range"]::-webkit-slider-runnable-track {
  width: 100%;
  height: 4px;
  cursor: pointer;
  background: #27272a;
  border-radius: 2px;
}
</style>
