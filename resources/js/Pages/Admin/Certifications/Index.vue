<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    certifications: Array
});

const deleteCert = (id) => {
    if (confirm('Permanently purge this certification from the archive?')) {
        router.delete(route('admin.certifications.destroy', id));
    }
};

const toggleVisibility = (cert) => {
    router.put(route('admin.certifications.update', cert.id), {
        ...cert,
        is_public_visible: !cert.is_public_visible
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Certification Archive" />

        <ModuleHeader title="Certification Matrix">
            <template #actions>
                <Link :href="route('admin.certifications.create')" class="bg-orange-600 text-black px-8 py-4 text-[10px] font-black uppercase tracking-[0.3em] hover:bg-white transition-all transform hover:-translate-y-1 shadow-2xl">
                    + Register Certification
                </Link>
            </template>
        </ModuleHeader>

        <div class="bg-black border border-zinc-900 shadow-2xl overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-zinc-900">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">Certificate</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">Issuing Body</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-900">
                    <tr v-for="cert in certifications" :key="cert.id" class="group hover:bg-zinc-950/50 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-zinc-900 flex items-center justify-center border border-zinc-800">
                                    <span class="text-xs font-black italic text-orange-600">BB</span>
                                </div>
                                <div>
                                    <p class="text-sm font-black uppercase tracking-tight text-white">{{ cert.name }}</p>
                                    <p class="text-[9px] font-bold text-zinc-600 uppercase tracking-widest">{{ cert.certificate_number || 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[10px] font-black uppercase tracking-widest text-zinc-400">{{ cert.issuing_organization }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <button @click="toggleVisibility(cert)" 
                                    class="px-3 py-1 text-[8px] font-black uppercase tracking-widest border transition-all"
                                    :class="cert.is_public_visible ? 'bg-orange-600/10 border-orange-600 text-orange-600' : 'bg-zinc-900 border-zinc-800 text-zinc-600'">
                                    {{ cert.is_public_visible ? 'Public' : 'Hidden' }}
                                </button>
                                <span v-if="cert.is_active" class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                <span v-else class="w-1.5 h-1.5 bg-zinc-700 rounded-full"></span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <Link :href="route('admin.certifications.edit', cert.id)" class="p-2 text-zinc-400 hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </Link>
                                <button @click="deleteCert(cert.id)" class="p-2 text-zinc-400 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
