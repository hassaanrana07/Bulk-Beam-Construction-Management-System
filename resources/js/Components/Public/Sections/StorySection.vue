<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    content: Object
});
</script>

<template>
    <section class="py-32 bg-white dark:bg-navy-950 overflow-hidden relative">
        <!-- Structural Background Overlay -->
        <div class="absolute right-0 top-0 w-1/2 h-full bg-zinc-50 dark:bg-zinc-900/10 -skew-x-12 translate-x-1/4 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
                <!-- Visual Asset Node -->
                <div v-reveal class="reveal reveal-left relative group">
                    <div class="relative overflow-hidden shadow-2xl skew-y-3 group-hover:skew-y-0 transition-transform duration-1000">
                        <img :src="content.image || 'https://images.unsplash.com/photo-1503387762-592e9e926b7a?q=80&w=2062'" 
                             class="w-full aspect-[4/5] object-cover grayscale group-hover:grayscale-0 transition-all duration-1000 group-hover:scale-105" 
                             alt="Structural Heritage">
                        
                        <!-- Tactical Overlay -->
                        <div class="absolute inset-0 border-[20px] border-white dark:border-black opacity-10 pointer-events-none"></div>
                    </div>
                    
                    <div v-reveal class="reveal reveal-up delay-300 absolute -bottom-10 -right-10 w-72 h-72 bg-primary p-12 flex flex-col justify-end shadow-2xl skew-x-[-6deg] group-hover:skew-x-0 transition-transform duration-700">
                        <span class="text-white text-6xl font-black tracking-tighter mb-2 italic">2005</span>
                        <span class="text-white/90 text-[10px] font-black uppercase tracking-[0.3em] leading-tight">FOUNDED ON<br>INDUSTRIAL LOGIC</span>
                    </div>
                </div>
                
                <!-- Content Node -->
                <div v-reveal class="reveal reveal-right space-y-12">
                    <div class="space-y-6">
                        <span class="text-xs font-black text-primary uppercase tracking-[0.4em] block animate-pulse">{{ content.heading_label || 'Our Structural Genesis' }}</span>
                        <h2 class="text-6xl md:text-8xl font-black uppercase tracking-tighter leading-none m-0 italic">
                            {{ content.heading || 'Forged in' }} <span class="text-gray-300 dark:text-gray-800">{{ content.subheading || 'Ambition.' }}</span>
                        </h2>
                    </div>
                    
                    <div class="space-y-8">
                        <p class="text-gray-600 dark:text-gray-400 text-lg md:text-xl font-bold leading-relaxed uppercase tracking-tight opacity-80">
                            {{ content.subtitle || 'Brick & Beam was founded with a singular objective: to elevate construction from a service to a science.' }}
                        </p>
                        <div class="w-20 h-1 bg-primary"></div>
                        <p class="text-gray-500 dark:text-zinc-500 text-sm md:text-base leading-relaxed font-medium">
                            {{ content.description || 'Recognizing a critical gap in the market for high-performance builds that demand technical rigor and absolute executive accountability, we established a framework that prioritizes structural integrity over aesthetic compromise.' }}
                        </p>
                    </div>

                    <div class="flex gap-16 pt-12 border-t border-zinc-100 dark:border-zinc-900 relative">
                        <!-- Decorative Quote Mark -->
                        <div class="absolute -top-6 left-0 text-7xl font-black text-gray-50 dark:text-zinc-900 pointer-events-none italic">"</div>
                        <div class="relative z-10">
                            <p class="text-sm md:text-base font-black uppercase tracking-widest text-black dark:text-white mb-3 italic">"{{ content.quote || 'Precision isn\'t a goal; it\'s our baseline.' }}"</p>
                            <p class="text-[10px] font-black uppercase tracking-[0.4em] text-primary">{{ content.citation || 'VECTOR: ARTHUR BEAM, FOUNDER' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
