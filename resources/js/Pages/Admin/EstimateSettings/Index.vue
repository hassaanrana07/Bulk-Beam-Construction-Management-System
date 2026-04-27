<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({ rules: Array });

const blankRule = () => ({
    id: null,
    name: '',
    category: 'residential',
    sector: 'residential',
    scope_level: 'standard',
    base_rate_per_sqft: 80,
    material_cost_factor: 500,
    labor_cost_factor: 300,
    timeline_weeks_per_1000sqft: 8,
    sector_multipliers: { residential: 1.0, commercial: 1.3, industrial: 1.6 },
    complexity_multipliers: { Standard: 1.0, Premium: 1.5, Luxury: 2.0 },
    is_active: true,
    order: 0,
});

const showModal = ref(false);
const isEditing = ref(false);

const form = useForm({ ...blankRule() });

const openCreate = () => {
    isEditing.value = false;
    Object.assign(form, blankRule());
    showModal.value = true;
};

const openEdit = (rule) => {
    isEditing.value = true;
    form.id = rule.id;
    form.name = rule.name;
    form.category = rule.category;
    form.sector = rule.sector || 'residential';
    form.scope_level = rule.scope_level || 'standard';
    form.base_rate_per_sqft = rule.base_rate_per_sqft;
    form.material_cost_factor = rule.material_cost_factor;
    form.labor_cost_factor = rule.labor_cost_factor;
    form.timeline_weeks_per_1000sqft = rule.timeline_weeks_per_1000sqft || 8;
    form.sector_multipliers = rule.sector_multipliers || { residential: 1.0, commercial: 1.3, industrial: 1.6 };
    form.complexity_multipliers = rule.complexity_multipliers || { Standard: 1.0, Premium: 1.5, Luxury: 2.0 };
    form.is_active = rule.is_active;
    form.order = rule.order;
    showModal.value = true;
};

const submit = () => {
    if (isEditing.value) {
        form.put(route('admin.estimate-settings.update', form.id), {
            onSuccess: () => { showModal.value = false; }
        });
    } else {
        form.post(route('admin.estimate-settings.store'), {
            onSuccess: () => { showModal.value = false; }
        });
    }
};

const destroy = (id) => {
    if (!confirm('Delete this estimation rule permanently?')) return;
    useForm({}).delete(route('admin.estimate-settings.destroy', id));
};

const sectorKeys = ['residential', 'commercial', 'industrial', 'government'];

const complexityKeys = ['Standard', 'Premium', 'Luxury'];

// Preview calculation
const previewArea = ref(1500);
const previewComplexity = ref('Standard');
const previewCost = computed(() => {
    const areaVal = previewArea.value || 0;
    const base = areaVal * (form.base_rate_per_sqft || 0);
    const matMult = (form.material_cost_factor || 0);
    const labMult = (form.labor_cost_factor || 0);
    const designMult = (form.complexity_multipliers && form.complexity_multipliers[previewComplexity.value]) || 1;
    
    // Formula: (Area * Base) + (Area * Base * MatMult) + (Area * Base * LabMult * DesignMult)
    // Simplified: Area * Base * (1 + MatMult + (LabMult * DesignMult))
    return Math.round(base * (1 + matMult + (labMult * designMult)));
});
const previewTimeline = computed(() => {
    return Math.max(4, Math.ceil((previewArea.value / 1000) * (form.timeline_weeks_per_1000sqft || 8)));
});
</script>

<template>
    <AdminLayout>
        <Head title="Estimate Settings" />
        <ModuleHeader title="Cost Estimation Engine">
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mt-2">
                    Configure formula parameters for real-time project cost estimation.
                </p>
            </template>
            <template #actions>
                <button @click="openCreate" class="px-6 py-3 bg-orange-600 text-white text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white hover:text-black transition-all rounded-lg shadow-xl">
                    + Add Rule
                </button>
            </template>
        </ModuleHeader>

        <div v-if="$page.props.flash?.success" class="mb-8 p-4 bg-orange-600/10 border border-orange-600/20 rounded-lg">
            <p class="text-[10px] font-black uppercase tracking-widest text-orange-600">✓ {{ $page.props.flash.success }}</p>
        </div>

        <!-- Rules Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div v-for="rule in rules" :key="rule.id"
                class="bg-black border border-zinc-900 p-8 rounded-xl group hover:border-orange-600 transition-all">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <span class="text-[8px] font-black uppercase tracking-[0.4em] text-orange-600">{{ rule.category }}</span>
                        <h3 class="text-sm font-black uppercase text-white tracking-tight mt-1">{{ rule.name }}</h3>
                    </div>
                    <span :class="rule.is_active ? 'bg-green-900/30 text-green-500' : 'bg-red-900/30 text-red-500'"
                        class="text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded">
                        {{ rule.is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="space-y-2 border-t border-zinc-900 pt-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black uppercase text-zinc-600">Base Rate / sqft</span>
                        <span class="text-[10px] font-black text-orange-500">${{ rule.base_rate_per_sqft }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black uppercase text-zinc-600">Material Multiplier</span>
                        <span class="text-[10px] font-black text-zinc-300">x{{ rule.material_cost_factor }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black uppercase text-zinc-600">Labor Multiplier</span>
                        <span class="text-[10px] font-black text-zinc-300">x{{ rule.labor_cost_factor }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black uppercase text-zinc-600">Timeline</span>
                        <span class="text-[10px] font-black text-zinc-300">{{ rule.timeline_weeks_per_1000sqft ?? 8 }} wks/1K sqft</span>
                    </div>
                </div>

                <!-- Multipliers Grid -->
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <div v-if="rule.sector_multipliers" class="bg-zinc-950 rounded-lg p-4">
                        <p class="text-[8px] font-black uppercase tracking-widest text-zinc-600 mb-3">Sectors</p>
                        <div class="space-y-1">
                            <div v-for="(v, k) in rule.sector_multipliers" :key="k" class="flex justify-between">
                                <span class="text-[8px] text-zinc-600 uppercase">{{ k }}</span>
                                <span class="text-[8px] font-black text-orange-500">×{{ v }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="rule.complexity_multipliers" class="bg-zinc-950 rounded-lg p-4">
                        <p class="text-[8px] font-black uppercase tracking-widest text-zinc-600 mb-3">Complexity</p>
                        <div class="space-y-1">
                            <div v-for="(v, k) in rule.complexity_multipliers" :key="k" class="flex justify-between">
                                <span class="text-[8px] text-zinc-600 uppercase">{{ k }}</span>
                                <span class="text-[8px] font-black text-orange-500">×{{ v }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button @click="openEdit(rule)" class="flex-1 py-2.5 bg-zinc-900 border border-zinc-800 text-[8px] font-black uppercase tracking-widest text-zinc-400 hover:bg-white hover:text-black transition-all rounded">Edit</button>
                    <button @click="destroy(rule.id)" class="px-4 py-2.5 bg-red-950/30 border border-red-900/40 text-[8px] font-black uppercase text-red-500 hover:bg-red-600 hover:text-white transition-all rounded">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/90 backdrop-blur-md">
            <div class="bg-zinc-950 border border-zinc-800 w-full max-w-2xl p-10 rounded-2xl animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
                <h3 class="text-xl font-black uppercase text-white mb-8 italic">
                    {{ isEditing ? 'Update Estimation Rule' : 'New Estimation Rule' }}
                </h3>
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Name & Category -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Rule Name</label>
                            <input v-model="form.name" type="text" required class="w-full bg-black border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Category</label>
                            <input v-model="form.category" type="text" placeholder="e.g. residential" class="w-full bg-black border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg">
                        </div>
                    </div>

                    <!-- Core Formula -->
                    <div class="bg-black border border-orange-600/20 rounded-lg p-6 space-y-4">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 mb-2">Cost Multiplier Parameters</h4>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Base Rate / Sq.Ft (USD)</label>
                                <input v-model="form.base_rate_per_sqft" type="number" step="0.01" min="0" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black focus:border-orange-600 rounded-lg">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Material Multiplier (0.1 = 10%)</label>
                                <input v-model="form.material_cost_factor" type="number" step="0.01" min="0" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black focus:border-orange-600 rounded-lg">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Labor Multiplier (0.1 = 10%)</label>
                                <input v-model="form.labor_cost_factor" type="number" step="0.01" min="0" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black focus:border-orange-600 rounded-lg">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Timeline Weeks / 1000 Sq.Ft</label>
                                <input v-model="form.timeline_weeks_per_1000sqft" type="number" min="1" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black focus:border-orange-600 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Multipliers -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-black border border-zinc-900 rounded-lg p-6 space-y-4">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500 mb-2">Sector Multipliers</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div v-for="key in sectorKeys" :key="key" class="space-y-1">
                                    <label class="text-[8px] font-black uppercase tracking-widest text-zinc-600">{{ key }}</label>
                                    <input v-model="form.sector_multipliers[key]" type="number" step="0.01" min="0" placeholder="1.0" class="w-full bg-zinc-950 border border-zinc-900 text-white p-3 text-sm font-black focus:border-orange-600 rounded-lg">
                                </div>
                            </div>
                        </div>
                        <div class="bg-black border border-zinc-900 rounded-lg p-6 space-y-4">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500 mb-2">Complexity Multipliers</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div v-for="key in complexityKeys" :key="key" class="space-y-1">
                                    <label class="text-[8px] font-black uppercase tracking-widest text-zinc-600">{{ key }}</label>
                                    <input v-model="form.complexity_multipliers[key]" type="number" step="0.01" min="0" placeholder="1.0" class="w-full bg-zinc-950 border border-zinc-900 text-white p-3 text-sm font-black focus:border-orange-600 rounded-lg">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div class="bg-orange-600/5 border border-orange-600/20 rounded-lg p-6 space-y-4">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-600">Live Formula Preview</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[8px] font-black uppercase text-zinc-600 block mb-1">Area (sqft)</label>
                                <input v-model="previewArea" type="number" min="1" class="w-full bg-black border border-zinc-900 text-white p-2 text-xs font-black rounded">
                            </div>
                            <div>
                                <label class="text-[8px] font-black uppercase text-zinc-600 block mb-1">Design Complexity</label>
                                <select v-model="previewComplexity" class="w-full bg-black border border-zinc-900 text-white p-2 text-xs font-black rounded">
                                    <option v-for="k in complexityKeys" :key="k" :value="k">{{ k }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex gap-8 pt-2">
                            <div>
                                <p class="text-[8px] text-zinc-600 uppercase tracking-widest">Estimated Cost</p>
                                <p class="text-2xl font-black text-orange-500">${{ previewCost.toLocaleString() }}</p>
                            </div>
                            <div>
                                <p class="text-[8px] text-zinc-600 uppercase tracking-widest">Est. Timeline</p>
                                <p class="text-2xl font-black text-white">{{ previewTimeline }} <span class="text-sm text-zinc-500">wks</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Toggle -->
                    <div class="flex items-center justify-between p-4 bg-zinc-950 border border-zinc-900 rounded-lg">
                        <span class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Active Rule</span>
                        <button type="button" @click="form.is_active = !form.is_active"
                            :class="form.is_active ? 'bg-orange-600' : 'bg-zinc-800'"
                            class="w-12 h-6 rounded-full relative transition-colors duration-300">
                            <div :class="form.is_active ? 'translate-x-7' : 'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300"></div>
                        </button>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="showModal = false" class="flex-1 py-4 text-[10px] font-black text-zinc-500 uppercase hover:text-white transition-colors">Cancel</button>
                        <button type="submit" :disabled="form.processing" class="flex-1 py-4 bg-orange-600 text-white text-[10px] font-black uppercase rounded-lg hover:bg-orange-700 disabled:opacity-50">
                            {{ isEditing ? 'Update Rule' : 'Create Rule' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
