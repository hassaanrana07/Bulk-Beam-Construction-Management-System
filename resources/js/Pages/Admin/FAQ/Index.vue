<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    faqs: Array
});

const search = ref('');

const filteredFaqs = computed(() => {
    if (!search.value) return props.faqs;
    const lowerSearch = search.value.toLowerCase();
    return props.faqs.filter(f => 
        f.question.toLowerCase().includes(lowerSearch) || 
        f.answer.toLowerCase().includes(lowerSearch)
    );
});

const deleteFaq = (id) => {
    if (confirm('Delete this intelligence point?')) {
        router.delete(route('admin.faqs.destroy', id));
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Intelligence Base" />

        <ModuleHeader 
            title="FAQs Module" 
            actionText="Add Intel" 
            :actionRoute="route('admin.faqs.create')"
            v-model:search="search"
        >
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mt-2">Knowledge base for client inquiries and procedural clarifications.</p>
            </template>
        </ModuleHeader>

        <div class="bg-black border border-zinc-900 p-10 space-y-8 shadow-2xl rounded-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-zinc-900/50 border-b border-zinc-900">
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 w-1/3">Inquiry (Question)</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">Response (Answer)</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 text-right">Directives</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-900">
                        <tr v-for="faq in filteredFaqs" :key="faq.id" class="hover:bg-zinc-900/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="font-black text-xs uppercase tracking-tight text-white group-hover:text-orange-600 transition-colors">{{ faq.question }}</div>
                                <div class="text-[8px] text-zinc-600 font-bold uppercase tracking-[0.2em] mt-1">{{ faq.category || 'GENERAL' }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-[10px] text-zinc-400 line-clamp-2">{{ faq.answer }}</p>
                            </td>
                            <td class="px-8 py-6 text-right space-x-6 whitespace-nowrap">
                                <Link :href="route('admin.faqs.edit', faq.id)" class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-400 hover:text-white transition-colors border-b border-transparent hover:border-orange-600 pb-1">Edit</Link>
                                <button @click="deleteFaq(faq.id)" class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600 hover:text-red-600 transition-colors border-b border-transparent hover:border-red-600 pb-1">Purge</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
