<script setup>
import { computed } from 'vue';

const props = defineProps({
    content: Object
});

const defaultValues = [
    { title: 'Technical Rigor', desc: 'Every joint, every beam, and every circuit is audited for maximum structural performance.' },
    { title: 'Radical Transparency', desc: 'Real-time project data feeds ensure our clients are never in the dark about progress or budget.' },
    { title: 'Safety as Culture', desc: 'Our zero-incident goal is backed by industrial-grade safety protocols and continuous training.' },
    { title: 'Aesthetic Legacy', desc: 'We build structures that stand the test of time, both physically and architecturally.' }
];

const values = computed(() => {
    if (props.content.values && props.content.values.length > 0) {
        return props.content.values;
    }
    return defaultValues;
});
</script>

<template>
    <section class="py-32 bg-gray-50 dark:bg-navy-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-24">
                <span class="text-xs font-black text-primary uppercase tracking-[0.3em] mb-4 block">{{ content.subtitle || 'Core Directives' }}</span>
                <h2 class="text-5xl md:text-7xl font-black uppercase tracking-tighter leading-none m-0">
                    {{ content.heading || 'Mission &' }} <span class="text-gray-300 dark:text-gray-800">{{ content.subheading || 'Values.' }}</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-px bg-gray-200 dark:bg-gray-800 border border-gray-200 dark:border-gray-800">
                <div v-for="val in values" :key="val.title" class="bg-white dark:bg-navy-900 p-12 hover:bg-primary group transition-all duration-500">
                    <h3 class="text-xl font-black uppercase tracking-tight mb-6 group-hover:text-white transition-colors">{{ val.title }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed group-hover:text-blue-100 transition-colors">
                        {{ val.desc }}
                    </p>
                </div>
            </div>
            
            <div class="mt-20 p-16 bg-navy-900 text-white text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                <h3 class="text-2xl md:text-3xl font-black uppercase tracking-tighter mb-4 relative z-10">{{ content.mission_title || 'Our Mission Statement' }}</h3>
                <p class="text-xl text-primary font-bold uppercase tracking-tight max-w-4xl mx-auto relative z-10">
                    "{{ content.mission_statement || 'To engineer high-performance environments that empower the people and industries they house through technical excellence and architectural integrity.' }}"
                </p>
            </div>
        </div>
    </section>
</template>
