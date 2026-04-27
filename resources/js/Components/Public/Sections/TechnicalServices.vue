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

// User wants 4 specific services
const services = computed(() => {
    // Attempt to get services from globalData
    const all = props.globalData?.featured_services || [];
    // If not found, show placeholders or the ones we have
    return all.slice(0, 4);
});

const defaultServices = [
    { title: 'Custom Building', slug: 'custom-building', short_description: 'Architectural excellence meets master craftsmanship in our custom home builds.', image: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80' },
    { title: 'Commercial Renovation', slug: 'commercial-renovation', short_description: 'Transforming workspaces into high-performance environments.', image: 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80' },
    { title: 'Quality Renovation', slug: 'quality-renovation', short_description: 'Precision structural overhauls and optimization of existing architectural environments.', image: 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80' },
    { title: 'Structural Design', slug: 'structural-design', short_description: 'Strategic feasibility audits and project management for enterprise clients.', image: 'https://images.unsplash.com/photo-1504917595217-d4dc5f566fab?q=80' }
];

const items = computed(() => {
    if (services.value.length >= 4) return services.value;
    // Fallback to defaults merged with what we have
    const result = [...services.value];
    while (result.length < 4) {
        result.push(defaultServices[result.length]);
    }
    return result;
});

const resolveImage = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return path.startsWith('/') ? path : '/storage/' + path;
};
</script>

<template>
    <section class="py-32 bg-[#0a192f] overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div v-reveal class="reveal reveal-up mb-20">
                <h2 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter mb-4 italic underline decoration-orange-600 decoration-8 underline-offset-12">
                    {{ content?.title || 'Technical Services' }}
                </h2>
                <div class="flex items-center gap-4">
                    <span class="w-12 h-1 bg-orange-600"></span>
                    <p class="text-gray-400 text-xs font-black uppercase tracking-[0.5em]">
                        {{ content?.subtitle || 'Operational Verticals' }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div v-for="(service, index) in items" :key="service.slug" 
                    v-reveal
                    :class="['reveal reveal-up group relative aspect-[4/6] overflow-hidden rounded-2xl border border-white/5 bg-navy-900', `delay-${index * 100}`]">
                    
                    <img :src="resolveImage(service.featured_image) || defaultServices[index]?.image" 
                         class="absolute inset-0 w-full h-full object-cover opacity-60 grayscale group-hover:grayscale-0 group-hover:opacity-100 group-hover:scale-110 transition-all duration-[1.5s]">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-navy-950/40 to-transparent"></div>
                    
                    <div class="absolute inset-0 p-8 flex flex-col justify-end">
                        <div class="mb-4">
                            <span class="text-[8px] font-black text-orange-600 uppercase tracking-widest bg-orange-600/10 px-3 py-1 rounded-full border border-orange-600/20">Segment 0{{ index + 1 }}</span>
                        </div>
                        <h3 class="text-2xl font-black text-white uppercase tracking-tighter mb-4 group-hover:text-orange-500 transition-colors">{{ service.title }}</h3>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-tight leading-relaxed mb-6 opacity-0 group-hover:opacity-100 transition-opacity duration-500 transform translate-y-4 group-hover:translate-y-0 line-clamp-3">
                            {{ service.short_description }}
                        </p>
                        <Link :href="route('services.show', service.slug || '')" class="w-full py-4 text-center bg-white/10 hover:bg-orange-600 backdrop-blur-md text-white border border-white/10 text-[9px] font-black uppercase tracking-widest transition-all rounded-xl">
                            Intelligence Brief
                        </Link>
                    </div>

                    <!-- Industrial Decor -->
                    <div class="absolute top-4 right-4 text-[10px] font-mono text-white/10 group-hover:text-orange-500/30 transition-colors">
                        BB-{{ service.slug?.substring(0,3).toUpperCase() }}-OP
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.bg-navy-900 { background-color: #112240; }
.bg-navy-950 { background-color: #0a192f; }
.from-navy-950 { --tw-gradient-from: #0a192f; }
</style>
