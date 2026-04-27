<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    staff: Object
});

const form = useForm({
    name: props.staff?.name || '',
    role: props.staff?.role || '',
    bio: props.staff?.bio || '',
    photo: null,
    photo_url: props.staff?.photo_url || '',
    order: props.staff?.order || 0,
    is_leadership: props.staff?.is_leadership || false,
    is_active: props.staff?.is_active ?? true,
    is_public_visible: props.staff?.is_public_visible ?? true,
    social_links: props.staff?.social_links || { linkedin: '', twitter: '' }
});

const photoPreview = ref(null);

const handlePhotoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.photo = file;
        form.photo_url = ''; // Clear URL if file is selected
        photoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    if (props.staff) {
        form.transform(data => ({
            ...data,
            _method: 'PUT'
        })).post(route('admin.staff.update', props.staff.id), {
            forceFormData: true
        });
    } else {
        form.post(route('admin.staff.store'), {
            forceFormData: true
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="staff ? 'Modify Personnel' : 'Deploy Personnel'" />

        <ModuleHeader :title="staff ? `Edit: ${staff.name}` : 'New Personnel Deployment'">
            <template #subtitle>
                <Link :href="route('admin.staff.index')" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-primary transition-colors mt-2">
                    ← Return to Matrix
                </Link>
            </template>
        </ModuleHeader>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white border border-slate-200 p-10 space-y-10 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 border-b border-slate-100 pb-4 italic">Personnel Identification</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Full Name</label>
                            <input v-model="form.name" type="text" required class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary text-slate-900 font-black uppercase tracking-tight px-6 py-4 focus:ring-0 transition-all rounded-xl placeholder-slate-300">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Operational Role</label>
                            <input v-model="form.role" type="text" required class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary text-slate-900 font-black uppercase tracking-tight px-6 py-4 focus:ring-0 transition-all rounded-xl placeholder-slate-300" placeholder="e.g. Lead Project architect">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h4 class="text-[9px] font-black uppercase tracking-[0.4em] text-slate-400 italic">Visual Identity Assets</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block tracking-[0.4em]">Local Asset (Upload)</label>
                                <label class="block w-full bg-slate-50 border-2 border-dashed border-slate-200 hover:border-primary text-slate-400 hover:text-primary font-black uppercase tracking-widest px-6 py-8 cursor-pointer transition-all text-center rounded-2xl group">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-6 h-6 mb-2 transition-transform group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                        <span>Select Local File</span>
                                    </div>
                                    <input type="file" @change="handlePhotoChange" class="hidden" accept="image/*">
                                </label>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block tracking-[0.4em]">Remote Asset (URL)</label>
                                <input v-model="form.photo_url" type="url" @input="form.photo = null" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary text-slate-900 font-bold px-6 py-4 focus:ring-0 transition-all rounded-xl placeholder-slate-300" placeholder="https://external-storage.com/asset.jpg">
                                <p class="text-[8px] text-slate-400 font-black uppercase italic">Remote URLs bypass local storage protocols.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Personnel Narrative (Bio)</label>
                        <textarea v-model="form.bio" rows="6" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary text-slate-600 font-medium px-6 py-4 focus:ring-0 transition-all rounded-xl leading-relaxed"></textarea>
                    </div>

                    <div class="pt-8 space-y-8">
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 border-b border-slate-100 pb-4 italic">External Linkage (Social)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block font-black">LinkedIn URI</label>
                                <input v-model="form.social_links.linkedin" type="text" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary text-slate-900 font-bold px-6 py-4 focus:ring-0 transition-all rounded-xl shadow-inner">
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block font-black">Twitter URI</label>
                                <input v-model="form.social_links.twitter" type="text" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary text-slate-900 font-bold px-6 py-4 focus:ring-0 transition-all rounded-xl shadow-inner">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-white border border-slate-200 p-10 space-y-10 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 italic">Command Configuration</h3>
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Priority Index (Order)</label>
                            <input v-model="form.order" type="number" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary text-slate-900 font-black px-6 py-4 focus:ring-0 transition-all rounded-xl">
                        </div>

                        <label class="flex items-center gap-4 bg-slate-50 p-6 border border-slate-100 cursor-pointer group hover:border-primary transition-all rounded-xl shadow-inner">
                            <input type="checkbox" v-model="form.is_leadership" class="w-6 h-6 bg-white border-slate-200 text-primary focus:ring-0 rounded-lg cursor-pointer">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 group-hover:text-slate-900 transition-colors">Grant Leadership Status</span>
                        </label>
                        
                        <label class="flex items-center gap-4 bg-slate-50 p-6 border border-slate-100 cursor-pointer group hover:border-primary transition-all rounded-xl shadow-inner active:scale-[0.98]">
                            <input type="checkbox" v-model="form.is_public_visible" class="w-6 h-6 bg-white border-slate-200 text-primary focus:ring-0 rounded-lg cursor-pointer">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary group-hover:text-slate-900 transition-colors">Web Visibility</span>
                                <span class="text-[8px] text-slate-400 font-bold uppercase">Live on Public Team Page</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center gap-4 bg-slate-50 p-6 border border-slate-100 cursor-pointer group hover:border-primary transition-all rounded-xl shadow-inner">
                            <input type="checkbox" v-model="form.is_active" class="w-6 h-6 bg-white border-slate-200 text-primary focus:ring-0 rounded-lg cursor-pointer">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 group-hover:text-slate-900 transition-colors">Personnel Active State</span>
                        </label>
                    </div>

                    <button type="submit" :disabled="form.processing" class="w-full group relative bg-primary py-6 overflow-hidden rounded-2xl shadow-lg shadow-primary/20 transition-all active:scale-95 disabled:opacity-50">
                        <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.4em] text-white">Commit Dossier</span>
                        <div class="absolute inset-0 bg-slate-900 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    </button>
                </div>
                
                <div v-if="photoPreview || form.photo_url || staff?.photo" class="bg-white border border-slate-200 p-2 overflow-hidden shadow-2xl rounded-2xl group relative">
                    <img :src="photoPreview || form.photo_url || staff?.photo" class="w-full transition-all duration-700 aspect-square object-cover rounded-xl" alt="Preview">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6 rounded-xl">
                        <p class="text-[9px] font-black text-white uppercase tracking-widest italic">Live Preview Data</p>
                    </div>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>
