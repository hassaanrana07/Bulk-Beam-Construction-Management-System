<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    category: Object
});

const form = useForm({
    name:        props.category?.name || '',
    slug:        props.category?.slug || '',
    description: props.category?.description || '',
});

const submit = () => {
    if (props.category) {
        form.put(route('admin.blog-categories.update', props.category.id));
    } else {
        form.post(route('admin.blog-categories.store'));
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="category ? 'Edit Category' : 'New Knowledge Category'" />

        <ModuleHeader :title="category ? `Category: ${category.name}` : 'New Knowledge Category'">
            <template #subtitle>
                <Link :href="route('admin.blog-categories.index')" class="text-[10px] font-black text-zinc-600 hover:text-orange-600 uppercase tracking-[0.3em] flex items-center gap-2 mt-2 transition-colors">
                    <span>←</span> Return to Categories
                </Link>
            </template>
        </ModuleHeader>

        <div class="max-w-2xl">
            <form @submit.prevent="submit" class="bg-black border border-zinc-900 p-10 shadow-2xl rounded-lg space-y-8">
                <h3 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500 border-b border-zinc-900 pb-4 italic">Category Details</h3>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 block">Category Name</label>
                    <input v-model="form.name" type="text" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-sm font-black uppercase tracking-tight text-white transition-all focus:ring-0 rounded-lg" placeholder="e.g. Structural Engineering">
                    <p v-if="form.errors.name" class="text-red-500 text-[9px] font-black uppercase">{{ form.errors.name }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 block">Slug (URL)</label>
                    <input v-model="form.slug" type="text" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-xs font-bold tracking-tight text-white transition-all focus:ring-0 rounded-lg placeholder-zinc-800" placeholder="auto-generated from name">
                    <p v-if="form.errors.slug" class="text-red-500 text-[9px] font-black uppercase">{{ form.errors.slug }}</p>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 block">Description (Optional)</label>
                    <textarea v-model="form.description" rows="3" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-sm font-medium tracking-tight text-zinc-300 transition-all focus:ring-0 rounded-lg"></textarea>
                </div>

                <button type="submit" :disabled="form.processing" class="group relative w-full bg-white py-5 overflow-hidden transition-all active:scale-95 disabled:opacity-50 rounded-lg">
                    <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.4em] text-black group-hover:text-white transition-colors">
                        {{ form.processing ? 'Saving...' : (category ? 'Update Category' : 'Create Category') }}
                    </span>
                    <div class="absolute inset-0 bg-orange-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
