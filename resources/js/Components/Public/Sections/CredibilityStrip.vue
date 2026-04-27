<script setup>
import { computed } from 'vue';

const props = defineProps({
    content: Object
});

const defaultStats = [
    { value: '500+', label: 'PROJECTS DELIVERED' },
    { value: '25+', label: 'YEARS EXPERTISE' },
    { value: 'Global', label: 'MARKET REACH' }
];

const stats = computed(() => {
    if (props.content.stats && props.content.stats.length > 0) {
        return props.content.stats;
    }
    return defaultStats;
});
</script>

<template>
    <div class="bg-[#0b1120] py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Partner Logos -->
            <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-30 mb-20">
                <span v-for="logo in ['METROBUILD', 'SKYLINE.CO', 'URBAN_X', 'FORGE_DEV', 'VANTAGE']" 
                      :key="logo" 
                      class="text-xs font-black uppercase tracking-[0.5em] text-white italic">
                    {{ logo }}
                </span>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-24">
                <div v-for="(stat, index) in [
                    { value: '500+', label: 'Verified Projects' },
                    { value: '$1.2B', label: 'Capital Managed' },
                    { value: '15k+', label: 'Stakeholders' },
                    { value: '99.9%', label: 'Project Uplink' }
                ]" :key="index" class="flex flex-col">
                    <span class="text-4xl md:text-5xl font-black tracking-tighter text-amber-500 mb-2">{{ stat.value }}</span>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">{{ stat.label }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
