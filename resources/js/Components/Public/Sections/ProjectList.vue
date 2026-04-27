<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    content: Object,
    globalData: Object
});

const projects = computed(() => props.globalData?.projects || []);

const resolveImage = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    if (path.startsWith('/storage/')) return path;
    if (path.startsWith('storage/')) return '/' + path;
    return '/storage/' + path;
};

const defaultImages = [
    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=2071',
];
</script>

<template>
    <section class="py-32 bg-[#0b0f19]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                <div v-for="(project, index) in projects" :key="project.id" class="group">
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-900 rounded-[2rem] border border-white/5 mb-8">
                        <img :src="resolveImage(project.featured_image) || defaultImages[index % defaultImages.length]" 
                                class="w-full h-full object-cover transition-all duration-1000 group-hover:scale-110" 
                                :alt="project.title">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b0f19] via-transparent to-transparent opacity-60"></div>
                        <div class="absolute bottom-6 left-6 flex flex-wrap gap-2">
                            <span class="bg-amber-500 text-slate-900 text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-lg">{{ project.project_type }}</span>
                            <span class="bg-black/50 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-lg">{{ project.location || 'Undisclosed' }}</span>
                        </div>
                    </div>
                    <h2 class="text-3xl font-black text-white uppercase tracking-tighter mb-4 group-hover:text-amber-500 transition-colors">{{ project.title }}</h2>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed mb-8 line-clamp-2">
                        {{ project.short_description }}
                    </p>
                    <Link :href="route('portfolio.show', project.slug || '')" class="text-[10px] font-black uppercase tracking-[0.3em] text-amber-500 inline-flex items-center gap-3 group-hover:gap-5 transition-all">
                        Audit Project Analysis
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
