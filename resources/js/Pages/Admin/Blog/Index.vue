<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    posts: Array
});

const search = ref('');

const filteredPosts = computed(() => {
    if (!search.value) return props.posts;
    const lowerSearch = search.value.toLowerCase();
    return props.posts.filter(post => 
        post.title.toLowerCase().includes(lowerSearch) || 
        (post.knowledge_category && post.knowledge_category.name.toLowerCase().includes(lowerSearch))
    );
});

const deletePost = (id) => {
    if (confirm('Delete this insight permanently?')) {
        router.delete(route('admin.blog.destroy', id), {
            preserveScroll: true,
            preserveState: true,
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Insight Matrix" />

        <ModuleHeader 
            title="Blog Suite" 
            v-model:search="search"
        >
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mt-2">Centralized Knowledge Hub & Categorization Matrix.</p>
            </template>
            <template #actions>
                 <Link 
                    :href="route('admin.blog-categories.index')" 
                    class="px-6 py-3 bg-zinc-900 text-zinc-400 text-[10px] font-black uppercase tracking-[0.2em] hover:text-white transition-all border border-zinc-800 hover:border-orange-600 flex items-center justify-center gap-2 rounded-lg"
                >
                    Categorization
                </Link>
                <Link 
                    :href="route('admin.blog.create')" 
                    class="px-6 py-3 bg-orange-600 text-black text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white transition-all shadow-[0_0_20px_rgba(255,107,0,0.3)] active:scale-95 flex items-center justify-center gap-2 rounded-lg"
                >
                    <span>+</span> Publish Insight
                </Link>
            </template>
        </ModuleHeader>

        <div class="bg-black border border-zinc-900 overflow-hidden shadow-2xl rounded-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-zinc-900/50 border-b border-zinc-900">
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">Post Identity</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">Category Node</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">Publication Status</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">Engagement Metrics</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 text-right">Directives</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-900">
                        <tr v-for="post in filteredPosts" :key="post.id" class="hover:bg-zinc-900/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="font-black text-xs uppercase tracking-tight text-white group-hover:text-orange-600 transition-colors truncate max-w-sm">{{ post.title }}</div>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-800"></span>
                                    <div class="text-[8px] text-zinc-600 font-bold uppercase tracking-[0.2em]">Authored by {{ post.author?.name || 'System Core' }}</div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-[8px] font-black text-black bg-orange-600 px-3 py-1 uppercase tracking-widest inline-block skew-x-[-10deg]">{{ post.knowledge_category?.name || 'General Vector' }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div :class="[
                                        post.status === 'published' ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.4)]' : 'bg-zinc-700',
                                        'w-2 h-2 rounded-full'
                                    ]"></div>
                                    <span :class="[
                                        post.status === 'published' ? 'text-white' : 'text-zinc-500',
                                        'text-[9px] font-black uppercase tracking-[0.2em]'
                                    ]">{{ post.status }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-black text-white tracking-tighter">{{ post.views || 0 }}</span>
                                    <span class="text-[8px] font-bold text-zinc-600 uppercase tracking-widest">Visual Impressions</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right space-x-6">
                                <Link :href="route('admin.blog.edit', post.id)" class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-400 hover:text-white transition-colors border-b border-transparent hover:border-orange-600 pb-1">Modify</Link>
                                <button @click="deletePost(post.id)" class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600 hover:text-red-600 transition-colors border-b border-transparent hover:border-red-600 pb-1">Purge</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Empty State -->
            <div v-if="filteredPosts.length === 0" class="py-24 flex flex-col items-center justify-center text-center">
                <div class="w-12 h-12 bg-zinc-900/50 flex items-center justify-center mb-6 border border-zinc-900">
                    <span class="text-zinc-700 text-2xl font-black italic">?</span>
                </div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600">No signals detected in knowledge base.</p>
            </div>
        </div>
    </AdminLayout>
</template>
