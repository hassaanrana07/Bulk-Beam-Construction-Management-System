<script setup>
import { computed } from 'vue';

const props = defineProps({
    content: Object
});

const defaultStandards = [
    { id: 'S1', title: 'Zero-Harm Directive', desc: 'Every accident is preventable through technical discipline and constant vigilance.' },
    { id: 'S2', title: 'Daily Site Audits', desc: 'Comprehensive safety vector analysis every 24 hours to mitigate environmental risk.' },
    { id: 'S3', title: 'OSHA+ Training', desc: 'Personnel exceed standard certifications with specialized industrial modules.' }
];

const standards = computed(() => {
    if (props.content.features && props.content.features.length > 0) {
        return props.content.features;
    }
    return defaultStandards;
});

const defaultStats = {
    ltifr: '0.00',
    safe_hours: '1.2M+',
    ppe_compliance: '100%'
};

const stats = computed(() => {
    return { ...defaultStats, ...(props.content.stats || {}) };
});
</script>

<template>
    <section class="py-32 bg-white dark:bg-navy-950 border-y border-zinc-100 dark:border-zinc-900 relative overflow-hidden">
        <!-- Structural Accents -->
        <div class="absolute left-0 top-1/2 w-32 h-px bg-primary/30 hidden lg:block"></div>
        <div class="absolute right-0 top-1/2 w-32 h-px bg-primary/30 hidden lg:block"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
                
                <!-- Left: Text Core -->
                <div v-reveal class="reveal reveal-left space-y-16 order-2 lg:order-1">
                    <div class="space-y-6">
                        <span class="text-xs font-black text-primary uppercase tracking-[0.5em] block">{{ content.subtitle || 'Industrial Security Matrix' }}</span>
                        <h2 class="text-6xl md:text-8xl font-black uppercase tracking-tighter leading-none m-0 italic">
                            {{ content.heading || 'Safety' }} <span class="text-zinc-200 dark:text-zinc-800">{{ content.subheading || 'Protocol.' }}</span>
                        </h2>
                    </div>
                    
                    <div class="space-y-12">
                        <div v-for="(s, i) in standards" :key="i" class="flex gap-8 group">
                            <div class="w-16 h-16 bg-navy-950 dark:bg-zinc-900 text-white flex items-center justify-center font-black text-xl italic flex-shrink-0 group-hover:bg-primary group-hover:text-black transition-all duration-500 border border-zinc-900 drop-shadow-xl">
                                {{ s.id || `S${i+1}` }}
                            </div>
                            <div class="pt-2">
                                <h3 class="text-xl font-black uppercase tracking-tight mb-3 group-hover:text-primary transition-colors">{{ s.title }}</h3>
                                <p class="text-[13px] text-zinc-500 font-bold uppercase tracking-wide leading-relaxed">{{ s.desc }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Tactical Dashboard -->
                <div v-reveal class="reveal reveal-right order-1 lg:order-2">
                    <div class="bg-zinc-50 dark:bg-navy-900 p-16 border-l-[12px] border-primary shadow-2xl relative group overflow-hidden">
                        <!-- Background Pattern -->
                        <div class="absolute inset-0 opacity-[0.03] pointer-events-none group-hover:opacity-[0.07] transition-opacity duration-1000">
                             <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="1"/>
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#grid)" />
                            </svg>
                        </div>

                        <div class="relative z-10">
                            <div class="mb-16">
                                <span class="text-[10px] font-black uppercase tracking-[0.6em] text-zinc-400 block mb-6 px-1">Current Safety Index</span>
                                <div class="flex items-baseline gap-4">
                                    <p class="text-9xl font-black tracking-tighter text-black dark:text-white leading-none italic">{{ stats.ltifr }}</p>
                                    <span class="text-primary animate-ping inline-flex h-3 w-3 rounded-full bg-blue-400 opacity-75"></span>
                                </div>
                                <p class="text-[10px] font-black uppercase tracking-[0.4em] text-primary mt-6 border-t border-primary/20 pt-4 inline-block">Lost-Time Injury Frequency Rate</p>
                            </div>

                            <div class="grid grid-cols-2 gap-12 pt-16 border-t border-zinc-200 dark:border-zinc-800">
                                <div class="group/stat">
                                    <p class="text-4xl font-black text-black dark:text-white tracking-tighter group-hover/stat:text-primary transition-colors">{{ stats.safe_hours }}</p>
                                    <p class="text-[9px] uppercase font-black text-zinc-400 tracking-[0.4em] mt-2">Safe Labor Hours</p>
                                </div>
                                <div class="group/stat">
                                    <p class="text-4xl font-black text-black dark:text-white tracking-tighter group-hover/stat:text-primary transition-colors">{{ stats.ppe_compliance }}</p>
                                    <p class="text-[9px] uppercase font-black text-zinc-400 tracking-[0.4em] mt-2">PPE Compliance Ratio</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tactical Border Info -->
                        <div class="absolute bottom-4 right-4 text-[7px] font-black text-zinc-300 dark:text-zinc-800 uppercase tracking-[0.8em]">
                            DIRECTIVE_INTEGRITY_INDEX_4200
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Identifier -->
        <div class="absolute left-12 bottom-12 text-[9px] font-black text-zinc-200 dark:text-zinc-800 uppercase tracking-[0.8em] [writing-mode:vertical-lr] pointer-events-none">
            RISK ASSESSMENT // BRICK & BEAM
        </div>
    </section>
</template>
