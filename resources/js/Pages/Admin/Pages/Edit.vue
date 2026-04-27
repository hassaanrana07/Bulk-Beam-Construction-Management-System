<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import SectionBuilder from '@/Components/Admin/SectionBuilder.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    page: Object
});

const form = useForm({
    title: props.page?.title || '',
    slug: props.page?.slug || '',
    status: props.page?.status || 'draft',
    sections: props.page?.sections || [],
});

const submit = () => {
    if (props.page) {
        form.transform(data => ({
            ...data,
            _method: 'PUT'
        })).post(route('admin.pages.update', props.page.id), {
            forceFormData: true,
        });
    } else {
        form.post(route('admin.pages.store'), {
            forceFormData: true,
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="page ? 'Modify Structure' : 'Construct New Page'" />

        <ModuleHeader :title="page ? `Edit Page: ${page.title}` : 'New Page Assembly'">
            <template #subtitle>
                <Link :href="route('admin.pages.index')" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-primary transition-colors mt-2">
                    ← Return to Pages Overview
                </Link>
            </template>
        </ModuleHeader>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            <!-- Content & Sections -->
            <div class="lg:col-span-3 space-y-12">
                <div class="bg-white border border-slate-200 p-10 space-y-10 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 border-b border-slate-100 pb-4 italic">Primary Page Identity</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Administrative Title</label>
                            <input v-model="form.title" type="text" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary text-slate-900 font-black uppercase tracking-tight px-6 py-4 focus:ring-0 transition-all rounded-xl shadow-inner shadow-slate-200/20">
                            <p v-if="form.errors.title" class="text-[9px] font-black text-red-600 mt-2 uppercase tracking-widest">{{ form.errors.title }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Structural Slug (URL Vector)</label>
                            <input v-model="form.slug" type="text" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary text-slate-900 font-bold tracking-tight px-6 py-4 focus:ring-0 transition-all rounded-xl shadow-inner shadow-slate-200/20" placeholder="my-custom-page">
                            <p v-if="form.errors.slug" class="text-[9px] font-black text-red-600 mt-2 uppercase tracking-widest">{{ form.errors.slug }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 p-10 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-6 mb-10">
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 italic">Structural Components</h3>
                        <span class="text-[9px] font-black uppercase tracking-[0.3em] text-primary/50">Total Nodes: {{ form.sections.length }}</span>
                    </div>
                    <SectionBuilder v-model="form.sections" />
                </div>
            </div>

            <!-- Sidebar Controls -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Deployment Status Card -->
                <div class="bg-white border border-slate-200 shadow-2xl sticky top-32 flex flex-col w-full max-w-full overflow-hidden rounded-2xl">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 italic">Deployment Status</h3>
                    </div>
                    
                    <div class="p-6 space-y-10 flex flex-col w-full box-border">
                        <div class="space-y-4 flex flex-col w-full">
                            <label v-for="status in ['draft', 'published', 'archived']" :key="status" 
                                class="flex items-center gap-4 p-4 bg-slate-50 border border-slate-100 cursor-pointer group hover:border-primary/50 transition-all rounded-xl w-full box-border overflow-hidden">
                                <input type="radio" v-model="form.status" :value="status" 
                                    class="w-5 h-5 bg-white border-slate-200 text-primary focus:ring-0 cursor-pointer shrink-0">
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 group-hover:text-primary transition-colors truncate">{{ status }}</span>
                            </label>
                        </div>

                        <div class="space-y-4 flex flex-col w-full">
                            <button type="submit" :disabled="form.processing" class="group relative w-full bg-primary py-6 overflow-hidden transition-all active:scale-95 disabled:opacity-50 rounded-xl shadow-lg shadow-primary/20">
                                <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.4em] text-white">
                                    {{ form.processing ? 'Syncing...' : 'Commit Structure' }}
                                </span>
                                <div class="absolute inset-0 bg-slate-900 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            </button>

                            <a v-if="page" :href="route('pages.show', page.slug)" target="_blank" class="group relative w-full border-2 border-primary py-6 flex items-center justify-center overflow-hidden transition-all active:scale-95 text-center rounded-xl">
                                <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.4em] text-primary group-hover:text-white transition-colors">
                                    Live Preview
                                </span>
                                <div class="absolute inset-0 bg-primary translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            </a>
                        </div>
                        
                        <div v-if="form.recentlySuccessful" class="p-4 bg-green-50 border border-green-100 text-center rounded-xl">
                            <p class="text-[9px] font-black text-green-600 uppercase tracking-[0.2em] animate-pulse">
                                Synchronization Complete
                            </p>
                        </div>
                    </div>
                    
                    <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                        <h4 class="text-[9px] font-black text-primary uppercase tracking-[0.4em] mb-4 text-center">Metadata Analysis</h4>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed text-center italic">
                            Search engine optimization vectors are auto-injected based on primary content segments.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>
