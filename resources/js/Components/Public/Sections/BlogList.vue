<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    content: Object,
    globalData: Object
});

const resolveImage = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    if (path.startsWith('/storage/')) return path;
    if (path.startsWith('storage/')) return '/' + path;
    return '/storage/' + path;
};

const posts = computed(() => props.globalData?.posts?.data || []);
const categories = computed(() => props.globalData?.categories || []);
</script>

<template>
    <section class="py-32 bg-white dark:bg-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-4 gap-20">
            <!-- Sidebar -->
            <aside class="lg:col-span-1 space-y-12">
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Knowledge Nodes</h3>
                    <div class="flex flex-col gap-2">
                        <Link v-for="cat in categories" :key="cat.id" :href="route('insights', { category: cat.slug || '' })" class="text-sm font-bold uppercase tracking-tight text-gray-600 dark:text-gray-400 hover:text-orange-600 transition-colors">
                            {{ cat.name }}
                        </Link>
                    </div>
                </div>
            </aside>

            <!-- Post Grid -->
            <div class="lg:col-span-3 space-y-24">
                <div v-for="(post, index) in posts" :key="post.id" class="group flex flex-col md:flex-row gap-12 items-start pb-24 border-b border-gray-100 dark:border-gray-900 last:border-0 last:pb-0">
                    <div class="w-full md:w-1/3 aspect-[4/3] bg-gray-100 overflow-hidden relative grayscale group-hover:grayscale-0 transition-all duration-700 rounded-lg">
                        <img :src="resolveImage(post.featured_image) || 'https://images.unsplash.com/photo-1541888946425-d81bb19480c5?q=80&w=1200' " class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <div class="inline-block px-3 py-1 bg-black text-white text-[9px] font-black uppercase tracking-[0.3em] rounded-lg">
                                {{ post.category?.name || 'General' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-full md:w-2/3 space-y-6">
                        <div class="space-y-2">
                            <span class="text-[10px] font-black uppercase tracking-widest text-orange-600">{{ new Date(post.published_at).toLocaleDateString() }}</span>
                            <h2 class="text-4xl font-black uppercase tracking-tighter leading-none group-hover:text-orange-600 transition-colors">{{ post.title }}</h2>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-base leading-relaxed line-clamp-3">
                            {{ post.excerpt || (post.content ? post.content.substring(0, 200) + '...' : '') }}
                        </p>
                        <Link :href="route('insights.show', post.slug || '')" class="text-[10px] font-black uppercase tracking-[0.2em] inline-flex items-center gap-2">
                            Full Insight Analysis ⟶
                        </Link>
                    </div>
                </div>
                
                <!-- Pagination (Simplified) -->
                <div class="pt-12 flex justify-center">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">End of Knowledge Matrix</span>
                </div>
            </div>
        </div>
    </section>
</template>
