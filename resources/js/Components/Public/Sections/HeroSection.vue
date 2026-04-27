<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    content: Object
});

const resolveImage = (path, url = null) => {
    if (url) return url;
    if (!path) return null;
    if (path.startsWith('http')) return path;
    if (path.startsWith('/storage/')) return path;
    if (path.startsWith('storage/')) return '/' + path;
    return '/storage/' + path;
};
</script>

<template>
    <section class="min-h-screen relative flex items-center bg-[#0b0f19] overflow-hidden pt-20 pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Text Content -->
                <div v-reveal class="reveal reveal-left space-y-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-amber-500/10 border border-amber-500/20 rounded-full">
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                        <span class="text-amber-500 text-[10px] font-black uppercase tracking-[0.3em]">Engineering the Future</span>
                    </div>
                    
                    <h1 class="text-6xl md:text-8xl font-black text-white leading-none tracking-tighter">
                       Building Excellence, <br>
                       <span class="text-amber-500 italic block mt-4">Delivering Trust</span>
                    </h1>
                    
                    <p class="text-lg text-slate-400 leading-relaxed max-w-xl font-medium">
                        Streamline your construction lifecycle with an enterprise-grade platform built for modern developers. Manage estimates, workflows, and stakeholders in one centralized dashboard.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-5 pt-6">
                        <Link :href="content.primary_button_link || route('portfolio')" class="px-10 py-5 bg-amber-500 text-slate-900 font-black uppercase tracking-widest text-xs rounded-xl shadow-2xl shadow-amber-500/20 hover:bg-white transition-all transform hover:-translate-y-1">
                            Explore Projects
                        </Link>
                        
                        <Link :href="content.secondary_button_link || route('about')" class="px-10 py-5 bg-white/5 border border-white/10 text-white font-black uppercase tracking-widest text-xs rounded-xl hover:bg-white/10 transition-all transform hover:-translate-y-1">
                            Our Methodology
                        </Link>
                    </div>
                </div>

                <!-- Featured Image -->
                <div v-reveal class="reveal reveal-right relative group">
                    <div class="absolute -inset-4 bg-amber-500/20 blur-3xl rounded-full opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    <div class="relative rounded-[2rem] overflow-hidden border border-white/10 shadow-3xl aspect-[4/5] lg:aspect-[3/4]">
                        <img :src="resolveImage(content.image, content.image_url) || 'https://images.unsplash.com/photo-1541888946425-d81bb19480c5?q=80&w=2070&auto=format&fit=crop'" 
                             class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-1000" 
                             alt="Building Excellence">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b0f19] via-transparent to-transparent opacity-60"></div>
                        
                        <!-- Mini Dashboard Overlay -->
                        <div class="absolute bottom-10 left-10 p-6 bg-slate-900/40 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl max-w-xs transition-transform transform group-hover:translate-y-[-10px] duration-500">
                             <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[8px] font-black uppercase tracking-widest text-slate-400">OPTIMIZED ROI</span>
                                    <span class="text-xl font-black text-white italic">+42% YoY</span>
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
@keyframes pulse-slow {
    0%, 100% { opacity: 0.3; transform: scale(1.1); }
    50% { opacity: 0.4; transform: scale(1.15); }
}
.animate-pulse-slow {
    animation: pulse-slow 20s infinite ease-in-out;
}
</style>
