<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SectionRenderer from '@/Components/SectionRenderer.vue';
import LeadSection from '@/Components/Public/Sections/LeadSection.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    page: Object,
    featured_services: Array,
    projects: Array,
    testimonials: Array
});
</script>

<template>
    <PublicLayout :key="$page.url">
        <Head :title="props.page.title" />

        <SectionRenderer 
            :sections="props.page.sections" 
            :data="{
                featured_services: props.featured_services,
                projects: props.projects,
                testimonials: props.testimonials
            }"
        />

    </PublicLayout>
</template>
