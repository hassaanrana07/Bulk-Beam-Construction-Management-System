<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    pages: Array
});

const search = ref('');

const filteredPages = computed(() => {
    if (!search.value) return props.pages;
    const lowerSearch = search.value.toLowerCase();
    return props.pages.filter(page => 
        page.title.toLowerCase().includes(lowerSearch) || 
        page.slug.toLowerCase().includes(lowerSearch)
    );
});

const deletePage = (id) => {
    if (confirm('Deconstruct this structural node permanently?')) {
        router.delete(route('admin.pages.destroy', id), {
            preserveScroll: true,
            preserveState: true,
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Structural Matrix" />

        <ModuleHeader 
            title="CMS Pages" 
            actionText="Construct New Page" 
            :actionRoute="route('admin.pages.create')"
            v-model:search="search"
        >
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mt-2">Managing structural assets and informational terminals for the public matrix.</p>
            </template>
        </ModuleHeader>

        <div class="bg-white border border-slate-200 shadow-xl shadow-slate-200/50 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left table-fixed">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic w-1/3">Deployment Node (Title)</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic w-1/4">Sector (Slug)</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic w-1/6">Status</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 italic text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="page in filteredPages" :key="page.id" class="group hover:bg-primary/[0.02] transition-colors">
                            <td class="px-8 py-6">
                                <Link :href="route('admin.pages.edit', page.id)" class="text-sm font-black text-slate-900 uppercase tracking-tighter group-hover:text-primary transition-colors italic block truncate">
                                    {{ page.title }}
                                </Link>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-2">NODE ID: {{ page.id.toString().padStart(4, '0') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-[10px] font-black text-primary font-sans tracking-tight truncate block bg-primary/5 px-3 py-1 rounded inline-block">/{{ page.slug }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span :class="[
                                    page.status === 'published' ? 'text-green-600 border-green-200 bg-green-50' : 'text-primary border-primary/20 bg-primary/5',
                                    'px-3 py-1 text-[9px] font-black uppercase tracking-[0.1em] border inline-block rounded-lg'
                                ]">{{ page.status }}</span>
                            </td>
                            <td class="px-8 py-6 text-right space-x-6">
                                <Link :href="route('admin.pages.edit', page.id)" class="text-[9px] font-black text-slate-600 hover:text-primary uppercase tracking-widest transition-colors">Modify</Link>
                                <button @click="deletePage(page.id)" class="text-[9px] font-black text-slate-300 hover:text-red-600 uppercase tracking-widest transition-colors">Purge</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div v-if="filteredPages.length === 0" class="py-24 text-center border-t border-slate-100 bg-slate-50/30">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 mb-4">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <p class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400">No organizational nodes detected.</p>
            </div>
        </div>
        
        <div class="mt-12 flex justify-between items-center text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">
            <span>SYSTEM LOG: STRUCTURAL_INTEGRITY_CHECK_PASS</span>
            <span>V.12.4.0</span>
        </div>
    </AdminLayout>
</template>
