<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    requests: Array,
    portfolios: Array,
});

const form = useForm({
    id: null,
    portfolio_id: '',
    title: '',
    items: '',
    estimated_cost: '',
    status: 'pending',
});

const showCreateModal = ref(false);
const isEditing = ref(false);

const openCreateModal = () => {
    isEditing.value = false;
    form.reset();
    form.id = null;
    showCreateModal.value = true;
};

const openEditModal = (req) => {
    isEditing.value = true;
    form.id = req.id;
    form.portfolio_id = req.portfolio_id;
    form.title = req.title;
    form.items = req.items;
    form.estimated_cost = req.estimated_cost;
    form.status = req.status;
    showCreateModal.value = true;
};

const submit = () => {
    if (isEditing.value) {
        form.put(route('admin.purchase-requests.update', form.id), {
            onSuccess: () => {
                showCreateModal.value = false;
                form.reset();
            }
        });
    } else {
        form.post(route('admin.purchase-requests.store'), {
            onSuccess: () => {
                showCreateModal.value = false;
                form.reset();
            }
        });
    }
};

const updateStatus = (req, status) => {
    router.patch(route('admin.purchase-requests.update', req.id), { status }, {
        preserveScroll: true
    });
};

const deleteRequest = (id) => {
    if (confirm('Permanently purge this procurement record?')) {
        router.delete(route('admin.purchase-requests.destroy', id), {
            preserveScroll: true
        });
    }
};

const formatCurrency = (val) => new Intl.NumberFormat('en-PK', { style: 'currency', currency: 'PKR' }).format(val);
</script>

<template>
    <AdminLayout>
        <Head title="Procurement Queue | Operational Core" />

        <ModuleHeader title="Purchase Request Matrix">
            <template #actions>
                <button @click="openCreateModal" class="px-6 py-3 bg-orange-600 text-white text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white transition-all rounded-lg shadow-xl">
                    Initiate Procurement
                </button>
            </template>
        </ModuleHeader>

        <div class="space-y-6">
            <div v-for="req in requests" :key="req.id" class="bg-black border border-zinc-900 p-8 rounded-xl flex flex-col md:flex-row justify-between gap-8 group hover:border-orange-600 transition-all relative overflow-hidden">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-2 py-1 bg-zinc-900 text-zinc-500 text-[8px] font-black uppercase tracking-widest border border-zinc-800 rounded">{{ req.status }}</span>
                        <h3 class="text-sm font-black text-white uppercase tracking-tighter">{{ req.title }}</h3>
                    </div>
                    <p class="text-[10px] text-zinc-600 font-bold uppercase mb-6">{{ req.portfolio?.title }} · Initiated by {{ req.user?.name }}</p>
                    <div class="p-6 bg-zinc-900/50 border border-zinc-800 rounded-lg">
                        <p class="text-[10px] text-zinc-400 font-medium leading-relaxed">{{ req.items }}</p>
                    </div>
                </div>
                
                <div class="md:w-72 flex flex-col justify-between items-end">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-zinc-700 uppercase tracking-widest mb-1">Estimated Capital</p>
                        <p class="text-2xl font-black text-orange-500 italic">{{ formatCurrency(req.estimated_cost) }}</p>
                    </div>
                    
                    <div class="flex flex-wrap justify-end gap-2 mt-8">
                        <!-- Status Controls -->
                         <template v-if="req.status === 'pending'">
                            <button @click="updateStatus(req, 'manager_approved')" class="px-4 py-2 bg-emerald-600/10 border border-emerald-600/20 text-emerald-500 text-[8px] font-black uppercase tracking-widest rounded hover:bg-emerald-600 hover:text-white transition-all">Approve</button>
                            <button @click="updateStatus(req, 'rejected')" class="px-4 py-2 bg-red-600/10 border border-red-600/20 text-red-500 text-[8px] font-black uppercase tracking-widest rounded hover:bg-red-600 hover:text-white transition-all">Reject</button>
                         </template>
                        <button v-if="req.status === 'manager_approved'" @click="updateStatus(req, 'finance_processed')" class="px-4 py-2 bg-orange-600 text-white text-[8px] font-black uppercase tracking-widest rounded hover:bg-white hover:text-black transition-all">Process Payment</button>
                        
                        <!-- Management Controls -->
                        <div class="flex gap-2 ml-4 pl-4 border-l border-zinc-900">
                            <button @click="openEditModal(req)" class="p-2 bg-zinc-900 border border-zinc-800 text-zinc-500 hover:text-white transition-all rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button @click="deleteRequest(req.id)" class="p-2 bg-zinc-900 border border-zinc-800 text-zinc-500 hover:text-red-500 transition-all rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-if="!requests.length" class="py-20 text-center border border-dashed border-zinc-900 rounded-2xl text-zinc-700 text-[10px] font-black uppercase tracking-widest">
                Procurement queue is clear. No active requests.
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div v-if="showCreateModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/90 backdrop-blur-md">
            <div class="bg-black border border-zinc-800 w-full max-w-xl p-10 rounded-2xl animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
                <h3 class="text-xl font-black uppercase text-white mb-8 italic">{{ isEditing ? 'Modification Protocol' : 'Procurement Initiation' }}</h3>
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Request Subject</label>
                        <input v-model="form.title" type="text" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg" placeholder="Enter request title">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Project Allocation</label>
                        <select v-model="form.portfolio_id" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg appearance-none cursor-pointer">
                            <option value="" disabled>Select target project</option>
                            <option v-for="p in portfolios" :key="p.id" :value="p.id">{{ p.title }}</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Items Specification</label>
                        <textarea v-model="form.items" rows="4" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-medium focus:border-orange-600 rounded-lg custom-scrollbar" placeholder="Detail all items and requirements..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Estimated Capital Required</label>
                            <input v-model="form.estimated_cost" type="number" step="0.01" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg">
                        </div>
                        <div v-if="isEditing" class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Current Lifecycle Status</label>
                            <select v-model="form.status" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg appearance-none cursor-pointer">
                                <option value="pending">Pending</option>
                                <option value="manager_approved">Approved</option>
                                <option value="finance_processed">Processed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-4 pt-6">
                        <button type="button" @click="showCreateModal = false" class="flex-1 py-4 text-[10px] font-black text-zinc-500 uppercase hover:text-white transition-colors">Abort</button>
                        <button type="submit" :disabled="form.processing" class="flex-1 py-4 bg-orange-600 text-white text-[10px] font-black uppercase rounded-lg shadow-lg hover:bg-orange-700 disabled:opacity-50">
                            {{ isEditing ? 'Apply Modifications' : 'Submit Request' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
