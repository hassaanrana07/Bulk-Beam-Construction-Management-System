<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const projects = computed(() => {
    const all = page.props.projects || [];
    return all.filter(p => p.is_featured);
});

const props = defineProps({
    content: Object
});

const resolveImage = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    if (path.startsWith('/storage/')) return path;
    if (path.startsWith('storage/')) return '/' + path;
    return '/storage/' + path;
};

const defaultImages = [
    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070', // Modern Skyscraper
    'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=2071', // Structural Steel
    'https://images.unsplash.com/photo-1590644365607-1c5a519a7a37?q=80&w=2070', // Geometric Concrete
];
</script>

<template>
    <section class="py-32 bg-[#0b0f19] overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div v-reveal class="reveal reveal-up mb-16">
                <h2 class="text-3xl font-black text-white uppercase tracking-tight mb-4">Featured Projects</h2>
            </div>

            <!-- Asymmetric Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 h-[700px]">
                <!-- Huge Feature -->
                <div v-reveal class="reveal reveal-left relative group overflow-hidden rounded-[2.5rem] border border-white/5">
                    <img :src="resolveImage(projects[0]?.featured_image) || 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop'" 
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110" 
                         alt="Featured Project">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0b0f19] via-transparent to-transparent opacity-80"></div>
                    <div class="absolute bottom-10 left-10">
                        <h3 class="text-3xl font-black text-white uppercase tracking-tighter mb-2">{{ projects[0]?.title || 'Metropolitan Hub' }}</h3>
                        <p class="text-amber-500 text-[10px] font-black uppercase tracking-[0.3em]">Signature Development</p>
                    </div>
                    <Link v-if="projects[0]" :href="route('portfolio.show', projects[0].slug || '')" class="absolute inset-0 z-10"></Link>
                </div>

                <!-- Collection of 3 -->
                <div class="grid grid-rows-3 gap-8">
                    <div v-for="(project, index) in (projects.length > 1 ? projects.slice(1, 4) : [
                        { id: 2, title: 'Coastal Estate', image: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=2070' },
                        { id: 3, title: 'Industrial Complex', image: 'https://images.unsplash.com/photo-1486325212027-8081e485255e?q=80&w=2070' },
                        { id: 4, title: 'Modern Workspace', image: 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2070' }
                    ])" 
                    :key="index" 
                    v-reveal :class="['reveal reveal-right relative group overflow-hidden rounded-[2rem] border border-white/5', `delay-${index * 150}`]">
                        <img :src="resolveImage(project.featured_image) || project.image || defaultImages[index % defaultImages.length]" 
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110" 
                             :alt="project.title">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b0f19]/80 to-transparent"></div>
                        <div class="absolute bottom-6 left-8">
                            <h4 class="text-xl font-black text-white uppercase tracking-tight">{{ project.title }}</h4>
                        </div>
                        <Link v-if="project.slug" :href="route('portfolio.show', project.slug)" class="absolute inset-0 z-10"></Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
