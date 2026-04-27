<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    content: Object,
    globalData: {
        type: Object,
        default: () => ({})
    }
});

const projects = computed(() => props.globalData.projects || []);

const categories = [
    {
        title: 'Residential',
        icon: 'M12 3l-8 7V21h16V10l-8-7zm6 16H6v-8l6-5.25L18 11v8z',
        description: 'Custom luxury homes and high-end residential developments designed for premium living.',
        image: 'https://images.unsplash.com/photo-1600585154340-be6191da95b4?q=80&w=2070',
        delay: '0'
    },
    {
        title: 'Commercial',
        icon: 'M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm4 8H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm4 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V9h2v2zm4 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V9h2v2z',
        description: 'Scale business complexes and high-rise environments built for operational growth.',
        image: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070',
        delay: '100'
    },
    {
        title: 'Industrial',
        icon: 'M22 21V9l-8-4-8 4v12h16zM6 19v-8l6-3 6 3v8H6zm2-8h8v2H8v-2zm0 4h8v2H8v-2z',
        description: 'Advanced logistics hubs and manufacturing facilities housing state-of-the-art infrastructure.',
        image: 'https://images.unsplash.com/photo-1516937941344-00b4e0337589?q=80&w=2070',
        delay: '200'
    }
];

const getProjectsByCategory = (type) => {
    return projects.value.filter(p => p.project_type?.toLowerCase() === type.toLowerCase());
};

const resolveImage = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return path.startsWith('/') ? path : '/storage/' + path;
};
</script>

<template>
    <section class="py-32 bg-[#0b0f19] overflow-hidden relative border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 font-sans">
            <div v-reveal class="reveal reveal-up mb-20 flex flex-col md:flex-row justify-between items-start md:items-end gap-10">
                <div class="max-w-2xl">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-8 h-px bg-amber-500"></span>
                        <span class="text-amber-500 text-[10px] font-black uppercase tracking-[0.4em]">Core Competence</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-white leading-tight tracking-tighter uppercase">Specialized solutions for every structural challenge</h2>
                </div>
                <Link :href="route('services')" class="text-amber-500 text-[10px] font-black uppercase tracking-[0.3em] flex items-center gap-3 group transition-all hover:text-white">
                    Browse all Services
                    <svg class="w-4 h-4 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </Link>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="(cat, index) in categories" :key="cat.title" 
                    v-reveal :class="['reveal reveal-up group p-10 bg-slate-900/40 border border-white/5 rounded-[2rem] hover:bg-slate-900/60 hover:border-amber-500/30 transition-all duration-500 flex flex-col h-full transform hover:-translate-y-2', `delay-${index * 100}`]">
                    <!-- Icon Box -->
                    <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center shadow-2xl shadow-amber-500/20 mb-10 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path :d="cat.icon" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </div>

                    <h3 class="text-2xl font-black text-white uppercase tracking-tight mb-6">{{ cat.title }}</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed mb-8">{{ cat.description }}</p>

                    <!-- List Items -->
                    <ul class="space-y-4 mb-10 mt-auto">
                        <li v-for="item in ['Structural Engineering', 'Site Management']" :key="item" class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-300">{{ item }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>
