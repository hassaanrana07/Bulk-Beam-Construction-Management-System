<script setup>
import { computed } from 'vue';

const props = defineProps({
    content: Object
});

const defaultMilestones = [
    { year: '2005', title: 'The Foundation', desc: 'Arthur Beam founds the company as a structural auditing firm in Chicago.' },
    { year: '2010', title: 'Industrial Shift', desc: 'Secured first $100M infrastructure project, expanding into full-service construction.' },
    { year: '2016', title: 'Tech Integration', desc: 'Deployed first proprietary CMS tools for real-time project tracking and lead management.' },
    { year: '2025', title: 'Global Operations', desc: 'Established active project hubs in 12 major North American markets.' }
];

const milestones = computed(() => {
    if (props.content.milestones && props.content.milestones.length > 0) {
        return props.content.milestones;
    }
    return defaultMilestones;
});
</script>

<template>
    <section class="py-32 bg-black text-white overflow-hidden relative">
        <!-- Background Grid -->
        <div class="absolute inset-x-0 top-0 h-full opacity-5 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/graphy.png')]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div v-reveal class="reveal reveal-up text-center mb-24">
                <span class="text-xs font-black text-orange-600 uppercase tracking-[0.3em] mb-4 block underline underline-offset-8 decoration-orange-600/30">{{ content.subtitle || 'Our Evolution' }}</span>
                <h2 class="text-6xl md:text-8xl font-black uppercase tracking-tighter leading-none m-0 italic">
                    {{ content.heading || 'Growth' }} <span class="text-zinc-800">{{ content.subheading || 'Timeline.' }}</span>
                </h2>
            </div>

            <div class="relative">
                <!-- Center Line -->
                <div v-reveal class="reveal reveal-up absolute left-1/2 top-0 bottom-0 w-px bg-zinc-900 hidden lg:block origin-top scale-y-110"></div>

                <div class="space-y-48">
                    <div v-for="(m, index) in milestones" :key="m.year" 
                        v-reveal 
                        :class="[
                            'flex flex-col lg:flex-row items-center gap-12 reveal', 
                            index % 2 === 1 ? 'lg:flex-row-reverse reveal-right' : 'reveal-left'
                        ]">
                        
                        <div class="w-full lg:w-1/2 lg:px-24 text-center lg:text-right group" :class="index % 2 === 1 ? 'lg:text-left' : ''">
                            <span class="text-8xl md:text-[10rem] font-black tracking-tighter text-zinc-900 mb-[-2rem] block transition-colors group-hover:text-orange-600/10 pointer-events-none font-sans italic">{{ m.year }}</span>
                            <div class="relative z-10">
                                <h3 class="text-3xl font-black uppercase tracking-tighter mb-4 italic text-white group-hover:text-orange-600 transition-colors">{{ m.title }}</h3>
                                <p class="text-sm font-bold uppercase tracking-wide text-zinc-500 max-w-md mx-auto leading-relaxed" :class="index % 2 === 1 ? 'lg:ml-0' : 'lg:mr-0'">{{ m.desc }}</p>
                            </div>
                        </div>
                        
                        <!-- Tactical Hub Dot -->
                        <div class="w-12 h-12 flex items-center justify-center relative z-10 hidden lg:flex">
                             <div class="w-3 h-3 bg-orange-600 rotate-45 group-hover:scale-150 transition-transform duration-500"></div>
                             <div class="absolute inset-0 border border-orange-600/20 rotate-45 scale-150 group-hover:scale-110 transition-transform duration-700"></div>
                        </div>
                        
                        <div class="w-full lg:w-1/2"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Identifier -->
        <div class="absolute right-12 bottom-12 text-[9px] font-black text-zinc-800 uppercase tracking-[0.5em] [writing-mode:vertical-lr]">
            TEMPORAL LOGS // BRICK & BEAM
        </div>
    </section>
</template>
