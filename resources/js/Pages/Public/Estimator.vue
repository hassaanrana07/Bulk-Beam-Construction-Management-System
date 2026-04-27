<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, computed, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    portfolios: Array
});

// --- Initialization ---
const materials = ref([{ item_name: '', quantity: 1, unit_cost: 0, total: 0 }]);
const labor = ref([{ worker_type: '', count: 1, days: 1, daily_rate: 0, total: 0 }]);
const equipment = ref([{ equipment_name: '', hours: 1, hourly_rate: 0, total: 0 }]);
const other_cost = ref(0);
const tax_percent = ref(0);
const contingency_percent = ref(0);
const profit_percent = ref(0);
const portfolio_id = ref('');
const project_title = ref('');
const isCustomProject = ref(false);

// --- Project Details ---
const selectedProject = computed(() => {
    if (isCustomProject.value) return null;
    return props.portfolios.find(p => p.id === portfolio_id.value);
});

// Watch for toggle change to reset fields
watch(isCustomProject, (val) => {
    if (val) portfolio_id.value = '';
    else project_title.value = '';
});

// --- Materials Logic ---
const addMaterial = () => materials.value.push({ item_name: '', quantity: 1, unit_cost: 0, total: 0 });
const removeMaterial = (index) => materials.value.splice(index, 1);
const materialTotal = computed(() => materials.value.reduce((s, i) => { i.total = i.quantity * i.unit_cost; return s + i.total; }, 0));

// --- Labor Logic ---
const addLabor = () => labor.value.push({ worker_type: '', count: 1, days: 1, daily_rate: 0, total: 0 });
const removeLabor = (index) => labor.value.splice(index, 1);
const laborTotal = computed(() => labor.value.reduce((s, i) => { i.total = (i.count || 1) * i.days * i.daily_rate; return s + i.total; }, 0));

// --- Equipment Logic ---
const addEquipment = () => equipment.value.push({ equipment_name: '', hours: 1, hourly_rate: 0, total: 0 });
const removeEquipment = (index) => equipment.value.splice(index, 1);
const equipmentTotal = computed(() => equipment.value.reduce((s, i) => { i.total = i.hours * i.hourly_rate; return s + i.total; }, 0));

// --- Summary Calculations ---
const subtotal = computed(() => materialTotal.value + laborTotal.value + equipmentTotal.value + Number(other_cost.value));
const taxAmount = computed(() => subtotal.value * (tax_percent.value / 100));
const contingencyAmount = computed(() => subtotal.value * (contingency_percent.value / 100));
const profitAmount = computed(() => subtotal.value * (profit_percent.value / 100));
const grandTotal = computed(() => subtotal.value + taxAmount.value + contingencyAmount.value + profitAmount.value);

const formatCurrency = (v) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(v);

// --- Dynamic Data Fetching ---
const fetching = ref(false);
watch(portfolio_id, (newVal) => {
    if (!newVal || isCustomProject.value) {
        // Reset defaults if needed, or keep current state
        return;
    }
    fetching.value = true;
    axios.get(`/estimate/fetch/${newVal}`)
        .then(res => {
            const data = res.data;
            if (data.estimate) {
                // Populate tables if estimate exists
                materials.value = data.estimate.materials?.length ? data.estimate.materials : [{ item_name: '', quantity: 1, unit_cost: 0, total: 0 }];
                labor.value = data.estimate.labor?.length ? data.estimate.labor : [{ worker_type: '', days: 1, daily_rate: 0, total: 0 }];
                equipment.value = data.estimate.equipment?.length ? data.estimate.equipment : [{ equipment_name: '', hours: 1, hourly_rate: 0, total: 0 }];
                other_cost.value = data.estimate.other_cost || 0;
                tax_percent.value = data.estimate.tax_percent || 0;
                contingency_percent.value = data.estimate.contingency_percent || 0;
                profit_percent.value = data.estimate.profit_percent || 0;
            } else {
                // Keep current state or reset to minimal rows
                materials.value = [{ item_name: '', quantity: 1, unit_cost: 0, total: 0 }];
                labor.value = [{ worker_type: '', days: 1, daily_rate: 0, total: 0 }];
                equipment.value = [{ equipment_name: '', hours: 1, hourly_rate: 0, total: 0 }];
                other_cost.value = 0;
            }
        })
        .finally(() => {
            fetching.value = false;
        });
});

const saving = ref(false);
const saveEstimate = () => {
    if (!isCustomProject.value && !portfolio_id.value) {
        alert('Please select a project context first.');
        return;
    }
    if (isCustomProject.value && !project_title.value) {
        alert('Please enter a name for your project concept.');
        return;
    }

    saving.value = true;
    axios.post(route('estimate.save'), {
        portfolio_id: isCustomProject.value ? null : portfolio_id.value,
        project_title: isCustomProject.value ? project_title.value : null,
        materials: materials.value,
        labor: labor.value,
        equipment: equipment.value,
        other_cost: other_cost.value,
        tax_percent: tax_percent.value,
        contingency_percent: contingency_percent.value,
        profit_percent: profit_percent.value,
        material_cost: materialTotal.value,
        labor_cost: laborTotal.value,
        equipment_cost: equipmentTotal.value,
        total_amount: grandTotal.value,
        estimate_date: new Date().toISOString().split('T')[0],
        reference_number: 'EST-' + Math.random().toString(36).substr(2, 9).toUpperCase()
    }).then(res => {
        alert('Estimate indexed and saved to your secure profile.');
    }).catch(err => {
        if (err.response?.status === 401) {
            alert('Security clearance required. Please login to archive calculations.');
        } else {
            alert('Calculation commit failed. Internal trace required.');
        }
    }).finally(() => {
        saving.value = false;
    });
};
</script>

<template>
    <PublicLayout>
        <Head title="Cost Estimates" />

        <div class="min-h-screen bg-zinc-950 pt-24 pb-20">
            <div class="max-w-7xl mx-auto px-6">
                <!-- Branding Header -->
                <div class="mb-16 text-center">
                    <span class="text-xs font-black uppercase tracking-[0.5em] text-orange-600 block mb-4">Structural Finance Hub</span>
                    <h1 class="text-6xl font-black uppercase tracking-tighter text-white mb-6 italic">Project <span class="text-orange-600">Estimator.</span></h1>
                    <p class="text-zinc-500 max-w-2xl mx-auto text-sm font-medium leading-relaxed uppercase tracking-widest italic">Generate professional-grade cost projections for your upcoming architecture and construction projects.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
                    <div class="lg:col-span-3 space-y-12">
                        <!-- Initialization Context -->
                        <div class="bg-zinc-900 p-12 rounded-[2rem] border-2 border-zinc-800 shadow-2xl">
                            <div class="flex gap-8 mb-10 border-b border-zinc-800 pb-6">
                                <button @click="isCustomProject = false" :class="!isCustomProject ? 'text-orange-600 border-b-2 border-orange-600' : 'text-zinc-500'" class="pb-2 text-[10px] font-black uppercase tracking-widest transition-all">Existing Portfolio</button>
                                <button @click="isCustomProject = true" :class="isCustomProject ? 'text-orange-600 border-b-2 border-orange-600' : 'text-zinc-500'" class="pb-2 text-[10px] font-black uppercase tracking-widest transition-all">New Project Concept</button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div v-if="!isCustomProject">
                                    <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500 mb-4">Project Architecture</label>
                                    <select v-model="portfolio_id" class="w-full bg-white text-black border-none rounded-2xl px-8 py-5 text-sm font-black uppercase tracking-tight focus:ring-4 focus:ring-orange-600/10 transition-all cursor-pointer">
                                        <option value="" class="text-zinc-500">Select Target Entity</option>
                                        <option v-for="p in portfolios" :key="p.id" :value="p.id">{{ p.title }}</option>
                                    </select>
                                </div>
                                <div v-else>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500 mb-4">Concept Identifier</label>
                                    <input v-model="project_title" type="text" placeholder="ENTER NEW PROJECT NAME" class="w-full bg-white text-black border-none rounded-2xl px-8 py-5 text-sm font-black uppercase tracking-tight focus:ring-4 focus:ring-orange-600/10 transition-all">
                                </div>

                                <div v-if="selectedProject" class="bg-black p-8 rounded-3xl text-white transform hover:scale-105 transition-all duration-500 shadow-2xl border border-zinc-800">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <span class="text-[8px] font-black uppercase tracking-widest text-orange-600">Context Identifier</span>
                                            <h3 class="text-xl font-black uppercase italic mt-1 text-white">{{ selectedProject.title }}</h3>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[8px] font-black uppercase tracking-widest text-zinc-500">Classification</span>
                                            <p class="text-[10px] text-white font-bold uppercase tracking-widest mt-1 italic">{{ selectedProject.project_type }}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <span class="text-[8px] font-black uppercase tracking-widest text-zinc-500">Vector Location</span>
                                            <p class="text-xs text-zinc-400 font-medium uppercase tracking-widest mt-1 italic">{{ selectedProject.location }}</p>
                                        </div>
                                        <div v-if="selectedProject.description || selectedProject.short_description">
                                            <span class="text-[8px] font-black uppercase tracking-widest text-zinc-500">Technical Brief</span>
                                            <p class="text-[10px] text-zinc-500 leading-relaxed mt-1 line-clamp-2 italic">{{ selectedProject.short_description || selectedProject.description }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div v-else-if="isCustomProject" class="bg-orange-600/5 p-8 rounded-3xl border border-dashed border-orange-600/20 flex flex-col justify-center">
                                    <span class="text-[8px] font-black uppercase tracking-widest text-orange-600 mb-2">Simulation Mode</span>
                                    <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest italic leading-relaxed">You are in sandbox mode. Calibrate all vectors below to generate a custom projection for your unique architecture.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Segment -->
                        <div class="bg-zinc-900 rounded-[2rem] border-2 border-zinc-800 overflow-hidden shadow-xl">
                            <div class="p-10 border-b border-zinc-800 bg-zinc-800/20 flex justify-between items-center">
                                <div class="flex items-center gap-6">
                                    <div class="w-12 h-12 bg-black rounded-2xl flex items-center justify-center text-orange-600 shadow-xl shadow-orange-600/10 border border-zinc-800"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg></div>
                                    <h2 class="text-2xl font-black uppercase tracking-tighter italic text-white">Structural <span class="text-orange-600">Materials.</span></h2>
                                </div>
                                <button @click="addMaterial" class="px-6 py-3 bg-orange-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-white hover:text-black transition-all flex items-center gap-3">Index Vector</button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-black">
                                        <tr class="text-[9px] font-black uppercase tracking-widest text-orange-600">
                                            <th class="px-10 py-6">Identifier</th>
                                            <th class="px-6 py-6 w-32 text-center">Quant.</th>
                                            <th class="px-6 py-6 w-40 text-right">Unit Val.</th>
                                            <th class="px-6 py-6 w-40 text-right">Segment Sum</th>
                                            <th class="px-10 py-6 w-20"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-800">
                                        <tr v-for="(item, index) in materials" :key="index" class="odd:bg-zinc-900 even:bg-zinc-800/10 transition-colors">
                                            <td class="px-10 py-7"><input v-model="item.item_name" class="w-full bg-transparent border-none p-0 text-sm font-black uppercase italic text-white placeholder:text-zinc-700" placeholder="ITEM-0X"></td>
                                            <td class="px-6 py-7"><input v-model="item.quantity" type="number" class="w-full bg-white text-black border-none rounded-xl p-3 text-sm font-black text-center focus:ring-2 focus:ring-orange-600"></td>
                                            <td class="px-6 py-7"><input v-model="item.unit_cost" type="number" class="w-full bg-white text-black border-none rounded-xl p-3 text-sm font-black text-right focus:ring-2 focus:ring-orange-600"></td>
                                            <td class="px-6 py-7 text-right font-black italic text-orange-600">{{ formatCurrency(item.total) }}</td>
                                            <td class="px-10 py-7 text-center">
                                                <button @click="removeMaterial(index)" class="text-zinc-300 hover:text-red-500 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Workforce Segment -->
                        <div class="bg-zinc-900 rounded-[2rem] border-2 border-zinc-800 overflow-hidden shadow-xl">
                            <div class="p-10 border-b border-zinc-800 bg-blue-600/5 flex justify-between items-center">
                                <div class="flex items-center gap-6">
                                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-600/10"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></div>
                                    <h2 class="text-2xl font-black uppercase tracking-tighter italic text-white">Personnel <span class="text-orange-600">Deployment.</span></h2>
                                </div>
                                <button @click="addLabor" class="px-6 py-3 bg-blue-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-white hover:text-black transition-all flex items-center gap-3">Assign Member</button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-black">
                                        <tr class="text-[9px] font-black uppercase tracking-widest text-white">
                                            <th class="px-10 py-6">Designation</th>
                                            <th class="px-6 py-6 w-32 text-center">Cohort Size</th>
                                            <th class="px-6 py-6 w-32 text-center">Interval (D)</th>
                                            <th class="px-6 py-6 w-40 text-right">Daily Scale</th>
                                            <th class="px-6 py-6 w-40 text-right">Labor Inflow</th>
                                            <th class="px-10 py-6 w-20"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-800">
                                        <tr v-for="(item, index) in labor" :key="index" class="odd:bg-zinc-900 even:bg-zinc-800/10 transition-colors">
                                            <td class="px-10 py-7"><input v-model="item.worker_type" class="w-full bg-transparent border-none p-0 text-sm font-black uppercase italic text-white placeholder:text-zinc-700" placeholder="ARCHITECT / LEAD"></td>
                                            <td class="px-6 py-7"><input v-model="item.count" type="number" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-sm font-black text-center text-white focus:ring-2 focus:ring-blue-600"></td>
                                            <td class="px-6 py-7"><input v-model="item.days" type="number" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-sm font-black text-center text-white focus:ring-2 focus:ring-blue-600"></td>
                                            <td class="px-6 py-7"><input v-model="item.daily_rate" type="number" class="w-full bg-zinc-800 border border-zinc-700 rounded-xl p-3 text-sm font-black text-right text-white focus:ring-2 focus:ring-blue-600"></td>
                                            <td class="px-6 py-7 text-right font-black italic text-orange-600">{{ formatCurrency(item.total) }}</td>
                                            <td class="px-10 py-7 text-center">
                                                <button @click="removeLabor(index)" class="text-zinc-300 hover:text-red-500 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Heavy Machinery Segment -->
                        <div class="bg-zinc-900 rounded-[2rem] border-2 border-zinc-800 overflow-hidden shadow-xl">
                            <div class="p-10 border-b border-zinc-800 bg-emerald-600/5 flex justify-between items-center">
                                <div class="flex items-center gap-6">
                                    <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-emerald-600/10 border border-zinc-800"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></div>
                                    <h2 class="text-2xl font-black uppercase tracking-tighter italic text-white">Heavy <span class="text-orange-600">Machinery.</span></h2>
                                </div>
                                <button @click="addEquipment" class="px-6 py-3 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl hover:bg-white hover:text-black transition-all flex items-center gap-3">Acquire Asset</button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-black">
                                        <tr class="text-[9px] font-black uppercase tracking-widest text-orange-600">
                                            <th class="px-10 py-6">Machine Model</th>
                                            <th class="px-6 py-6 w-32 text-center">Ops Hrs</th>
                                            <th class="px-6 py-6 w-40 text-right">Hourly Rate</th>
                                            <th class="px-6 py-6 w-40 text-right">Segment Cost</th>
                                            <th class="px-10 py-6 w-20"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-800">
                                        <tr v-for="(item, index) in equipment" :key="index" class="odd:bg-zinc-900 even:bg-zinc-800/10 transition-colors">
                                            <td class="px-10 py-7"><input v-model="item.equipment_name" class="w-full bg-transparent border-none p-0 text-sm font-black uppercase italic text-white placeholder:text-zinc-700" placeholder="INDUSTRIAL UNIT"></td>
                                            <td class="px-6 py-7"><input v-model="item.hours" type="number" class="w-full bg-white text-black border-none rounded-xl p-3 text-sm font-black text-center focus:ring-2 focus:ring-orange-600"></td>
                                            <td class="px-6 py-7"><input v-model="item.hourly_rate" type="number" class="w-full bg-white text-black border-none rounded-xl p-3 text-sm font-black text-right focus:ring-2 focus:ring-orange-600"></td>
                                            <td class="px-6 py-7 text-right font-black italic text-orange-600">{{ formatCurrency(item.total) }}</td>
                                            <td class="px-10 py-7 text-center">
                                                <button @click="removeEquipment(index)" class="text-zinc-300 hover:text-red-500 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Command Center -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-28 bg-zinc-900 rounded-[3rem] p-12 text-white shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] space-y-12">
                            <div class="border-b border-zinc-800 pb-10">
                                <span class="text-[9px] font-black uppercase tracking-[0.5em] text-orange-600 block mb-3">Live Calculation Vector</span>
                                <h2 class="text-4xl font-black uppercase tracking-tighter italic leading-none">Estimate <br/><span class="text-zinc-500">Protocol.</span></h2>
                            </div>

                            <div class="space-y-6">
                                <div class="flex justify-between items-center opacity-60">
                                    <span class="text-[9px] font-black uppercase tracking-widest italic">Structural Inv.</span>
                                    <span class="text-sm font-black tabular-nums">{{ formatCurrency(materialTotal) }}</span>
                                </div>
                                <div class="flex justify-between items-center opacity-60">
                                    <span class="text-[9px] font-black uppercase tracking-widest italic">Workforce Log.</span>
                                    <span class="text-sm font-black tabular-nums">{{ formatCurrency(laborTotal) }}</span>
                                </div>
                                <div class="flex justify-between items-center opacity-60">
                                    <span class="text-[9px] font-black uppercase tracking-widest italic">Asset Load</span>
                                    <span class="text-sm font-black tabular-nums">{{ formatCurrency(equipmentTotal) }}</span>
                                </div>
                                
                                <div class="pt-8 border-t border-zinc-800 space-y-8">
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center">
                                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500 italic">Regulatory Tax (%)</label>
                                            <span class="text-xs font-black text-orange-600">+{{ formatCurrency(taxAmount) }}</span>
                                        </div>
                                        <input v-model="tax_percent" type="range" class="w-full accent-orange-600 h-1 bg-zinc-800 rounded-lg appearance-none">
                                    </div>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center">
                                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500 italic">Buffer Layer (%)</label>
                                            <span class="text-xs font-black text-orange-600">+{{ formatCurrency(contingencyAmount) }}</span>
                                        </div>
                                        <input v-model="contingency_percent" type="range" class="w-full accent-orange-600 h-1 bg-zinc-800 rounded-lg appearance-none">
                                    </div>
                                </div>

                                <div class="pt-12">
                                    <div class="bg-gradient-to-br from-zinc-800 to-zinc-900 -mx-12 -mb-12 p-12 rounded-b-[3rem] border-t border-zinc-800 overflow-hidden">
                                        <span class="text-[9px] font-black uppercase tracking-[0.5em] text-orange-600 block mb-4">Target Expenditure</span>
                                        <p class="text-4xl md:text-5xl lg:text-3xl xl:text-4xl font-black tracking-tighter leading-none italic break-words">{{ formatCurrency(grandTotal) }}</p>
                                        <button 
                                            @click="saveEstimate"
                                            :disabled="saving"
                                            class="w-full mt-10 py-5 bg-orange-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-orange-600/20 hover:bg-white hover:text-black transition-all flex items-center justify-center gap-3 active:scale-95 group disabled:opacity-50"
                                        >
                                            <span v-if="saving" class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                                            <span v-else>{{ $page.props.auth?.user ? 'Index Calculation' : 'Commit Calculation' }}</span>
                                            <svg v-if="!saving" class="w-4 h-4 transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </button>
                                        <p class="text-center text-zinc-700 text-[8px] font-black uppercase tracking-widest mt-8 italic">Brick & Beam // Proprietary Ledger Logic</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
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

input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  height: 14px;
  width: 14px;
  border-radius: 50%;
  background: #ea580c;
  cursor: pointer;
  box-shadow: 0 0 10px rgba(234, 88, 12, 0.4);
}
</style>
