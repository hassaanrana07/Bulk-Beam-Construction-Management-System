<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    content: Object,
    globalData: Object
});

const services = computed(() => props.globalData?.services || []);
</script>

<template>
    <section class="py-32 bg-white dark:bg-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-px bg-gray-200 dark:bg-gray-800 border border-gray-200 dark:border-gray-800">
                <div v-for="service in services" :key="service.id" 
                    class="bg-white dark:bg-black p-16 hover:bg-gray-50 dark:hover:bg-gray-950 transition-all duration-500 group relative">
                    <div class="mb-12">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest block mb-4">SEGMENT 0{{ service.id }}</span>
                        <h2 class="text-3xl font-black uppercase tracking-tighter leading-none">{{ service.title }}</h2>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed mb-12">
                        {{ service.short_description }}
                    </p>
                    <Link :href="route('services.show', service.slug || '')" class="inline-flex items-center gap-4 text-xs font-black uppercase tracking-widest text-orange-600 group-hover:gap-6 transition-all duration-500 font-bold">
                        View Case Studies 
                        <span class="w-12 h-px bg-orange-600"></span>
                    </Link>
                    
                    <!-- Industrial Accent -->
                    <div class="absolute bottom-0 left-0 w-0 h-1 bg-orange-600 group-hover:w-full transition-all duration-700"></div>
                </div>
            </div>
        </div>
    </section>
</template>
