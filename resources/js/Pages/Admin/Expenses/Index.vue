<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    expenses: Array,
    portfolios: Array,
    vendors: Array,
    stats: Object,
});

const form = useForm({
    portfolio_id: '',
    vendor_id: '',
    amount: '',
    category: 'materials',
    due_date: '',
    invoice_number: '',
    status: 'pending',
});

const showCreateModal = ref(false);

const submit = () => {
    form.post(route('admin.expenses.store'), {
        onSuccess: () => {
            showCreateModal.value = false;
            form.reset();
        }
    });
};

const formatCurrency = (val) => new Intl.NumberFormat('en-PK', { style: 'currency', currency: 'PKR' }).format(val);
const getStatusColor = (s) => {
    const colors = { paid: 'text-emerald-500', pending: 'text-orange-500', disputed: 'text-red-500' };
    return colors[s] || 'text-zinc-500';
};
</script>

<template>
    <AdminLayout>
        <Head title="Expense Control | Operational Core" />

        <ModuleHeader title="Financial Expense Matrix">
            <template #actions>
                <div class="flex gap-4">
                    <button @click="showCreateModal = true" class="px-6 py-3 bg-orange-600 text-white text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white transition-all rounded-lg">
                        Log New Expense
                    </button>
                    <a :href="route('admin.expenses.export-csv')" class="px-6 py-3 border border-zinc-900 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 hover:text-white hover:border-orange-600 transition-all rounded-lg">
                        Export CSV
                    </a>
                    <a :href="route('admin.expenses.export-pdf')" target="_blank" class="px-6 py-3 border border-zinc-900 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 hover:text-white hover:border-orange-600 transition-all rounded-lg">
                        Export PDF
                    </a>
                </div>
            </template>
        </ModuleHeader>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
            <div v-for="(val, label) in {
                'Total Outflow': formatCurrency(stats.total_outflow),
                'Pending AP': formatCurrency(stats.pending_ap),
                'Monthly Burn': formatCurrency(stats.monthly_burn),
                'Disputed Claims': formatCurrency(stats.disputed_amount)
            }" :key="label" class="bg-white border border-slate-200 p-8 rounded-2xl shadow-sm">
                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mb-2">{{ label }}</p>
                <p class="text-2xl font-black text-slate-900 italic tracking-tighter">{{ val }}</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xl shadow-slate-200/50">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-100">
                            <th class="px-8 py-6">Reference</th>
                            <th class="px-8 py-6">Project Nodes</th>
                            <th class="px-8 py-6">Vendor/Service</th>
                            <th class="px-8 py-6">Magnitude</th>
                            <th class="px-8 py-6">Protocol Status</th>
                            <th class="px-8 py-6 text-right">Audit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="exp in expenses" :key="exp.id" class="hover:bg-slate-50 transition-colors group">
                            <td class="px-8 py-6">
                                <p class="text-[10px] font-black text-slate-900 uppercase">{{ exp.invoice_number || 'STUB-#' + exp.id }}</p>
                                <p class="text-[8px] text-slate-400 font-bold uppercase mt-1">{{ exp.category }}</p>
                            </td>
                            <td class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase">{{ exp.portfolio?.title }}</td>
                            <td class="px-8 py-6 text-[10px] font-black text-slate-900 uppercase">{{ exp.vendor?.name || 'Direct Procurement' }}</td>
                            <td class="px-8 py-6 text-[11px] font-black text-orange-600">{{ formatCurrency(exp.amount) }}</td>
                            <td class="px-8 py-6">
                                <span :class="getStatusColor(exp.status)" class="text-[9px] font-black uppercase tracking-widest">{{ exp.status }}</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <button class="text-[9px] font-black text-slate-300 hover:text-slate-900 uppercase tracking-widest transition-colors">Archive</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Modal -->
        <div v-if="showCreateModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200 w-full max-w-xl p-10 rounded-2xl shadow-2xl animate-fade-in">
                <h3 class="text-xl font-black uppercase text-slate-900 mb-8 italic border-b border-slate-100 pb-4">New Expense Protocol</h3>
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Project Nodes</label>
                            <select v-model="form.portfolio_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 p-4 text-sm font-black uppercase focus:border-orange-600 focus:ring-0 rounded-xl transition-all">
                                <option v-for="p in portfolios" :key="p.id" :value="p.id">{{ p.title }}</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Vendor/Entity</label>
                            <select v-model="form.vendor_id" class="w-full bg-slate-50 border border-slate-200 text-slate-900 p-4 text-sm font-black uppercase focus:border-orange-600 focus:ring-0 rounded-xl transition-all">
                                <option v-for="v in vendors" :key="v.id" :value="v.id">{{ v.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Financial Magnitude</label>
                            <input v-model="form.amount" type="number" step="0.01" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 p-4 text-sm font-black uppercase focus:border-orange-600 focus:ring-0 rounded-xl transition-all placeholder-slate-300">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Classification</label>
                            <select v-model="form.category" class="w-full bg-slate-50 border border-slate-200 text-slate-900 p-4 text-sm font-black uppercase focus:border-orange-600 focus:ring-0 rounded-xl transition-all">
                                <option value="labor">Personnel/Labor</option>
                                <option value="materials">Raw Materials</option>
                                <option value="equipment">Hardware/Equipment</option>
                                <option value="overhead">Operational Overhead</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-4 pt-6">
                        <button type="button" @click="showCreateModal = false" class="flex-1 py-4 text-[10px] font-black text-slate-400 hover:text-slate-900 uppercase transition-colors">Abort</button>
                        <button type="submit" class="flex-1 py-4 bg-orange-600 text-white text-[10px] font-black uppercase rounded-xl hover:bg-slate-900 transition-all shadow-lg shadow-orange-600/20 active:scale-95">Commit Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
