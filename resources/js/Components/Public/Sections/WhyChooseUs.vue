<script setup>
import { computed } from 'vue';

const props = defineProps({
    content: Object
});

const defaultValues = [
    { title: 'Structural Integrity', desc: 'Our engineering exceeds municipal standards to ensure multi-generational durability.' },
    { title: 'Fixed-Price Assurance', desc: 'Enterprise-grade budgeting prevents cost overruns through precise procurement.' },
    { title: 'Executive Management', desc: 'Your project is supervised by senior construction executives, not just site managers.' },
    { title: 'Rapid Deployment', desc: 'Optimized supply chain logistics allow for faster start dates and tighter timelines.' }
];

const values = computed(() => {
    if (props.content.values && props.content.values.length > 0) {
        return props.content.values;
    }
    return defaultValues;
});
</script>

<template>
    <section class="py-32 bg-[#0b1120] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <!-- Features Column -->
                <div class="space-y-12">
                    <h2 class="text-3xl font-black text-white uppercase tracking-tight">The BuildTrust Advantage</h2>
                    
                    <div class="space-y-10">
                        <div v-for="(val, idx) in values.slice(0, 3)" :key="val.title" class="flex gap-6 group">
                            <div class="flex-shrink-0 w-12 h-12 bg-amber-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7" v-if="idx===0" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" v-if="idx===1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" v-if="idx===2" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="space-y-2">
                                <h3 class="text-lg font-black text-white uppercase tracking-tight">{{ val.title }}</h3>
                                <p class="text-slate-500 text-sm font-medium leading-relaxed max-w-md">{{ val.desc }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Mockup Column -->
                <div class="relative group">
                    <div class="absolute -inset-10 bg-amber-500/10 blur-[100px] rounded-full opacity-20 group-hover:opacity-40 transition-opacity"></div>
                    <div class="relative bg-slate-900 border border-white/5 rounded-[2.5rem] p-10 shadow-3xl transform group-hover:scale-105 transition-transform duration-700">
                        <div class="flex items-center justify-between mb-10">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-white">Project Health Dashboard</h4>
                            <span class="px-3 py-1 bg-green-500/10 text-green-500 text-[8px] font-black uppercase tracking-widest rounded border border-green-500/20">Stable</span>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Progress Mockup -->
                            <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-500 w-[88%] shadow-glow-primary"></div>
                            </div>
                            <div class="h-2 w-[70%] bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-slate-700 w-full"></div>
                            </div>

                            <div class="grid grid-cols-2 gap-6 pt-6">
                                <div class="p-6 bg-[#0b0f19] border border-white/5 rounded-2xl">
                                    <p class="text-3xl font-black text-white italic">88%</p>
                                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest mt-2">Efficiency Rating</p>
                                </div>
                                <div class="p-6 bg-[#0b0f19] border border-white/5 rounded-2xl">
                                    <p class="text-3xl font-black text-white italic">12d</p>
                                    <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest mt-2">Ahead of schedule</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
