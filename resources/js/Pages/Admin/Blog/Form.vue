<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    post: Object,
    categories: Array
});

const form = useForm({
    title: props.post?.title || '',
    slug: props.post?.slug || '',
    knowledge_category_id: props.post?.knowledge_category_id || '',
    content: props.post?.content || '',
    excerpt: props.post?.excerpt || '',
    status: props.post?.status || 'published',
    is_featured: props.post?.is_featured || false,
    featured_image: null,
    image_url: props.post?.image_url || '',
    author_name: props.post?.author_name || '',
    author_role: props.post?.author_role || '',
    tags: props.post?.tags || '',
    seo_title: props.post?.seo_title || '',
    seo_description: props.post?.seo_description || '',
    reading_time: props.post?.reading_time || '',
    is_public_visible: props.post?.is_public_visible ?? true,
});

const imagePreview = ref(null);
const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.featured_image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    if (props.post) {
        form.transform(data => ({
            ...data,
            _method: 'PUT'
        })).post(route('admin.blog.update', props.post.id), {
            forceFormData: true
        });
    } else {
        form.post(route('admin.blog.store'), {
            forceFormData: true
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="post ? 'Modify Insight' : 'Publish New Insight'" />

        <ModuleHeader :title="post ? `Edit: ${post.title}` : 'New Knowledge Post'">
            <template #subtitle>
                <Link :href="route('admin.blog.index')" class="text-[10px] font-black text-slate-600 hover:text-orange-600 uppercase tracking-[0.3em] flex items-center gap-2 mt-2 transition-colors">
                    <span>←</span> Return to Knowledge Matrix
                </Link>
            </template>
        </ModuleHeader>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-10">
                <div class="bg-white border border-slate-200 p-10 space-y-10 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 border-b border-slate-100 pb-4 italic">Editorial Content</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Headline</label>
                            <input v-model="form.title" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-sm font-black uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                            <p v-if="form.errors.title" class="text-red-500 text-[9px] font-black uppercase mt-1">{{ form.errors.title }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Knowledge Category</label>
                                    <Link :href="route('admin.blog-categories.index')" class="text-[8px] font-black uppercase tracking-widest text-orange-600 hover:underline">+ Manage</Link>
                                </div>
                                <select v-model="form.knowledge_category_id" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                                    <option value="">Select Category Node</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                                <p v-if="form.errors.knowledge_category_id" class="text-red-500 text-[9px] font-black uppercase mt-1">{{ form.errors.knowledge_category_id }}</p>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block tracking-[0.4em]">Editorial Visualization</label>
                                <div class="flex items-center gap-6">
                                    <div class="w-24 h-24 bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center rounded-xl shadow-inner">
                                        <img v-if="imagePreview || form.image_url || post?.featured_image" :src="imagePreview || form.image_url || post?.featured_image" class="w-full h-full object-cover">
                                        <span v-else class="text-slate-200 text-3xl font-black italic">?</span>
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <label class="block px-4 py-2 bg-white border border-slate-200 hover:border-orange-600 text-slate-500 hover:text-orange-600 text-[9px] font-black uppercase tracking-widest cursor-pointer transition-all text-center rounded-lg shadow-sm">
                                            Upload File
                                            <input type="file" @change="handleImageChange" class="hidden" accept="image/*">
                                        </label>
                                        <div class="relative">
                                            <input v-model="form.image_url" type="text" placeholder="Or paste Image URL..." class="w-full bg-slate-50 border border-slate-100 px-3 py-2 text-[9px] text-slate-500 focus:border-orange-600 focus:ring-0 rounded-lg font-bold uppercase tracking-tighter">
                                        </div>
                                    </div>
                                </div>
                                <p v-if="form.errors.featured_image" class="text-red-500 text-[9px] font-black uppercase mt-1">{{ form.errors.featured_image }}</p>
                                <p v-if="form.errors.image_url" class="text-red-500 text-[9px] font-black uppercase mt-1">{{ form.errors.image_url }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                             <div>
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Author Name</label>
                                <input v-model="form.author_name" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl" placeholder="Display Name">
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Author Role</label>
                                <input v-model="form.author_role" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl" placeholder="e.g. Lead Engineer">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="md:col-span-2">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">URL Slug</label>
                                <input v-model="form.slug" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold tracking-tight text-slate-900 transition-all focus:ring-0 placeholder-slate-300 rounded-xl" placeholder="auto-generated">
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Reading Time</label>
                                <input v-model="form.reading_time" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl" placeholder="e.g. 5 min">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Short Description</label>
                        <textarea v-model="form.excerpt" rows="2" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-sm font-medium tracking-tight text-slate-700 transition-all focus:ring-0 rounded-xl"></textarea>
                    </div>

                    <div class="space-y-4 pt-6 border-t border-slate-100">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-orange-600 italic">SEO Strategy</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 mb-1 block">SEO Title</label>
                                <input v-model="form.seo_title" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-3 text-xs text-slate-900 transition-all focus:ring-0 rounded-lg">
                            </div>
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 mb-1 block">SEO Description</label>
                                <textarea v-model="form.seo_description" rows="2" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-3 text-xs text-slate-600 transition-all focus:ring-0 rounded-lg"></textarea>
                            </div>
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 mb-1 block">Tags (Comma Separated)</label>
                                <input v-model="form.tags" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-3 text-xs text-slate-900 transition-all focus:ring-0 rounded-lg" placeholder="construction, tech, architecture">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Post Narrative (Full Content)</label>
                        <textarea v-model="form.content" rows="15" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-sm font-medium leading-relaxed text-slate-700 transition-all focus:ring-0 rounded-xl"></textarea>
                        <p v-if="form.errors.content" class="text-red-500 text-[9px] font-black uppercase mt-1">{{ form.errors.content }}</p>
                    </div>

                </div>
            </div>

            <div class="lg:col-span-1 space-y-10">
                <div class="bg-white border border-slate-200 p-10 space-y-10 shadow-xl shadow-slate-200/50 rounded-2xl sticky top-32">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 italic border-b border-slate-100 pb-4">Publication Vector</h3>
                    
                    <div class="space-y-4">
                        <label class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:border-orange-600 transition-all group shadow-sm">
                            <input type="checkbox" v-model="form.is_public_visible" class="w-5 h-5 bg-white border-slate-200 text-orange-600 focus:ring-0 rounded-lg cursor-pointer">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 group-hover:text-slate-900 transition-colors">Web Visibility</span>
                                <span class="text-[8px] text-slate-400 font-bold uppercase">Live on Public Blog</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:border-orange-600 transition-all group">
                            <input type="checkbox" v-model="form.is_featured" class="w-5 h-5 bg-white border-slate-200 text-orange-600 focus:ring-0 rounded-lg cursor-pointer">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 group-hover:text-slate-900 transition-colors">Featured Insight</span>
                        </label>
                    </div>

                    <div class="space-y-4 border-t border-slate-100 pt-6">
                        <label v-for="status in ['draft', 'published', 'scheduled']" :key="status" class="flex items-center gap-4 p-4 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:border-slate-300 transition-all group">
                            <input type="radio" v-model="form.status" :value="status" class="w-4 h-4 bg-white border-slate-200 text-orange-600 focus:ring-0 cursor-pointer">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 group-hover:text-slate-900 transition-colors">{{ status }}</span>
                        </label>
                    </div>

                    <button type="submit" :disabled="form.processing" class="group relative w-full bg-slate-900 py-6 overflow-hidden rounded-2xl transition-all active:scale-95 disabled:opacity-50 shadow-2xl shadow-slate-900/10">
                        <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.4em] text-white transition-colors">
                            {{ form.processing ? 'Syncing...' : 'Commit Post' }}
                        </span>
                        <div class="absolute inset-0 bg-orange-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    </button>
                    
                    <div class="pt-8 border-t border-slate-100">
                        <h3 class="text-[9px] font-black uppercase tracking-[0.4em] text-orange-600 mb-4 italic">Editorial Standard</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed">
                            Educational content drives organic authority. Ensure all industrial insights are peer-reviewed for technical accuracy before final publication.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>
