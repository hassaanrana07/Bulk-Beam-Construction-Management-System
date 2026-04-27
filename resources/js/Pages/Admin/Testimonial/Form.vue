<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    testimonial: Object
});

const form = useForm({
    client_name: props.testimonial?.client_name || '',
    client_position: props.testimonial?.client_position || '',
    client_company: props.testimonial?.client_company || '',
    client_image: null,
    image_url: props.testimonial?.image_url || '',
    testimonial: props.testimonial?.testimonial || '',
    rating: props.testimonial?.rating || 5,
    is_published: props.testimonial?.is_published ?? true,
    is_public_visible: props.testimonial?.is_public_visible ?? true,
});

const imagePreview = ref(null);
const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.client_image = file;
        form.image_url = ''; // Clear URL if file is selected
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    if (props.testimonial) {
        form.transform(data => ({
            ...data,
            _method: 'PUT'
        })).post(route('admin.testimonials.update', props.testimonial.id), {
            forceFormData: true
        });
    } else {
        form.post(route('admin.testimonials.store'), {
            forceFormData: true
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="testimonial ? 'Update Endorsement' : 'Capture Feedback'" />

        <ModuleHeader :title="testimonial ? `Edit: ${testimonial.client_name}` : 'New Testimonial'">
            <template #subtitle>
                <Link :href="route('admin.testimonials.index')" class="text-[10px] font-black text-slate-400 hover:text-primary uppercase tracking-[0.3em] flex items-center gap-2 mt-2 transition-colors">
                    <span>←</span> Return to Feedback Matrix
                </Link>
            </template>
        </ModuleHeader>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-10">
                <div class="bg-white border border-slate-200 p-10 space-y-10 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 border-b border-slate-100 pb-4 italic">Client Profile Architecture</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Client Full Name</label>
                            <input v-model="form.client_name" type="text" required class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-sm font-black uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                            <p v-if="form.errors.client_name" class="text-red-500 text-[9px] font-black uppercase mt-1">{{ form.errors.client_name }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Client Enterprise</label>
                            <input v-model="form.client_company" type="text" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Professional Designation</label>
                            <input v-model="form.client_position" type="text" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl" placeholder="e.g. Director of Operations">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Visual Identity Source</label>
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-slate-100 border border-slate-200 rounded-full overflow-hidden flex items-center justify-center shadow-inner">
                                    <img v-if="imagePreview || form.image_url || testimonial?.client_image" :src="imagePreview || form.image_url || testimonial?.client_image" class="w-full h-full object-cover">
                                    <span v-else class="text-slate-300 text-xl font-black italic">?</span>
                                </div>
                                <div class="flex-1 space-y-2">
                                    <label class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 hover:border-primary text-slate-400 hover:text-primary text-[9px] font-black uppercase tracking-widest cursor-pointer transition-all text-center rounded-xl">
                                        Upload Locale File
                                        <input type="file" @change="handleImageChange" class="hidden" accept="image/*">
                                    </label>
                                    <input v-model="form.image_url" type="url" @input="form.client_image = null" placeholder="Or provide asset URL..." class="w-full bg-slate-50 border border-thin border-slate-200 focus:border-primary px-4 py-2 text-[10px] font-medium text-slate-600 focus:ring-0 rounded-lg">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Endorsement Transcript</label>
                        <textarea v-model="form.testimonial" rows="6" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-sm font-medium tracking-tight text-slate-600 transition-all focus:ring-0 rounded-xl leading-relaxed"></textarea>
                        <p v-if="form.errors.testimonial" class="text-red-500 text-[9px] font-black uppercase mt-1">{{ form.errors.testimonial }}</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-10">
                <div class="bg-white border border-slate-200 p-10 space-y-10 shadow-xl shadow-slate-200/50 rounded-2xl sticky top-32">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 italic">Testimonial Logic</h3>
                    
                    <div class="space-y-4">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Trust Index Rating</label>
                        <div class="flex gap-2">
                            <button v-for="i in 5" :key="i" @click.prevent="form.rating = i" class="w-12 h-12 border-2 flex items-center justify-center transition-all rounded-xl" :class="form.rating >= i ? 'bg-primary border-primary text-white shadow-lg shadow-primary/20' : 'bg-slate-50 border-slate-100 text-slate-300 hover:border-slate-300'">
                                <span class="font-black italic">{{ i }}</span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:border-primary transition-all group shadow-inner">
                            <input type="checkbox" v-model="form.is_public_visible" class="w-6 h-6 bg-white border-slate-200 text-primary focus:ring-0 rounded-lg cursor-pointer">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary group-hover:text-slate-900 transition-colors">Web Visibility</span>
                                <span class="text-[8px] text-slate-400 font-bold uppercase">Live on Client Matrix</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:border-primary transition-all group shadow-inner">
                            <input type="checkbox" v-model="form.is_published" class="w-6 h-6 bg-white border-slate-200 text-primary focus:ring-0 rounded-lg cursor-pointer">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 group-hover:text-slate-900 transition-colors">Internal Published</span>
                        </label>
                    </div>

                    <button type="submit" :disabled="form.processing" class="group relative w-full bg-primary py-6 overflow-hidden rounded-2xl shadow-lg shadow-primary/30 transition-all active:scale-95 disabled:opacity-50">
                        <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.4em] text-white">
                            {{ form.processing ? 'Transmitting...' : 'Commit Feedback' }}
                        </span>
                        <div class="absolute inset-0 bg-slate-900 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    </button>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>
