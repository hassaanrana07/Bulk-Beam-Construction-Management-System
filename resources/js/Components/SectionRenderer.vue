<template>
    <div class="dynamic-sections">
        <component 
            v-for="(section, index) in sections" 
            :key="index"
            :is="sectionComponents[section.type]"
            :content="section.content"
            :global-data="data"
        />
    </div>
</template>

<script setup>
import { computed, defineAsyncComponent } from 'vue';

const props = defineProps({
    sections: {
        type: Array,
        required: true
    },
    data: {
        type: Object,
        default: () => ({})
    }
});

// Map section types to component files
const sectionComponents = {
    hero: defineAsyncComponent(() => import('@/Components/Public/Sections/HeroSection.vue')),
    credibility: defineAsyncComponent(() => import('@/Components/Public/Sections/CredibilityStrip.vue')),
    services_overview: defineAsyncComponent(() => import('@/Components/Public/Sections/ServicesOverview.vue')),
    featured_projects: defineAsyncComponent(() => import('@/Components/Public/Sections/FeaturedProjects.vue')),
    why_choose_us: defineAsyncComponent(() => import('@/Components/Public/Sections/WhyChooseUs.vue')),
    certifications: defineAsyncComponent(() => import('@/Components/Public/Sections/Certifications.vue')),
    before_after: defineAsyncComponent(() => import('@/Components/Public/Sections/BeforeAfter.vue')),
    testimonials: defineAsyncComponent(() => import('@/Components/Public/Sections/TestimonialsSection.vue')),
    cta: defineAsyncComponent(() => import('@/Components/Public/Sections/CTASection.vue')),
    story: defineAsyncComponent(() => import('@/Components/Public/Sections/StorySection.vue')),
    mission_values: defineAsyncComponent(() => import('@/Components/Public/Sections/MissionValues.vue')),
    leadership: defineAsyncComponent(() => import('@/Components/Public/Sections/Leadership.vue')),
    timeline: defineAsyncComponent(() => import('@/Components/Public/Sections/Timeline.vue')),
    safety: defineAsyncComponent(() => import('@/Components/Public/Sections/SafetyStandards.vue')),
    service_list: defineAsyncComponent(() => import('@/Components/Public/Sections/ServiceList.vue')),
    project_list: defineAsyncComponent(() => import('@/Components/Public/Sections/ProjectList.vue')),
    blog_list: defineAsyncComponent(() => import('@/Components/Public/Sections/BlogList.vue')),
    lead_section: defineAsyncComponent(() => import('@/Components/Public/Sections/LeadSection.vue')),
    technical_services: defineAsyncComponent(() => import('@/Components/Public/Sections/TechnicalServices.vue')),
};
</script>

<style scoped>
.dynamic-sections {
    width: 100%;
}
</style>
