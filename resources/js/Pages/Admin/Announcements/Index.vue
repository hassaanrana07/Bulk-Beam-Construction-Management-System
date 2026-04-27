<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    announcements: Array,
});

const form = useForm({
    title: '',
    content: '',
    target_roles: [],
    expires_at: '',
});

const showCreateModal = ref(false);

const submit = () => {
    form.post(route('admin.announcements.store'), {
        onSuccess: () => {
            showCreateModal.value = false;
            form.reset();
        }
    });
};

const formatDate = (date) => date ? new Date(date).toLocaleDateString() : 'Permanent';
</script>

<template>
    <AdminLayout>
        <Head title="Announcements | Operational Core" />

        <ModuleHeader title="Internal Communications">
            <template #actions>
                <button @click="showCreateModal = true" class="px-6 py-3 bg-orange-600 text-white text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white transition-all rounded-lg">
                    Broadcast Update
                </button>
            </template>
        </ModuleHeader>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="ann in announcements" :key="ann.id" class="bg-black border border-zinc-900 p-8 rounded-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-orange-600/5 rotate-45 translate-x-12 -translate-y-12 transition-transform group-hover:scale-150"></div>
                
                <h3 class="text-xs font-black text-white uppercase mb-4 tracking-tighter">{{ ann.title }}</h3>
                <p class="text-[10px] text-zinc-600 leading-relaxed font-medium mb-8">{{ ann.content }}</p>
                
                <div class="flex items-center justify-between pt-6 border-t border-zinc-900">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-zinc-900 border border-zinc-800 flex items-center justify-center text-[8px] font-black rounded uppercase">{{ ann.user?.name.charAt(0) }}</div>
                        <span class="text-[8px] font-black uppercase text-zinc-500">{{ ann.user?.name }}</span>
                    </div>
                    <span class="text-[8px] font-black text-zinc-700 uppercase tracking-widest">Expires: {{ formatDate(ann.expires_at) }}</span>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div v-if="showCreateModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/90 backdrop-blur-md">
            <div class="bg-black border border-zinc-800 w-full max-w-xl p-10 rounded-2xl animate-fade-in">
                <h3 class="text-xl font-black uppercase text-white mb-8 italic">New Broadcast</h3>
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Subject</label>
                        <input v-model="form.title" type="text" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Communication Content</label>
                        <textarea v-model="form.content" rows="4" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-medium focus:border-orange-600 rounded-lg"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Expires At (Optional)</label>
                        <input v-model="form.expires_at" type="date" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg">
                    </div>
                    <div class="flex gap-4 pt-6">
                        <button type="button" @click="showCreateModal = false" class="flex-1 py-4 text-[10px] font-black text-zinc-500 uppercase">Abort</button>
                        <button type="submit" class="flex-1 py-4 bg-orange-600 text-white text-[10px] font-black uppercase rounded-lg">Broadcast</button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
