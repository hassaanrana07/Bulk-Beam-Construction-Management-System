<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    faq: Object
});

const form = useForm({
    question: props.faq?.question || '',
    answer: props.faq?.answer || '',
    category: props.faq?.category || 'General',
    order: props.faq?.order || 0,
    is_published: props.faq?.is_published ?? true,
    is_public_visible: props.faq?.is_public_visible ?? true,
});

const submit = () => {
    if (props.faq) {
        form.put(route('admin.faqs.update', props.faq.id));
    } else {
        form.post(route('admin.faqs.store'));
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="faq ? 'Edit Intelligence' : 'Deploy New Intel'" />

        <ModuleHeader :title="faq ? 'Edit FAQ' : 'New Intelligence Point'">
            <template #subtitle>
                <Link :href="route('admin.faqs.index')" class="text-[10px] font-black text-zinc-600 hover:text-orange-600 uppercase tracking-[0.3em] flex items-center gap-2 mt-2 transition-colors">
                    <span>←</span> Return to Knowledge Base
                </Link>
            </template>
        </ModuleHeader>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-10">
                <div class="bg-black border border-zinc-900 p-10 space-y-10 shadow-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500 border-b border-zinc-900 pb-4 italic">Intel Geometry</h3>
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2 block">Inquiry Prompt (Question)</label>
                            <input v-model="form.question" type="text" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-sm font-black uppercase tracking-tight text-white transition-all focus:ring-0">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2 block">Response Matrix (Answer)</label>
                            <textarea v-model="form.answer" rows="8" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-sm font-medium tracking-tight text-zinc-300 transition-all focus:ring-0"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-10">
                <div class="bg-black border border-zinc-900 p-10 space-y-10 shadow-2xl sticky top-32">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500 italic">Intel Logic</h3>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2 block">Category Vector</label>
                        <select v-model="form.category" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-white transition-all focus:ring-0">
                            <option>General</option>
                            <option>Services</option>
                            <option>Pricing</option>
                            <option>Compliance</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2 block">Sequence Order</label>
                        <input v-model="form.order" type="number" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-white transition-all focus:ring-0">
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center gap-4 p-5 bg-zinc-950 border border-zinc-900 cursor-pointer hover:border-orange-600 transition-all shadow-[0_0_20px_rgba(234,88,12,0.1)] group">
                            <input type="checkbox" v-model="form.is_public_visible" class="w-5 h-5 bg-black border-zinc-800 text-orange-600 focus:ring-0 rounded-none cursor-pointer">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 group-hover:text-white transition-colors">Web Visibility</span>
                                <span class="text-[8px] text-zinc-600 font-bold uppercase">Live on FAQ Page</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-4 p-5 bg-zinc-950 border border-zinc-900 cursor-pointer hover:border-orange-600 transition-all group">
                            <input type="checkbox" v-model="form.is_published" class="w-5 h-5 bg-black border-zinc-800 text-orange-600 focus:ring-0 rounded-none cursor-pointer">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-400 group-hover:text-white transition-colors">Internal Published</span>
                        </label>
                    </div>

                    <button type="submit" :disabled="form.processing" class="group relative w-full bg-white py-6 overflow-hidden transition-all active:scale-95 disabled:opacity-50">
                        <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.4em] text-black group-hover:text-white transition-colors">
                            {{ form.processing ? 'Deploying...' : 'Archive Intel' }}
                        </span>
                        <div class="absolute inset-0 bg-orange-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    </button>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>
