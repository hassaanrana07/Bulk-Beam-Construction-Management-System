<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SectionRenderer from '@/Components/SectionRenderer.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    services: Array,
    page: Object,
    estimation_rules: Array
});

// ── Calculator Logic ──────────────────────────────────────────
const area = ref(1500);
const selectedRuleId = ref(props.estimation_rules?.[0]?.id || null);
const selectedSector = ref('residential');
const selectedScope = ref('standard');

const currentRule = computed(() => 
    props.estimation_rules?.find(r => r.id === selectedRuleId.value) || props.estimation_rules?.[0]
);

const scopeMultipliers = { standard: 1.0, premium: 1.25, luxury: 1.5 };

const estimation = computed(() => {
    if (!currentRule.value) return null;

    const rule = currentRule.value;
    const baseRate = rule.base_rate_per_sqft || 0;
    const matMult = (rule.material_cost_factor || 0);
    const labMult = (rule.labor_cost_factor || 0);
    const sectorMult = (rule.sector_multipliers && rule.sector_multipliers[selectedSector.value]) || 1.0;
    const scopeMult = scopeMultipliers[selectedScope.value] || 1.0;

    const baseCost = area.value * baseRate;
    // Total = BaseCost * (1 + Mat + Lab) * Sector * Scope
    const totalCost = baseCost * (1 + matMult + labMult) * sectorMult * scopeMult;
    
    const weeksPer1K = rule.timeline_weeks_per_1000sqft || 8;
    const estimatedWeeks = Math.max(4, Math.ceil((area.value / 1000) * weeksPer1K * scopeMult));

    return {
        total: Math.round(totalCost),
        breakdown: [
            { label: 'Structural Base', amount: Math.round(baseCost * sectorMult * scopeMult) },
            { label: 'System Multipliers (+' + Math.round((matMult + labMult) * 100) + '%)', amount: Math.round(baseCost * (matMult + labMult) * sectorMult * scopeMult) },
        ],
        timeline: estimatedWeeks,
        resources: Math.ceil(area.value / 500) + (selectedScope.value === 'luxury' ? 2 : 0)
    };
});

const formatCurrency = (val) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 0
    }).format(val);
};
</script>

<template>
    <PublicLayout :key="$page.url">
        <Head title="Technical Capabilities" />

        <div v-if="page && page.sections && page.sections.length > 0">
            <SectionRenderer 
                :sections="page.sections" 
                :data="{ services: services }"
            />
        </div>

        <div v-else>
            <!-- Fallback Default Content -->
            <div class="pt-32 pb-20 bg-black text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <span class="text-xs font-black text-orange-600 uppercase tracking-[0.3em] mb-4 block">Our Operations</span>
                    <h1 class="text-6xl md:text-8xl font-black uppercase tracking-tighter leading-none mb-8">
                        Capabilities<span class="text-orange-600">.</span>
                    </h1>
                    <p class="text-xl text-gray-400 max-w-2xl leading-relaxed">
                        Industrial-grade construction solutions engineered for scale, precision, and architectural legacy.
                    </p>
                </div>
            </div>

            <section class="py-32 bg-white dark:bg-black">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="service in services" :key="service.id" 
                            class="bg-white dark:bg-zinc-950 p-16 border border-gray-100 dark:border-zinc-900 hover:border-orange-600 transition-all duration-500 group relative rounded-xl shadow-sm">
                            <div class="mb-12">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-4">SEGMENT 0{{ service.id }}</span>
                                <h2 class="text-3xl font-black uppercase tracking-tighter leading-none">{{ service.title }}</h2>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 leading-relaxed mb-12 text-sm">
                                {{ service.short_description }}
                            </p>
                            <Link :href="route('services.show', service.slug || '')" class="inline-flex items-center gap-4 text-xs font-black uppercase tracking-widest text-orange-600 group-hover:gap-6 transition-all duration-500">
                                View Specification
                                <span class="w-12 h-px bg-orange-600"></span>
                            </Link>
                            <div class="absolute bottom-0 left-0 w-0 h-1 bg-orange-600 group-hover:w-full transition-all duration-700"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Launch Estimate Module Activation -->
            <section class="py-32 bg-zinc-950 text-white overflow-hidden border-t border-zinc-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                        <div>
                            <span class="text-xs font-black text-orange-600 uppercase tracking-[0.3em] mb-6 block font-bold">Launch Estimate Module</span>
                            <h2 class="text-5xl md:text-7xl font-black uppercase tracking-tighter leading-tight mb-8">
                                Project Cost<br/>Calculator<span class="text-orange-600">.</span>
                            </h2>
                            <p class="text-lg text-zinc-500 mb-12 leading-relaxed">
                                Our proprietary estimation engine utilizes localized labor indices and material logistics factors to provide immediate structural budget projections.
                            </p>

                            <div class="space-y-10">
                                <!-- Area Input -->
                                <div class="space-y-4">
                                    <div class="flex justify-between items-end">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Total Area (Sq. Ft)</label>
                                        <span class="text-2xl font-black text-orange-600 tracking-tighter">{{ area.toLocaleString() }} SQFT</span>
                                    </div>
                                    <input v-model="area" type="range" min="500" max="25000" step="100" class="w-full h-1 bg-zinc-800 appearance-none cursor-pointer accent-orange-600">
                                </div>

                                <!-- Category & Sector -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-zinc-600">Project Category</label>
                                        <select v-model="selectedRuleId" class="w-full bg-zinc-900 border border-zinc-800 text-sm font-bold p-4 focus:ring-1 focus:ring-orange-600 rounded-lg">
                                            <option v-for="rule in estimation_rules" :key="rule.id" :value="rule.id">{{ rule.name }}</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-zinc-600">Sector Type</label>
                                        <select v-model="selectedSector" class="w-full bg-zinc-900 border border-zinc-800 text-sm font-bold p-4 focus:ring-1 focus:ring-orange-600 rounded-lg">
                                            <option value="residential">Private Residential</option>
                                            <option value="commercial">Commercial Estate</option>
                                            <option value="industrial">Industrial Complex</option>
                                            <option value="government">Government Infrastructure</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Scope Level -->
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-zinc-600 block">Scope Level</label>
                                    <div class="grid grid-cols-3 gap-4">
                                        <button v-for="scope in ['standard', 'premium', 'luxury']" :key="scope"
                                            @click="selectedScope = scope"
                                            :class="selectedScope === scope ? 'bg-orange-600 border-orange-600 text-white shadow-lg shadow-orange-600/20' : 'bg-zinc-900 border-zinc-800 text-zinc-500 hover:border-zinc-700'"
                                            class="py-4 border-2 text-[10px] font-black uppercase tracking-widest transition-all rounded-lg">
                                            {{ scope }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Results Card -->
                        <div class="bg-white text-black p-12 md:p-16 rounded-3xl shadow-2xl relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-orange-600/5 -mr-16 -mt-16 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
                            
                            <h3 class="text-xs font-black uppercase tracking-[0.4em] text-orange-600 mb-12">Deployment Projection</h3>
                            
                            <div class="space-y-12">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-2">Total Estimated Cost</p>
                                    <p class="text-6xl md:text-7xl font-black tracking-tighter leading-none italic">{{ formatCurrency(estimation?.total) }}</p>
                                </div>

                                <div class="space-y-6 pt-12 border-t border-zinc-100">
                                    <div v-for="item in estimation?.breakdown" :key="item.label" class="flex justify-between items-center text-xs font-black uppercase tracking-widest">
                                        <span class="text-zinc-400">{{ item.label }}</span>
                                        <span class="text-black">{{ formatCurrency(item.amount) }}</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-8 pt-6">
                                    <div class="bg-zinc-50 p-6 rounded-2xl border border-zinc-100">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-zinc-400 mb-2">Timeline</p>
                                        <p class="text-2xl font-black italic">{{ estimation?.timeline }} <span class="text-xs uppercase opacity-40 ml-1">Weeks</span></p>
                                    </div>
                                    <div class="bg-zinc-50 p-6 rounded-2xl border border-zinc-100">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-zinc-400 mb-2">Personnel</p>
                                        <p class="text-2xl font-black italic">{{ estimation?.resources }} <span class="text-xs uppercase opacity-40 ml-1">Units</span></p>
                                    </div>
                                </div>

                                <Link :href="route('contact')" class="block w-full py-6 bg-black text-white text-center text-xs font-black uppercase tracking-[0.3em] hover:bg-orange-600 transition-all rounded-xl shadow-xl">
                                    Initiate Full Structural Audit
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

             <section class="py-32 bg-black text-white overflow-hidden border-t border-gray-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-24">
                        <h3 class="text-4xl font-black uppercase tracking-tighter mb-4">Our Deployment Protocol</h3>
                        <p class="text-gray-500 uppercase tracking-widest text-xs font-bold font-bold">Six Phases of Industrial Execution</p>
                    </div>
                    
                    <div class="grid grid-cols-2 lg:grid-cols-6 gap-8">
                        <div v-for="i in 6" :key="i" class="text-center space-y-4">
                            <div class="w-16 h-16 border border-gray-800 flex items-center justify-center mx-auto text-orange-600 font-black text-xl rounded-xl transition-all hover:border-orange-600 hover:scale-110 shadow-lg">{{ i }}</div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Phase {{ i }}</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>
