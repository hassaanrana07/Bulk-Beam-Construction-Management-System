<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SectionRenderer from '@/Components/SectionRenderer.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    page: Object,
    team: Array
});

const pageData = usePage().props;

const globalData = computed(() => ({
    featured_services: pageData.featured_services,
    featured_projects: pageData.featured_projects,
    testimonials: pageData.testimonials,
    team: props.team,
    certifications: usePage().props.certifications
}));
</script>

<template>
    <PublicLayout :key="$page.url">
        <Head :title="page.title" />

        <SectionRenderer 
            :sections="page.sections" 
            :data="globalData"
        />
        
        <!-- Fallback for empty pages -->
        <div v-if="!page.sections || page.sections.length === 0" class="py-32 bg-white dark:bg-black">
            <div class="max-w-3xl mx-auto px-4 text-center">
                <h1 class="text-4xl font-black uppercase tracking-tighter mb-4">{{ page.title }}</h1>
                <p class="text-gray-500 uppercase tracking-widest text-xs font-bold">This section is currently under structural development.</p>
            </div>
        </div>
    </PublicLayout>
</template>
