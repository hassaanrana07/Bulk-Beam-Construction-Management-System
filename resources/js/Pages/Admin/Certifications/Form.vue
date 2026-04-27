<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    certification: Object
});

const form = useForm({
    name: props.certification?.name || '',
    issuing_organization: props.certification?.issuing_organization || '',
    certificate_number: props.certification?.certificate_number || '',
    image: props.certification?.image || '',
    issue_date: props.certification?.issue_date || '',
    expiry_date: props.certification?.expiry_date || '',
    description: props.certification?.description || '',
    is_active: props.certification?.is_active ?? true,
    is_public_visible: props.certification?.is_public_visible ?? true,
    order: props.certification?.order || 0,
});

const submit = () => {
    if (props.certification) {
        form.put(route('admin.certifications.update', props.certification.id));
    } else {
        form.post(route('admin.certifications.store'));
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="certification ? 'Modify Certification' : 'Archive Certification'" />

        <ModuleHeader :title="certification ? `Edit: ${certification.name}` : 'New Certification Register'">
            <template #subtitle>
                <Link :href="route('admin.certifications.index')" class="text-[10px] font-black text-zinc-600 hover:text-orange-600 uppercase tracking-[0.3em] flex items-center gap-2 mt-2 transition-colors">
                    <span>←</span> Return to Matrix
                </Link>
            </template>
        </ModuleHeader>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-10">
                <div class="bg-black border border-zinc-900 p-10 space-y-10 shadow-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500 border-b border-zinc-900 pb-4 italic">Technical Parameters</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2 block">Certificate Name</label>
                            <input v-model="form.name" type="text" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-sm font-black uppercase tracking-tight text-white transition-all focus:ring-0">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2 block">Issuing Organization</label>
                            <input v-model="form.issuing_organization" type="text" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-white transition-all focus:ring-0">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2 block">Certificate Number</label>
                            <input v-model="form.certificate_number" type="text" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-xs font-bold tracking-tight text-white transition-all focus:ring-0">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2 block">Certificate Image URI</label>
                            <input v-model="form.image" type="text" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-xs font-bold tracking-tight text-white transition-all focus:ring-0" placeholder="/storage/certs/iso.jpg">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2 block">Issue Date</label>
                            <input v-model="form.issue_date" type="date" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-xs font-bold text-white transition-all focus:ring-0">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2 block">Expiry Date</label>
                            <input v-model="form.expiry_date" type="date" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-xs font-bold text-white transition-all focus:ring-0">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2 block">Description / Scope</label>
                        <textarea v-model="form.description" rows="6" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-sm font-medium tracking-tight text-zinc-300 transition-all focus:ring-0"></textarea>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-10">
                <div class="bg-black border border-zinc-900 p-10 space-y-10 shadow-2xl sticky top-32">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500 italic">Archive Logic</h3>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mb-2 block">Display Sequence</label>
                        <input v-model="form.order" type="number" class="w-full bg-zinc-950 border border-zinc-900 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-white transition-all focus:ring-0">
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center gap-4 p-5 bg-zinc-950 border border-zinc-900 cursor-pointer hover:border-orange-600 transition-all group shadow-[0_0_20px_rgba(234,88,12,0.1)]">
                            <input type="checkbox" v-model="form.is_public_visible" class="w-5 h-5 bg-black border-zinc-800 text-orange-600 focus:ring-0 cursor-pointer">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 group-hover:text-white transition-colors">Web Visibility</span>
                                <span class="text-[8px] text-zinc-600 font-bold uppercase">Live on Public Profile</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-4 p-5 bg-zinc-950 border border-zinc-900 cursor-pointer hover:border-orange-600 transition-all group">
                            <input type="checkbox" v-model="form.is_active" class="w-5 h-5 bg-black border-zinc-800 text-orange-600 focus:ring-0 cursor-pointer">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-400 group-hover:text-white transition-colors">Validity Token Active</span>
                        </label>
                    </div>

                    <button type="submit" :disabled="form.processing" class="group relative w-full bg-white py-6 overflow-hidden transition-all active:scale-95 disabled:opacity-50">
                        <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.4em] text-black group-hover:text-white transition-colors">
                            {{ form.processing ? 'Syncing...' : 'Commit Certificate' }}
                        </span>
                        <div class="absolute inset-0 bg-orange-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    </button>
                    
                    <div class="pt-8 border-t border-zinc-900">
                        <h3 class="text-[9px] font-black uppercase tracking-[0.4em] text-orange-600 mb-4">Verification Protocol</h3>
                        <p class="text-[9px] text-zinc-600 font-bold uppercase tracking-widest leading-relaxed">
                            Certifications represent the formal authority of Brick & Beam. Ensure all document serial numbers are verified against issuing body records before deployment.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>
