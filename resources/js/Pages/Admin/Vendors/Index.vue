<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    vendors: Array,
});

const form = useForm({
    id: null,
    name: '',
    contact_person: '',
    email: '',
    phone: '',
    category: '',
    address: '',
    company_bim: '',
    company_bic: '',
});

const showCreateModal = ref(false);
const isEditing = ref(false);

const openCreateModal = () => {
    isEditing.value = false;
    form.reset();
    form.id = null;
    showCreateModal.value = true;
};

const openEditModal = (vendor) => {
    isEditing.value = true;
    form.id = vendor.id;
    form.name = vendor.name;
    form.contact_person = vendor.contact_person;
    form.email = vendor.email;
    form.phone = vendor.phone;
    form.category = vendor.category;
    form.address = vendor.address;
    form.company_bim = vendor.company_bim || '';
    form.company_bic = vendor.company_bic || '';
    showCreateModal.value = true;
};

const submit = () => {
    if (isEditing.value) {
        form.put(route('admin.vendors.update', form.id), {
            onSuccess: () => {
                showCreateModal.value = false;
                form.reset();
            }
        });
    } else {
        form.post(route('admin.vendors.store'), {
            onSuccess: () => {
                showCreateModal.value = false;
                form.reset();
            }
        });
    }
};

const deleteVendor = (id) => {
    if (confirm('Are you sure you want to purge this entity from the records?')) {
        form.delete(route('admin.vendors.destroy', id));
    }
};

// Check if a vendor already has BIM/BIC set (for restriction)
const isBimDisabled = (vendor) => !!vendor?.company_bim;
const isBicDisabled = (vendor) => !!vendor?.company_bic;
</script>

<template>
    <AdminLayout>
        <Head title="Contractor Archive | Operational Core" />

        <ModuleHeader title="Government & Contractor Registrations">
            <template #actions>
                <button @click="openCreateModal" class="px-6 py-3 bg-orange-600 text-white text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white transition-all rounded-lg shadow-xl">
                    Register New Entity
                </button>
            </template>
        </ModuleHeader>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="vendor in vendors" :key="vendor.id" class="bg-black border border-zinc-900 p-8 rounded-xl group hover:border-orange-600 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-zinc-900 border border-zinc-800 flex items-center justify-center font-black text-white text-lg rounded uppercase transition-transform group-hover:scale-110">
                            {{ vendor.name.charAt(0) }}
                        </div>
                        <div>
                            <h3 class="text-xs font-black text-white uppercase tracking-tighter">{{ vendor.name }}</h3>
                            <span class="text-[8px] font-black text-orange-600 uppercase tracking-widest">{{ vendor.category || 'General Entity' }}</span>
                        </div>
                    </div>

                    <div class="space-y-4 border-t border-zinc-900 pt-6">
                        <div v-if="vendor.company_bim" class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-zinc-600 uppercase">Company BIM</span>
                            <span class="text-[10px] font-bold text-orange-500">{{ vendor.company_bim }}</span>
                        </div>
                        <div v-if="vendor.company_bic" class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-zinc-600 uppercase">Company BIC</span>
                            <span class="text-[10px] font-bold text-orange-500">{{ vendor.company_bic }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-zinc-600 uppercase">Contact Node</span>
                            <span class="text-[10px] font-bold text-zinc-400">{{ vendor.contact_person }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-zinc-600 uppercase">Comm Channel</span>
                            <span class="text-[10px] font-bold text-zinc-400">{{ vendor.email }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-zinc-600 uppercase">Secure Line</span>
                            <span class="text-[10px] font-bold text-zinc-400">{{ vendor.phone }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 mt-8 pt-6 border-t border-zinc-900/50">
                    <button @click="openEditModal(vendor)" class="flex-1 py-3 bg-zinc-900 border border-zinc-800 text-[8px] font-black uppercase tracking-widest text-zinc-500 hover:bg-white hover:text-black transition-all rounded">Modify Profile</button>
                    <button @click="deleteVendor(vendor.id)" class="px-4 py-3 bg-zinc-900/50 border border-zinc-800 text-[8px] font-black uppercase tracking-widest text-red-900 hover:bg-red-600 hover:text-white transition-all rounded">
                         <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div v-if="showCreateModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/90 backdrop-blur-md">
            <div class="bg-black border border-zinc-800 w-full max-w-xl p-10 rounded-2xl animate-fade-in max-h-[90vh] overflow-y-auto custom-scrollbar">
                <h3 class="text-xl font-black uppercase text-white mb-8 italic">{{ isEditing ? 'Strategic Profile Update' : 'New Entity Registration' }}</h3>
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Legal Entity Name</label>
                        <input v-model="form.name" type="text" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Company BIM (Fixed Field)</label>
                            <input v-model="form.company_bim" type="text" :disabled="isEditing && form.company_bim" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed" placeholder="Immutable once set">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Company BIC (Fixed Field)</label>
                            <input v-model="form.company_bic" type="text" :disabled="isEditing && form.company_bic" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed" placeholder="Immutable once set">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Node Representative</label>
                            <input v-model="form.contact_person" type="text" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Entity Category</label>
                            <input v-model="form.category" type="text" placeholder="e.g. FBR, SECP, Contractor" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Comm Email</label>
                            <input v-model="form.email" type="email" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Secure Phone</label>
                            <input v-model="form.phone" type="text" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 rounded-lg">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Geographic Headquarters</label>
                        <textarea v-model="form.address" rows="2" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-medium focus:border-orange-600 rounded-lg"></textarea>
                    </div>

                    <div class="flex gap-4 pt-6">
                        <button type="button" @click="showCreateModal = false" class="flex-1 py-4 text-[10px] font-black text-zinc-500 uppercase hover:text-white transition-colors">Abort</button>
                        <button type="submit" :disabled="form.processing" class="flex-1 py-4 bg-orange-600 text-white text-[10px] font-black uppercase rounded-lg shadow-lg hover:bg-orange-700 disabled:opacity-50">
                            {{ isEditing ? 'Update Configuration' : 'Register Entity' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
