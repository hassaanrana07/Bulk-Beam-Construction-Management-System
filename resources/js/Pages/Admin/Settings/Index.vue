<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    currentSettings: Object,
    systemConfigs: Object
});

const s = props.currentSettings || {};
const c = props.systemConfigs || {};

const logoPreview = ref(s.company_logo || null);
const logoFile = ref(null);
const showPasswordSection = ref(false);

const form = useForm({
    site_name:           s.site_name || '',
    contact_email:       s.contact_email || '',
    contact_phone:       s.contact_phone || '',
    company_info:        s.company_info || '',
    meta_description:    s.meta_description || '',
    google_analytics_id: s.google_analytics_id || '',
    allow_public_toggle: s.allow_public_toggle ?? true,
    min_lead_score:      s.min_lead_score || 50,
    company_logo:        null,
    remove_logo:         false,
    header_style:        s.header_style || 'logo_and_name',
    logo_width:          s.logo_width || 44,
    logo_height:         s.logo_height || 44,
    show_company_name:   s.show_company_name ?? true,
    login_branding_mode: s.login_branding_mode || 'both',
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const configForm = useForm({
    project_types: c.project_types || '[]',
    material_categories: c.material_categories || '[]',
    currency: c.currency || 'USD',
    date_format: c.date_format || 'Y-m-d'
});

const currencyOptions = [
    { code: 'USD', label: 'USD — US Dollar ($)' },
    { code: 'EUR', label: 'EUR — Euro (€)' },
    { code: 'GBP', label: 'GBP — British Pound (£)' },
    { code: 'PKR', label: 'PKR — Pakistani Rupee (₨)' },
    { code: 'AED', label: 'AED — UAE Dirham (د.إ)' },
    { code: 'SAR', label: 'SAR — Saudi Riyal (﷼)' },
    { code: 'CAD', label: 'CAD — Canadian Dollar (C$)' },
    { code: 'AUD', label: 'AUD — Australian Dollar (A$)' },
    { code: 'JPY', label: 'JPY — Japanese Yen (¥)' },
    { code: 'CNY', label: 'CNY — Chinese Yuan (¥)' },
];

const handleLogoChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;
    logoFile.value = file;
    form.company_logo = file;
    form.remove_logo = false;
    logoPreview.value = URL.createObjectURL(file);
};

const removeLogo = () => {
    logoPreview.value = null;
    logoFile.value = null;
    form.company_logo = null;
    form.remove_logo = true;
};

const submit = () => {
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
        forceFormData: true,
    });
};

const submitConfigs = () => {
    configForm.post(route('admin.settings.configs'), {
        preserveScroll: true
    });
};

const submitPassword = () => {
    passwordForm.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            showPasswordSection.value = false;
        },
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="System Infrastructure" />

        <ModuleHeader title="Global Parameters">
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mt-2">
                    Configuring core operational logic, visual identity, and site-wide dynamic bindings.
                </p>
            </template>
        </ModuleHeader>

        <div class="space-y-12">
            <!-- System Flash -->
            <div v-if="$page.props.flash?.success" class="p-6 bg-orange-600/10 border border-orange-600/20 rounded-lg">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 italic">✓ {{ $page.props.flash.success }}</p>
            </div>
            <div v-if="$page.props.flash?.error" class="p-6 bg-red-600/10 border border-red-600/20 rounded-lg">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-red-500 italic">✗ {{ $page.props.flash.error }}</p>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-2 gap-10" enctype="multipart/form-data">
                <!-- Left Column -->
                <div class="space-y-10">
                    <!-- Corporate Identity -->
                    <div class="bg-white border border-slate-200 p-10 space-y-8 shadow-xl shadow-slate-200/50 rounded-2xl">
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 border-b border-slate-100 pb-4 italic">Corporate Identity</h3>
                        
                        <!-- Logo Upload -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Company Logo</label>
                            <div class="flex items-center gap-6">
                                <div class="w-24 h-24 bg-slate-50 border border-slate-100 flex items-center justify-center overflow-hidden rounded-xl shadow-inner">
                                    <img v-if="logoPreview" :src="logoPreview" class="w-full h-full object-contain p-2">
                                    <span v-else class="text-slate-200 text-3xl font-black italic">?</span>
                                </div>
                                <div class="flex-1 space-y-3">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="bg-slate-50 border border-slate-200 hover:border-primary px-5 py-3 transition-all rounded-xl">
                                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 group-hover:text-primary transition-colors">Upload / Change Logo</span>
                                        </div>
                                        <input type="file" class="hidden" accept="image/*" @change="handleLogoChange">
                                    </label>
                                    <button
                                        v-if="logoPreview"
                                        type="button"
                                        @click="removeLogo"
                                        class="flex items-center gap-2 px-5 py-3 bg-red-50 border border-red-100 hover:bg-red-100 transition-all rounded-xl text-[10px] font-black uppercase tracking-[0.3em] text-red-500 hover:text-red-600"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Remove Logo
                                    </button>
                                    <p class="text-[9px] text-slate-400 uppercase tracking-widest italic">JPG, PNG, WebP, SVG · Max 2MB</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Enterprise Name</label>
                            <input v-model="form.site_name" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-primary px-6 py-4 text-sm font-black uppercase tracking-tight text-slate-900 transition-all rounded-xl focus:ring-0">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Company Information</label>
                            <textarea v-model="form.company_info" rows="4" class="w-full bg-slate-50 border border-slate-100 focus:border-primary px-6 py-4 text-sm font-medium tracking-tight text-slate-600 transition-all rounded-xl focus:ring-0"></textarea>
                        </div>

                        <!-- Logo Dimensions -->
                        <div class="grid grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Logo Width (px)</label>
                                <input v-model="form.logo_width" type="number" class="w-full bg-slate-50 border border-slate-100 focus:border-primary px-6 py-4 text-sm font-black text-slate-900 rounded-xl focus:ring-0">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Logo Height (px)</label>
                                <input v-model="form.logo_height" type="number" class="w-full bg-slate-50 border border-slate-100 focus:border-primary px-6 py-4 text-sm font-black text-slate-900 rounded-xl focus:ring-0">
                            </div>
                        </div>

                        <!-- Public Visibility Toggle -->
                        <div class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-xl group hover:border-primary transition-all">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-900">Show Company Name on Public Site</span>
                            <button 
                                type="button"
                                @click="form.show_company_name = !form.show_company_name"
                                :class="form.show_company_name ? 'bg-primary shadow-lg shadow-primary/30' : 'bg-slate-300'"
                                class="w-12 h-6 rounded-full relative transition-all duration-300"
                            >
                                <div :class="form.show_company_name ? 'translate-x-7' : 'translate-x-1'" class="absolute top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300"></div>
                            </button>
                        </div>

                        <!-- Branding Color removed per request -->

                        <!-- Header Branding Style -->
                        <div class="space-y-4 pt-4 border-t border-slate-100">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Header Branding Style</label>
                            <div class="grid grid-cols-1 gap-3">
                                <label v-for="style in [
                                    { id: 'logo_and_name', label: 'Show Logo + Company Name' },
                                    { id: 'only_name',     label: 'Show Only Company Name' },
                                    { id: 'only_logo',     label: 'Show Only Logo' }
                                ]" :key="style.id" class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:border-primary transition-all group">
                                    <input type="radio" v-model="form.header_style" :value="style.id" class="w-4 h-4 text-primary bg-white border-slate-200 focus:ring-0">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-900">{{ style.label }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Login Page Branding Style -->
                        <div class="space-y-4 pt-4 border-t border-slate-100">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Login Page Branding Controls</label>
                            <div class="grid grid-cols-1 gap-3">
                                <label v-for="mode in [
                                    { id: 'name_only', label: 'Show Company Name Only' },
                                    { id: 'logo_only', label: 'Show Logo Only' },
                                    { id: 'both',      label: 'Show Logo + Company Name' },
                                    { id: 'hidden',    label: 'Hide All Branding' }
                                ]" :key="mode.id" class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:border-primary transition-all group">
                                    <input type="radio" v-model="form.login_branding_mode" :value="mode.id" class="w-4 h-4 text-primary bg-white border-slate-200 focus:ring-0">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-900">{{ mode.label }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-10">
                    <div class="bg-white border border-slate-200 p-10 space-y-8 shadow-xl shadow-slate-200/50 rounded-2xl">
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 border-b border-slate-100 pb-4 italic">Operational Control</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Company Email</label>
                                <input v-model="form.contact_email" type="email" class="w-full bg-slate-50 border border-slate-100 focus:border-primary px-6 py-4 text-sm font-bold tracking-tight text-slate-900 transition-all rounded-xl focus:ring-0">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Company Phone</label>
                                <input v-model="form.contact_phone" type="tel" class="w-full bg-slate-50 border border-slate-100 focus:border-primary px-6 py-4 text-sm font-bold tracking-tight text-slate-900 transition-all rounded-xl focus:ring-0">
                            </div>
                        </div>
                        <button type="submit" :disabled="form.processing" class="group relative w-full bg-primary py-5 overflow-hidden rounded-xl shadow-lg shadow-primary/20 hover:bg-slate-900 transition-all active:scale-95">
                            <span class="relative z-10 text-[9px] font-black uppercase tracking-[0.4em] text-white transition-colors">
                                {{ form.processing ? 'Saving...' : 'Save Corporate Settings' }}
                            </span>
                        </button>
                    </div>

                    <!-- Change Password -->
                    <div class="bg-white border border-slate-200 p-10 space-y-6 shadow-xl shadow-slate-200/50 rounded-2xl">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 italic">Security Credential Reset</h3>
                            <button type="button" @click="showPasswordSection = !showPasswordSection" class="text-[9px] font-black uppercase tracking-widest px-3 py-2 border border-slate-200 hover:border-primary text-slate-400 hover:text-primary transition-all rounded-lg">
                                {{ showPasswordSection ? 'Abort' : 'Initiate Reset' }}
                            </button>
                        </div>
                        <div v-if="showPasswordSection" class="space-y-4 animate-fade-in">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Current Secret</label>
                                <input v-model="passwordForm.current_password" type="password" class="w-full bg-slate-50 border border-slate-100 focus:border-primary px-6 py-4 text-sm font-bold text-slate-900 transition-all rounded-xl shadow-inner focus:ring-0">
                                <div v-if="passwordForm.errors.current_password" class="text-[10px] text-red-500 uppercase tracking-widest">{{ passwordForm.errors.current_password }}</div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">New Secret</label>
                                <input v-model="passwordForm.password" type="password" class="w-full bg-slate-50 border border-slate-100 focus:border-primary px-6 py-4 text-sm font-bold text-slate-900 transition-all rounded-xl shadow-inner focus:ring-0">
                                <div v-if="passwordForm.errors.password" class="text-[10px] text-red-500 uppercase tracking-widest">{{ passwordForm.errors.password }}</div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Confirm New Secret</label>
                                <input v-model="passwordForm.password_confirmation" type="password" class="w-full bg-slate-50 border border-slate-100 focus:border-primary px-6 py-4 text-sm font-bold text-slate-900 transition-all rounded-xl shadow-inner focus:ring-0">
                            </div>
                            <button type="button" @click="submitPassword" :disabled="passwordForm.processing" class="group relative w-full bg-primary py-4 overflow-hidden rounded-xl shadow-lg shadow-primary/20 hover:bg-slate-900 transition-all">
                                <span class="relative z-10 text-[9px] font-black uppercase tracking-[0.4em] text-white transition-colors">
                                    {{ passwordForm.processing ? 'Reconfiguring...' : 'Deploy New Secret' }}
                                </span>
                            </button>
                        </div>
                        <div v-else class="text-[9px] text-slate-400 uppercase tracking-widest italic leading-relaxed">
                            System security protocols require periodic credential rotation. Click 'Initiate Reset' to proceed.
                        </div>
                    </div>
                </div>
            </form>

            <!-- Enterprise System Configurations -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 space-y-10">
                    <div class="bg-white border border-slate-200 p-10 space-y-8 rounded-2xl shadow-xl shadow-slate-200/50">
                        <div class="flex justify-between items-center border-b border-slate-100 pb-6">
                            <h3 class="text-xs font-black uppercase tracking-[0.4em] text-primary italic">System Logic Configuration</h3>
                            <button @click="submitConfigs" :disabled="configForm.processing" class="text-[9px] font-black uppercase tracking-widest px-4 py-3 border border-slate-200 hover:border-primary text-slate-400 hover:text-primary transition-all rounded-xl">
                                {{ configForm.processing ? 'Deploying...' : 'Deploy Updates' }}
                            </button>
                        </div>

                        <!-- Global Currency -->
                        <div class="p-8 bg-slate-50 border border-slate-100 rounded-2xl space-y-4 shadow-inner">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse shadow-[0_0_10px_rgba(22,86,209,0.4)]"></div>
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-900">Global Currency Index</label>
                            </div>
                            <select v-model="configForm.currency" class="w-full bg-white border border-slate-200 focus:border-primary px-6 py-4 text-sm font-black text-slate-900 focus:ring-0 rounded-xl transition-all shadow-sm">
                                <option v-for="opt in currencyOptions" :key="opt.code" :value="opt.code">{{ opt.label }}</option>
                            </select>
                            <p class="text-[9px] text-slate-400 uppercase tracking-widest italic">Current Index: <span class="text-primary font-black">{{ configForm.currency }}</span> — Affects all dynamic valuations.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">Project Node Types</label>
                                <textarea v-model="configForm.project_types" rows="5" class="w-full bg-slate-50 border border-slate-100 focus:border-primary px-6 py-4 text-sm font-mono text-slate-600 focus:ring-0 rounded-xl shadow-inner" placeholder="JSON Array of types..."></textarea>
                                <p class="text-[8px] text-slate-400 font-bold uppercase italic mt-1">Accepts raw JSON array protocol.</p>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">Material Categories</label>
                                <textarea v-model="configForm.material_categories" rows="5" class="w-full bg-slate-50 border border-slate-100 focus:border-primary px-6 py-4 text-sm font-mono text-slate-600 focus:ring-0 rounded-xl shadow-inner" placeholder="JSON Array of categories..."></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 pt-2">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">Date Format Protocol</label>
                                <select v-model="configForm.date_format" class="w-full bg-slate-50 border border-slate-100 focus:border-primary px-6 py-4 text-sm font-black text-slate-900 focus:ring-0 rounded-xl shadow-inner">
                                    <option value="Y-m-d">Y-m-d (ISO)</option>
                                    <option value="d/m/Y">d/m/Y (British)</option>
                                    <option value="m/d/Y">m/d/Y (US)</option>
                                    <option value="d.m.Y">d.m.Y (European)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 p-10 space-y-8 rounded-2xl shadow-xl shadow-slate-200/50 flex flex-col justify-between">
                     <div>
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 border-b border-slate-100 pb-4 italic">Infrastructure Health</h3>
                        <div class="mt-8 space-y-4">
                            <div v-for="(v, l) in { 
                                'Network Latency': '0.04ms', 
                                'Database Sync': 'Active', 
                                'Storage Load': '12.4%',
                                'Auth Entropy': 'Optimal'
                            }" :key="l" class="flex justify-between items-center py-4 border-b border-slate-50 group">
                                <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest group-hover:text-slate-900 transition-colors">{{ l }}</span>
                                <span class="text-[9px] font-black text-primary italic">{{ v }}</span>
                            </div>
                        </div>
                     </div>
                     <div class="p-8 bg-slate-50 border border-slate-100 italic text-slate-400 text-[8px] font-black uppercase leading-relaxed rounded-xl shadow-inner">
                        Notice: Operational modifications to currency or system configurations trigger immediate global updates across all terminal nodes.
                     </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
@keyframes fade-in { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
.animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
</style>
