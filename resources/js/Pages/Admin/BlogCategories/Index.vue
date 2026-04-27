<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    categories: Array
});

const search = ref('');
const filtered = computed(() => {
    if (!search.value) return props.categories;
    return props.categories.filter(c => c.name.toLowerCase().includes(search.value.toLowerCase()));
});

const deleteCategory = (id) => {
    if (confirm('Purge this knowledge category?')) {
        router.delete(route('admin.blog-categories.destroy', id), { preserveScroll: true });
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Knowledge Categories" />

        <ModuleHeader
            title="Categorization Matrix"
            v-model:search="search"
        >
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mt-2">Manage taxonomy nodes for the intelligence base.</p>
            </template>
            <template #actions>
                 <Link 
                    :href="route('admin.blog.index')" 
                    class="px-6 py-3 bg-zinc-900 text-zinc-400 text-[10px] font-black uppercase tracking-[0.2em] hover:text-white transition-all border border-zinc-800 hover:border-orange-600 flex items-center justify-center gap-2 rounded-lg"
                >
                    Insights Feed
                </Link>
                <Link 
                    :href="route('admin.blog-categories.create')" 
                    class="px-6 py-3 bg-orange-600 text-black text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white transition-all shadow-[0_0_20px_rgba(255,107,0,0.3)] active:scale-95 flex items-center justify-center gap-2 rounded-lg"
                >
                    <span>+</span> New Category
                </Link>
            </template>
        </ModuleHeader>

        <div class="bg-black border border-zinc-900 p-10 shadow-2xl rounded-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-zinc-900/50 border-b border-zinc-900">
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">Category Name</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">Slug</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 text-center">Posts</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 text-right">Directives</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-900">
                        <tr v-for="cat in filtered" :key="cat.id" class="hover:bg-zinc-900/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="font-black text-xs uppercase tracking-tight text-white group-hover:text-orange-600 transition-colors">{{ cat.name }}</div>
                                <div v-if="cat.description" class="text-[8px] text-zinc-600 mt-1 font-bold uppercase tracking-wider line-clamp-1">{{ cat.description }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-[9px] font-bold text-zinc-500 tracking-widest">{{ cat.slug }}</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-[10px] font-black text-white">{{ cat.blogs_count }}</span>
                            </td>
                            <td class="px-8 py-6 text-right space-x-4">
                                <Link :href="route('admin.blog-categories.edit', cat.id)" class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-400 hover:text-white transition-colors border-b border-transparent hover:border-orange-600 pb-1">Edit</Link>
                                <button @click="deleteCategory(cat.id)" class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600 hover:text-red-600 transition-colors border-b border-transparent hover:border-red-600 pb-1">Purge</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="filtered.length === 0" class="py-24 flex flex-col items-center justify-center text-center">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600">No knowledge categories defined yet.</p>
                    <Link :href="route('admin.blog-categories.create')" class="mt-6 text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 hover:underline">Create First Category →</Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
