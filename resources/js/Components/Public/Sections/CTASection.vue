<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    content: Object
});
</script>

<template>
    <section class="py-20 bg-[#0b0f19] px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-amber-500 rounded-[3rem] p-16 md:p-32 text-center relative overflow-hidden group">
                <!-- Abstract Pattern -->
                <div class="absolute inset-0 opacity-20 pointer-events-none">
                    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                        <defs><pattern id="ctaGrid" width="60" height="60" patternUnits="userSpaceOnUse"><path d="M 60 0 L 0 0 0 60" fill="none" stroke="white" stroke-width="0.5"/></pattern></defs>
                        <rect width="100%" height="100%" fill="url(#ctaGrid)" />
                    </svg>
                </div>

                <div class="relative z-10 flex flex-col items-center max-w-3xl mx-auto space-y-10 font-sans">
                    <h2 class="text-4xl md:text-7xl font-black text-slate-900 leading-tight tracking-tighter uppercase">Ready to start your next build?</h2>
                    <p class="text-slate-900/70 text-lg font-bold">Get an instant preliminary estimate using our AI-driven cost calculators. Detailed breakdowns of labor, materials, and timeline.</p>
                    
                    <Link :href="route('estimate.index')" 
                        class="bg-white text-slate-900 px-12 py-5 text-[10px] font-black uppercase tracking-[0.3em] rounded-xl shadow-3xl hover:bg-slate-900 hover:text-white transition-all transform hover:-translate-y-2">
                        Launch AI Cost Estimator
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
