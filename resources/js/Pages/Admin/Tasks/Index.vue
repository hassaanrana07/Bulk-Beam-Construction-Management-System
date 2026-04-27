<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    tasks: Array,
    portfolios: Array,
    staff: Array,
});

const form = useForm({
    title: '',
    portfolio_id: '',
    assigned_to: '',
    deadline: '',
    priority: 'medium',
    description: '',
});

const showCreateModal = ref(false);

const submit = () => {
    form.post(route('admin.tasks.store'), {
        onSuccess: () => {
            showCreateModal.value = false;
            form.reset();
        }
    });
};

const updateStatus = (task, status) => {
    router.patch(route('admin.tasks.update', task.id), { status });
};

const getPriorityColor = (p) => {
    const m = { low: 'text-zinc-500', medium: 'text-orange-500', high: 'text-red-500' };
    return m[p] || 'text-zinc-500';
};
</script>

<template>
    <AdminLayout>
        <Head title="Task Matrix | Operational Core" />

        <ModuleHeader title="Task Intelligence Matrix">
            <template #actions>
                <button @click="showCreateModal = true" class="px-6 py-3 bg-orange-600 text-white text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white hover:text-orange-600 transition-all rounded-lg shadow-lg shadow-orange-600/20">
                    Initialize New Task
                </button>
            </template>
        </ModuleHeader>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Task Columns (Simple List for now) -->
            <div v-for="status in ['todo', 'in_progress', 'completed']" :key="status" class="bg-black/40 border border-zinc-900 rounded-xl p-6 min-h-[600px]">
                <div class="flex items-center justify-between mb-8 border-b border-zinc-900 pb-4">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-white flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full" :class="status === 'completed' ? 'bg-emerald-500' : 'bg-orange-500'"></span>
                        {{ status.replace('_', ' ') }}
                    </h3>
                    <span class="text-[9px] font-black text-zinc-700 bg-zinc-900 px-2 py-0.5 rounded">{{ tasks.filter(t => t.status === status).length }}</span>
                </div>

                <div class="space-y-4">
                    <div v-for="task in tasks.filter(t => t.status === status)" :key="task.id" class="p-5 bg-zinc-900 border border-zinc-800 rounded-lg group hover:border-orange-600 transition-all">
                        <div class="flex justify-between items-start mb-3">
                            <span :class="getPriorityColor(task.priority)" class="text-[8px] font-black uppercase tracking-widest">{{ task.priority }} priority</span>
                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button v-if="status !== 'completed'" @click="updateStatus(task, status === 'todo' ? 'in_progress' : 'completed')" class="text-[8px] font-black uppercase text-orange-500 hover:text-white">Forward →</button>
                            </div>
                        </div>
                        <h4 class="text-xs font-black text-white uppercase mb-2">{{ task.title }}</h4>
                        <p class="text-[9px] text-zinc-600 uppercase mb-4">{{ task.portfolio?.title }}</p>
                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-zinc-800">
                             <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-black border border-zinc-800 flex items-center justify-center text-[8px] font-black rounded">{{ task.assignee?.name?.charAt(0) || '?' }}</div>
                                <span class="text-[8px] font-black uppercase text-zinc-500">{{ task.assignee?.name || 'Unassigned' }}</span>
                             </div>
                             <span class="text-[8px] font-black text-zinc-700">{{ task.deadline || 'No Deadline' }}</span>
                        </div>
                    </div>
                    <!-- Empty state -->
                    <div v-if="tasks.filter(t => t.status === status).length === 0" class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-10 h-10 border border-zinc-800 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-4 h-4 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <p class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-700">No tasks found</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div v-if="showCreateModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/90 backdrop-blur-md">
            <div class="bg-black border border-zinc-800 w-full max-w-xl p-10 rounded-2xl shadow-2xl animate-fade-in">
                <h3 class="text-xl font-black uppercase text-white mb-8 italic tracking-tighter">Initialize Task Protocol</h3>
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Task Objective</label>
                        <input v-model="form.title" type="text" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 transition-all rounded-lg">
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Project Nodes</label>
                            <select v-model="form.portfolio_id" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 transition-all rounded-lg">
                                <option v-for="p in portfolios" :key="p.id" :value="p.id">{{ p.title }}</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Assigned Technician</label>
                            <select v-model="form.assigned_to" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 transition-all rounded-lg">
                                <option v-for="s in staff" :key="s.id" :value="s.id">{{ s.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Deadline</label>
                            <input v-model="form.deadline" type="date" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 transition-all rounded-lg">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Priority Tier</label>
                            <select v-model="form.priority" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 transition-all rounded-lg">
                                <option value="low">Low Priority</option>
                                <option value="medium">Medium Priority</option>
                                <option value="high">High Priority</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-4 pt-6">
                        <button type="button" @click="showCreateModal = false" class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500 hover:text-white transition-colors">Abort</button>
                        <button type="submit" class="flex-1 py-4 bg-orange-600 text-white text-[10px] font-black uppercase tracking-widest hover:bg-orange-700 transition-colors rounded-lg">Activate Task</button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
