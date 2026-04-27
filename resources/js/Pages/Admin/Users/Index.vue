<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    users: Array,
    roles: Array,
});

const showModal = ref(false);
const editingUser = ref(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
    roles: [],
});

const openCreateModal = () => {
    editingUser.value = null;
    form.clearErrors();
    form.reset();
    showModal.value = true;
};

const openEditModal = (user) => {
    editingUser.value = user;
    form.clearErrors();
    form.name = user.name;
    form.email = user.email;
    form.password = '';
    form.roles = user.roles.map(r => r.name);
    showModal.value = true;
};

const submit = () => {
    if (editingUser.value) {
        form.put(route('admin.users.update', editingUser.value.id), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            }
        });
    } else {
        form.post(route('admin.users.store'), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            }
        });
    }
};

const deleteUser = (id) => {
    if (confirm('Permanently revoke access for this operative?')) {
        router.delete(route('admin.users.destroy', id));
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Access Matrix | Operational Core" />

        <ModuleHeader title="Personnel Access Matrix">
            <template #actions>
                <button @click="openCreateModal" class="px-6 py-3 bg-orange-600 text-white text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white hover:text-orange-600 transition-all rounded-lg shadow-xl shadow-orange-600/20">
                    Deploy New Operative
                </button>
            </template>
        </ModuleHeader>

        <div class="bg-black border border-zinc-900 rounded-xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-zinc-900/50 text-[9px] font-black uppercase tracking-[0.2em] text-zinc-500 border-b border-zinc-800">
                            <th class="px-8 py-6">Ident</th>
                            <th class="px-8 py-6">Role Classification</th>
                            <th class="px-8 py-6">Status</th>
                            <th class="px-8 py-6 text-right">Directives</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-900">
                        <tr v-for="user in users" :key="user.id" class="hover:bg-zinc-950/50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-zinc-900 border border-zinc-800 flex items-center justify-center font-black text-white text-xs rounded-lg">{{ user.name.charAt(0) }}</div>
                                    <div>
                                        <p class="text-[10px] font-black text-white uppercase">{{ user.name }}</p>
                                        <p class="text-[9px] text-zinc-600 font-bold uppercase">{{ user.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="role in user.roles" :key="role.name" class="text-[8px] font-black uppercase tracking-widest px-2 py-1 bg-orange-600/10 text-orange-600 border border-orange-600/20 rounded">
                                        {{ role.name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-emerald-500">Active</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right space-x-4">
                                <button @click="openEditModal(user)" class="text-[9px] font-black uppercase tracking-widest text-zinc-500 hover:text-white transition-colors">Modify</button>
                                <button @click="deleteUser(user.id)" class="text-[9px] font-black uppercase tracking-widest text-zinc-700 hover:text-red-600 transition-colors">Term</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/90 backdrop-blur-md">
            <div class="bg-black border border-zinc-800 w-full max-w-xl p-10 rounded-2xl shadow-2xl animate-fade-in">
                <h3 class="text-xl font-black uppercase text-white mb-8 italic tracking-tighter">
                    {{ editingUser ? 'Modify Operative Parameters' : 'Deploy New Operative' }}
                </h3>
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Operative Name</label>
                        <input v-model="form.name" type="text" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 transition-all rounded-lg">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">System Identification (Email)</label>
                        <input v-model="form.email" type="email" required class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black uppercase focus:border-orange-600 transition-all rounded-lg">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Security Cipher (Password)</label>
                        <input v-model="form.password" type="password" :required="!editingUser" class="w-full bg-zinc-950 border border-zinc-900 text-white p-4 text-sm font-black focus:border-orange-600 transition-all rounded-lg">
                        <p v-if="editingUser" class="text-[8px] text-zinc-700 uppercase italic">Leave blank to maintain current cipher.</p>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[9px] font-black uppercase tracking-widest text-zinc-500">Access Classifications</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label v-for="role in roles" :key="role.id" class="flex items-center gap-3 p-4 bg-zinc-950 border border-zinc-900 rounded-lg cursor-pointer hover:border-orange-600 transition-all group">
                                <input type="checkbox" v-model="form.roles" :value="role.name" class="w-4 h-4 bg-black border-zinc-800 text-orange-600 focus:ring-0 rounded-none">
                                <span class="text-[9px] font-black uppercase tracking-widest text-zinc-500 group-hover:text-white">{{ role.name }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex gap-4 pt-6">
                        <button type="button" @click="showModal = false" class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500 hover:text-white transition-colors">Abort</button>
                        <button type="submit" class="flex-1 py-4 bg-orange-600 text-white text-[10px] font-black uppercase tracking-widest hover:bg-orange-700 transition-colors rounded-lg">
                            {{ editingUser ? 'Synchronize' : 'Authorize' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
