<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SectionRenderer from '@/Components/SectionRenderer.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    projects: Array,
    page: Object
});
</script>

<template>
    <PublicLayout :key="$page.url">
        <Head title="Architectural Archive" />

        <div v-if="page && page.sections && page.sections.length > 0">
            <SectionRenderer 
                :sections="page.sections" 
                :data="{ projects: projects }"
            />
        </div>

        <div v-else>
            <div class="pt-32 pb-20 bg-black text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <span class="text-xs font-black text-orange-600 uppercase tracking-[0.3em] mb-4 block">Archive Library</span>
                    <h1 class="text-6xl md:text-8xl font-black uppercase tracking-tighter leading-none mb-8">
                        Portfolio<span class="text-orange-600">.</span>
                    </h1>
                    <p class="text-xl text-gray-400 max-w-2xl">
                        A permanent record of technical integrity, architectural legacy, and high-performance execution.
                    </p>
                </div>
            </div>

            <section class="py-32 bg-white dark:bg-black">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div v-for="project in projects" :key="project.id" class="group">
                            <div class="relative aspect-[16/10] overflow-hidden bg-gray-100 mb-8 rounded-lg">
                                <img :src="project.featured_image || 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop'" 
                                     class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-1000 group-hover:scale-105" 
                                     :alt="project.title">
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-all"></div>
                                <div class="absolute bottom-6 left-6 flex flex-wrap gap-2">
                                    <span class="bg-orange-600 text-white text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg">{{ project.project_type }}</span>
                                    <span class="bg-black text-white text-[9px] font-black uppercase tracking-widest px-3 py-1.5 opacity-80 rounded-lg">{{ project.location || 'Undisclosed' }}</span>
                                    <span :class="[
                                        project.execution_status === 'Completed' ? 'bg-green-600' : 'bg-zinc-800',
                                        'text-white text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg'
                                    ]">{{ project.execution_status }}</span>
                                </div>
                            </div>
                            <h2 class="text-3xl font-black uppercase tracking-tighter mb-4 group-hover:text-orange-600 transition-colors">{{ project.title }}</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed mb-6 h-12 overflow-hidden">
                                {{ project.short_description }}
                            </p>
                            <Link :href="route('portfolio.show', project.slug)" class="text-[10px] font-black uppercase tracking-[0.2em] inline-flex items-center gap-2 hover:gap-4 transition-all">
                                View Case Study ⟶
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>
