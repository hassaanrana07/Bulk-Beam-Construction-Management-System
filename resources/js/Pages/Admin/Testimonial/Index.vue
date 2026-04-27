<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    testimonials: Array
});

const search = ref('');

const filteredTestimonials = computed(() => {
    if (!search.value) return props.testimonials;
    const lowerSearch = search.value.toLowerCase();
    return props.testimonials.filter(t => 
        t.client_name.toLowerCase().includes(lowerSearch) || 
        (t.client_company && t.client_company.toLowerCase().includes(lowerSearch))
    );
});

const deleteTestimonial = (id) => {
    if (confirm('Delete this testimonial?')) {
        router.delete(route('admin.testimonials.destroy', id));
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Client Feedback" />

        <ModuleHeader 
            title="Testimonials" 
            actionText="Add Feedback" 
            :actionRoute="route('admin.testimonials.create')"
            v-model:search="search"
        >
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mt-2">Verified client feedback and operational endorsements.</p>
            </template>
        </ModuleHeader>

        <div class="bg-black border border-zinc-900 p-10 space-y-8 shadow-2xl rounded-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-zinc-900/50 border-b border-zinc-900">
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">Client Identity</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">Feedback Extract</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500">Status</th>
                            <th class="px-8 py-6 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 text-right">Directives</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-900">
                        <tr v-for="t in filteredTestimonials" :key="t.id" class="hover:bg-zinc-900/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <img v-if="t.client_image" :src="t.client_image" class="w-10 h-10 rounded-lg grayscale group-hover:grayscale-0 transition-all border border-zinc-800">
                                    <div v-else class="w-10 h-10 rounded-lg bg-zinc-900 border border-zinc-800 flex items-center justify-center font-black text-zinc-700 italic">?</div>
                                    <div>
                                        <div class="font-black text-xs uppercase tracking-tight text-white group-hover:text-orange-600 transition-colors">{{ t.client_name }}</div>
                                        <div class="text-[8px] text-zinc-600 font-bold uppercase tracking-[0.2em] mt-1">{{ t.client_company || 'Private Client' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-[10px] text-zinc-400 italic line-clamp-2 max-w-md">"{{ t.testimonial }}"</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div :class="[t.is_published ? 'bg-orange-600' : 'bg-zinc-700', 'w-2 h-2 rounded-full']"></div>
                                    <span class="text-[9px] font-black uppercase tracking-[0.2em]" :class="t.is_published ? 'text-white' : 'text-zinc-500'">{{ t.is_published ? 'Active' : 'Hidden' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right space-x-6">
                                <Link :href="route('admin.testimonials.edit', t.id)" class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-400 hover:text-white transition-colors border-b border-transparent hover:border-orange-600 pb-1">Edit</Link>
                                <button @click="deleteTestimonial(t.id)" class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-600 hover:text-red-600 transition-colors border-b border-transparent hover:border-red-600 pb-1">Purge</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
