<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    post: Object,
    related_posts: Array
});

const resolveImage = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    if (path.startsWith('/storage/')) return path;
    if (path.startsWith('storage/')) return '/' + path;
    return '/storage/' + path;
};
</script>

<template>
    <PublicLayout :key="$page.url">
        <Head :title="post.title" />

        <div class="pt-32 pb-20 bg-black text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <Link :href="route('insights')" class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 mb-8 inline-block hover:opacity-70 transition-opacity">
                    ← Back to Knowledge Base
                </Link>
                <div class="space-y-6 max-w-4xl">
                    <span class="text-sm font-black uppercase tracking-widest text-gray-500">{{ post.category?.name }}</span>
                    <h1 class="text-6xl md:text-8xl font-black uppercase tracking-tighter leading-none mb-8">
                        {{ post.title }}<span class="text-orange-600">.</span>
                    </h1>
                    <div class="flex items-center gap-6 pt-8 border-t border-white/10">
                        <div class="w-12 h-12 bg-gray-900 overflow-hidden grayscale rounded-lg">
                            <img :src="resolveImage(post.author?.avatar) || 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=100'" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1">Audit By</p>
                            <p class="text-xs font-black uppercase">{{ post.author?.name || 'Vance & Beam Partners' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Image Area (Optional) -->
        <section v-if="post.featured_image" class="h-[60vh] w-full bg-gray-100 overflow-hidden">
            <img :src="resolveImage(post.featured_image)" class="w-full h-full object-cover grayscale">
        </section>

        <section class="py-32 bg-white dark:bg-black">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-24">
                <article class="lg:w-2/3 prose dark:prose-invert max-w-none">
                    <div class="text-gray-600 dark:text-gray-400 text-lg leading-relaxed space-y-8 prose-headings:font-black prose-headings:uppercase prose-headings:tracking-tighter prose-headings:m-0" v-html="post.content"></div>
                </article>
                
                <aside class="lg:w-1/3 space-y-12">
                    <div class="p-10 bg-black text-white space-y-8 rounded-lg">
                        <h3 class="text-xl font-black uppercase tracking-tight text-orange-600">Executive Insight</h3>
                        <p class="text-xs text-gray-400 uppercase tracking-widest leading-relaxed">
                            This analysis represents current industrial standards and technical observations. For project-specific specifications, consult our engineering leads.
                        </p>
                    </div>

                    <div class="space-y-8">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Convergent Research</h3>
                        <div class="space-y-8">
                            <Link v-for="related in related_posts" :key="related.id" :href="route('insights.show', related.slug)" class="group block space-y-2">
                                <span class="text-[10px] font-black uppercase tracking-widest text-orange-600">{{ new Date(related.published_at).toLocaleDateString() }}</span>
                                <h4 class="text-lg font-black uppercase tracking-tight group-hover:text-orange-600 transition-colors leading-tight">{{ related.title }}</h4>
                            </Link>
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </PublicLayout>
</template>
