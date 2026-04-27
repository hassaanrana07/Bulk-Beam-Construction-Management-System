<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    milestones: Array,
    projects: Array,
});

const form = useForm({
    title: '',
    portfolio_id: '',
    deadline: '',
    description: '',
});

const showCreateModal = ref(false);

const submit = () => {
    form.post(route('admin.milestones.store'), {
        onSuccess: () => {
            showCreateModal.value = false;
            form.reset();
        }
    });
};

const toggleStatus = (milestone) => {
    const newStatus = milestone.status === 'completed' ? 'pending' : 'completed';
    router.patch(route('admin.milestones.update', milestone.id), { status: newStatus });
};

const formatDate = (date) => date ? new Date(date).toLocaleDateString() : 'TBD';
</script>

<template>
    <AdminLayout>
        <Head title="Milestones | Operational Core" />

        <ModuleHeader title="Project Milestones">
            <template #actions>
                <button @click="showCreateModal = true" class="px-6 py-3 bg-orange-600 text-white text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white transition-all rounded-lg">
                    Define Milestone
                </button>
            </template>
        </ModuleHeader>

        <div class="grid grid-cols-1 gap-6">
            <div v-if="projects.length === 0" class="flex flex-col items-center justify-center py-24 text-center border border-zinc-900 rounded-xl">
                <div class="w-12 h-12 border border-zinc-800 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <p class="text-[10px] font-black uppercase tracking-[0.4em] text-zinc-600 italic">No projects or milestones found</p>
            </div>
            <div v-for="project in projects" :key="project.id" class="bg-black/40 border border-zinc-900 rounded-xl overflow-hidden">
                <div class="bg-zinc-900/50 p-6 border-b border-zinc-800 flex justify-between items-center">
                    <h3 class="text-sm font-black text-white uppercase tracking-wider">{{ project.title }}</h3>
                    <span class="text-[9px] font-black text-zinc-500 uppercase tracking-widest">{{ milestones.filter(m => m.portfolio_id === project.id).length }} Milestones</span>
                </div>
                <div class="p-6">
                    <div v-if="milestones.filter(m => m.portfolio_id === project.id).length" class="space-y-4">
                        <div v-for="m in milestones.filter(m => m.portfolio_id === project.id)" :key="m.id" class="flex items-center justify-between p-4 bg-zinc-900/30 border border-zinc-800 rounded-lg group">
                            <div class="flex items-center gap-4">
                                <button @click="toggleStatus(m)" :class="m.status === 'completed' ? 'bg-emerald-600 border-emerald-500' : 'bg-transparent border-zinc-700'" class="w-5 h-5 border-2 rounded flex items-center justify-center transition-colors">
                                    <svg v-if="m.status === 'completed'" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </button>
                                <div>
                                    <h4 :class="{'line-through opacity-50': m.status === 'completed'}" class="text-xs font-black text-white uppercase">{{ m.title }}</h4>
                                    <p class="text-[9px] text-zinc-600 font-bold uppercase mt-1">Deadline: {{ formatDate(m.deadline) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-10 italic text-[10px] text-zinc-700 uppercase tracking-widest">No milestones defined for this expansion.</div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div v-if="showCreateModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/90 backdrop-blur-md">
            <div class="bg-black border border-zinc-800 w-full max-w-xl p-10 rounded-2xl animate-fade-in">
                <h3 class="text-xl font-black uppercase text-white mb-8 italic">Add Milestone</h3>
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Milestone Title</label>
                        <input v-model="form.title" type="text" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg">
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Project Nodes</label>
                            <select v-model="form.portfolio_id" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg">
                                <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.title }}</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Target Date</label>
                            <input v-model="form.deadline" type="date" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Description</label>
                        <textarea v-model="form.description" rows="3" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm rounded-lg"></textarea>
                    </div>
                    <div class="flex gap-4 pt-6">
                        <button type="button" @click="showCreateModal = false" class="flex-1 py-4 text-[10px] font-black text-zinc-500 uppercase">Cancel</button>
                        <button type="submit" class="flex-1 py-4 bg-orange-600 text-white text-[10px] font-black uppercase rounded-lg">Add Milestone</button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
