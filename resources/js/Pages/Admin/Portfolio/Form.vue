<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    project: Object
});

const imagePreview = ref(null);
const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.featured_image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const form = useForm({
    title: props.project?.title || '',
    slug: props.project?.slug || '',
    short_description: props.project?.short_description || '',
    project_type: props.project?.project_type || 'Residential',
    location: props.project?.location || '',
    budget_range: props.project?.budget_range || '',
    total_budget: props.project?.total_budget || 0,
    expected_revenue: props.project?.expected_revenue || 0,
    received_payment: props.project?.received_payment || 0,
    execution_status: props.project?.execution_status || 'In Progress',
    start_date: props.project?.start_date || '',
    completion_date: props.project?.completion_date || '',
    status: props.project?.status || 'published',
    is_featured: props.project?.is_featured || false,
    is_public: props.project?.is_public ?? true,
    is_public_visible: props.project?.is_public_visible ?? true,
    featured_image: null,
    image_url: props.project?.image_url || '',
    // Case Study fields
    case_study_category: props.project?.case_study_category || '',
    case_study_scope: props.project?.case_study_scope || '',
    case_study_sector: props.project?.case_study_sector || '',
    cs_phase_1: props.project?.cs_phase_1 || '',
    cs_phase_2: props.project?.cs_phase_2 || '',
    cs_phase_3: props.project?.cs_phase_3 || '',
    cs_phase_4: props.project?.cs_phase_4 || '',
    cs_phase_5: props.project?.cs_phase_5 || '',
    cs_duration_weeks: props.project?.cs_duration_weeks || 0,
    cs_team: props.project?.cs_team || '',
    cs_total_value: props.project?.cs_total_value || '',
    // Structure Analysis
    structural_features: props.project?.structural_features || [],
    base_structure: props.project?.base_structure || '',
    foundation_type: props.project?.foundation_type || '',
    total_floors: props.project?.total_floors || 0,
    floor_composition: props.project?.floor_composition || '',
    capabilities: props.project?.capabilities || [],
    functional_features: props.project?.functional_features || [],
    technology_used: props.project?.technology_used || '',
    construction_technology: props.project?.construction_technology || '',
    tools_used: props.project?.tools_used || [],
    framework_type: props.project?.framework_type || '',
});

const newStrFeature = ref('');
const addStrFeature = () => { if (newStrFeature.value.trim()) { form.structural_features.push(newStrFeature.value.trim()); newStrFeature.value = ''; } };

const newCapability = ref('');
const addCapability = () => { if (newCapability.value.trim()) { form.capabilities.push(newCapability.value.trim()); newCapability.value = ''; } };

const newFuncFeature = ref('');
const addFuncFeature = () => { if (newFuncFeature.value.trim()) { form.functional_features.push(newFuncFeature.value.trim()); newFuncFeature.value = ''; } };

const newTool = ref('');
const addTool = () => { if (newTool.value.trim()) { form.tools_used.push(newTool.value.trim()); newTool.value = ''; } };

const removeBullet = (arr, i) => arr.splice(i, 1);

const calculatedPending = computed(() => {
    return (form.expected_revenue || 0) - (form.received_payment || 0);
});

const calculatedProfit = computed(() => {
    return (form.received_payment || 0) - (form.total_budget || 0);
});

const formatCurrency = (val) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(val);
};

const submit = () => {
    if (props.project) {
        form.transform(data => ({
            ...data,
            _method: 'PUT'
        })).post(route('admin.portfolios.update', props.project.id), {
            forceFormData: true,
        });
    } else {
        form.post(route('admin.portfolios.store'), {
            forceFormData: true,
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="project ? 'Audit Project Logic' : 'Initiate New Deployment'" />

        <ModuleHeader :title="project ? `Project: ${project.title}` : 'New Project Deployment'">
            <template #subtitle>
                <Link :href="route('admin.portfolios.index')" class="text-[10px] font-black text-slate-600 hover:text-orange-600 uppercase tracking-[0.3em] flex items-center gap-2 mt-2 transition-colors">
                    <span>←</span> Return to Project Library
                </Link>
            </template>
        </ModuleHeader>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-10">
                <!-- Structural Identity -->
                <div class="bg-white border border-slate-200 p-10 space-y-10 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 border-b border-slate-100 pb-4 italic">Structural Identity</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Project Label</label>
                            <input v-model="form.title" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-sm font-black uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                            <p v-if="form.errors.title" class="text-red-500 text-[9px] font-black uppercase mt-1">{{ form.errors.title }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Asset Slug (URL)</label>
                            <input v-model="form.slug" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold tracking-tight text-slate-900 transition-all focus:ring-0 placeholder-slate-300 rounded-xl" placeholder="auto-generated">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Segment Classification</label>
                            <select v-model="form.project_type" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                                <option>Residential</option>
                                <option>Commercial</option>
                                <option>Infrastructure</option>
                                <option>Industrial</option>
                            </select>
                            <p v-if="form.errors.project_type" class="text-red-500 text-[9px] font-black uppercase mt-1">{{ form.errors.project_type }}</p>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Execution Status</label>
                            <select v-model="form.execution_status" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                                <option>Planning</option>
                                <option>In Progress</option>
                                <option>Completed</option>
                                <option>On Hold</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Location Vector</label>
                            <input v-model="form.location" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Deployment Start Date</label>
                            <input v-model="form.start_date" type="date" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-sm font-bold tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                        </div>
                        <div class="space-y-2" v-if="form.execution_status === 'Completed'">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Completion Date</label>
                            <input v-model="form.completion_date" type="date" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-sm font-bold tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                        </div>
                    </div>

                    <div class="space-y-4 pb-8 border-b border-slate-100">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Featured Global Asset Image</label>
                        <div class="flex flex-col gap-6">
                            <div class="w-full h-80 bg-slate-50 border border-slate-100 flex items-center justify-center overflow-hidden rounded-2xl group relative shadow-inner">
                                <img v-if="imagePreview || project?.featured_image" :src="imagePreview || project?.featured_image" class="w-full h-full object-cover">
                                <span v-else class="text-slate-200 text-3xl font-black italic">?</span>
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                    <span class="text-[10px] font-black text-white uppercase tracking-widest italic">Target Showcase Image</span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Option 1: File Upload</label>
                                    <label class="block px-8 py-4 bg-white border border-slate-200 hover:border-orange-600 text-slate-500 hover:text-orange-600 text-[10px] font-black uppercase tracking-[0.2em] transition-all cursor-pointer text-center rounded-xl shadow-sm">
                                        Upload Local Asset
                                        <input type="file" class="hidden" accept="image/*" @change="handleImageChange">
                                    </label>
                                </div>
                                <div class="space-y-4">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400">Option 2: Image URL</label>
                                    <input v-model="form.image_url" type="url" placeholder="https://example.com/image.jpg" class="w-full bg-white border border-slate-200 focus:border-orange-600 px-6 py-4 text-xs font-bold text-slate-900 transition-all rounded-xl shadow-sm focus:ring-0">
                                </div>
                            </div>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em] leading-relaxed text-center italic">High-resolution visualization required for global showcase matrix. Max 2MB.</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Architectural Brief (Short Description)</label>
                        <textarea v-model="form.short_description" rows="3" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-sm font-medium tracking-tight text-slate-700 transition-all focus:ring-0 rounded-xl"></textarea>
                    </div>
                </div>

                <!-- Structure Analysis -->
                <div class="bg-white border border-slate-200 p-10 space-y-10 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-900 border-b border-slate-100 pb-4 italic">Structure Analysis Protocol</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Base Structure</label>
                            <input v-model="form.base_structure" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold text-slate-900 transition-all rounded-xl shadow-inner" placeholder="e.g. Reinforced Concrete">
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Foundation Type</label>
                            <input v-model="form.foundation_type" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold text-slate-900 transition-all rounded-xl shadow-inner" placeholder="e.g. Deep Pile Foundation">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Total Floors</label>
                            <input v-model="form.total_floors" type="number" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold text-slate-900 transition-all rounded-xl">
                        </div>
                        <div class="space-y-4 md:col-span-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Floor Composition</label>
                            <input v-model="form.floor_composition" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold text-slate-900 transition-all rounded-xl" placeholder="e.g. 2 Basement, G+12 Residential">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Structural Features -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block italic">Structural Features</label>
                            <div class="space-y-2">
                                <div v-for="(feat, i) in form.structural_features" :key="i" class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl">
                                    <span class="flex-1 text-[10px] font-bold text-slate-600">{{ feat }}</span>
                                    <button type="button" @click="removeBullet(form.structural_features, i)" class="text-red-500 text-xs">✕</button>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <input v-model="newStrFeature" @keydown.enter.prevent="addStrFeature" type="text" placeholder="Add feature..." class="flex-1 bg-white border-slate-200 text-[10px] text-slate-900 rounded-lg p-2 focus:border-orange-600 focus:ring-0">
                                <button type="button" @click="addStrFeature" class="bg-slate-900 px-4 text-white text-[9px] font-black uppercase rounded-lg transition-colors hover:bg-orange-600">Add</button>
                            </div>
                        </div>

                        <!-- Product Features / Capabilities -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block italic">Product Capabilities</label>
                            <div class="space-y-2">
                                <div v-for="(cap, i) in form.capabilities" :key="i" class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl">
                                    <span class="flex-1 text-[10px] font-bold text-slate-600">{{ cap }}</span>
                                    <button type="button" @click="removeBullet(form.capabilities, i)" class="text-red-500 text-xs">✕</button>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <input v-model="newCapability" @keydown.enter.prevent="addCapability" type="text" placeholder="Add capability..." class="flex-1 bg-white border-slate-200 text-[10px] text-slate-900 rounded-lg p-2 focus:border-orange-600 focus:ring-0">
                                <button type="button" @click="addCapability" class="bg-slate-900 px-4 text-white text-[9px] font-black uppercase rounded-lg transition-colors hover:bg-orange-600">Add</button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-slate-100">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Technology Used</label>
                            <input v-model="form.technology_used" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold text-slate-900 transition-all rounded-xl" placeholder="e.g. BIM, Smart Sensors">
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Construction Tech</label>
                            <input v-model="form.construction_technology" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold text-slate-900 transition-all rounded-xl" placeholder="e.g. Prefabricated Modules">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block italic">Tools Used</label>
                            <div class="space-y-2">
                                <div v-for="(tool, i) in form.tools_used" :key="i" class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl">
                                    <span class="flex-1 text-[10px] font-bold text-slate-600">{{ tool }}</span>
                                    <button type="button" @click="removeBullet(form.tools_used, i)" class="text-red-500 text-xs">✕</button>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <input v-model="newTool" @keydown.enter.prevent="addTool" type="text" placeholder="Add tool..." class="flex-1 bg-white border-slate-200 text-[10px] text-slate-900 rounded-lg p-2 focus:border-orange-600 focus:ring-0">
                                <button type="button" @click="addTool" class="bg-slate-900 px-4 text-white text-[9px] font-black uppercase rounded-lg transition-colors hover:bg-orange-600">Add</button>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Framework Type</label>
                            <input v-model="form.framework_type" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold text-slate-900 transition-all rounded-xl" placeholder="e.g. Steel Frame">
                        </div>
                    </div>
                </div>

                <!-- Case Study Specifications -->
                <div class="bg-white border border-slate-200 p-10 space-y-10 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-orange-600 border-b border-slate-100 pb-4 italic">Case Study Specifications</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Case Category</label>
                            <input v-model="form.case_study_category" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl" placeholder="e.g. Custom Home Building">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Project Scope</label>
                            <input v-model="form.case_study_scope" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl" placeholder="e.g. Full Construction">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Business Sector</label>
                            <input v-model="form.case_study_sector" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl" placeholder="e.g. Private Residential">
                        </div>
                    </div>

                    <div class="space-y-8">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 italic block border-b border-slate-100 pb-2">Execution Timeline Phases</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div v-for="i in 5" :key="i" class="space-y-2">
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500">Phase {{ i }} Instruction</label>
                                <input v-model="form['cs_phase_' + i]" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl" :placeholder="'Phase ' + i + ' details...'">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-6 border-t border-slate-100">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Duration (Weeks)</label>
                            <input v-model="form.cs_duration_weeks" type="number" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-black tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Assigned Team</label>
                            <input v-model="form.cs_team" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl" placeholder="e.g. Elite Design Group">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Total Assessment Value</label>
                            <input v-model="form.cs_total_value" type="text" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl" placeholder="e.g. $2.4M Deployment">
                        </div>
                    </div>
                </div>

                <!-- Financial Architecture -->
                <div class="bg-white border border-slate-200 p-10 space-y-10 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 border-b border-slate-100 pb-4 italic">Financial Architecture</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block italic text-orange-600/60">Total Budget</label>
                            <input v-model="form.total_budget" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-lg font-black tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block italic text-orange-600/60">Expected Revenue</label>
                            <input v-model="form.expected_revenue" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-lg font-black tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block italic text-orange-600">Received Payment</label>
                            <input v-model="form.received_payment" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-100 focus:border-orange-600 px-6 py-4 text-lg font-black tracking-tight text-orange-600 transition-all focus:ring-0 rounded-xl">
                            <p v-if="form.errors.received_payment" class="text-red-500 text-[9px] font-black uppercase mt-1">{{ form.errors.received_payment }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-slate-100">
                        <div class="p-6 bg-slate-50 border border-slate-100 rounded-xl shadow-inner">
                            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mb-1">Pending Payment (Auto)</p>
                            <p class="text-2xl font-black text-slate-900 italic tracking-tighter">{{ formatCurrency(calculatedPending) }}</p>
                        </div>
                        <div class="p-6 bg-slate-50 border border-slate-100 rounded-xl shadow-inner">
                            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mb-1">Project Profit (Auto)</p>
                            <p class="text-2xl font-black italic tracking-tighter" :class="calculatedProfit >= 0 ? 'text-emerald-500' : 'text-red-500'">{{ formatCurrency(calculatedProfit) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-10">
                <div class="bg-white border border-slate-200 p-10 space-y-10 shadow-xl shadow-slate-200/50 rounded-2xl sticky top-32">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 italic border-b border-slate-100 pb-4">Project Logic</h3>
                    
                    <div class="space-y-4">
                        <label class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:border-orange-600 transition-all group">
                            <input type="checkbox" v-model="form.is_public" class="w-5 h-5 bg-white border-slate-200 text-orange-600 focus:ring-0 rounded-lg cursor-pointer">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 group-hover:text-slate-900 transition-colors">Internal Public</span>
                                <span class="text-[8px] text-slate-400 font-bold uppercase">Legacy System Flag</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:border-orange-600 transition-all group shadow-sm">
                            <input type="checkbox" v-model="form.is_public_visible" class="w-5 h-5 bg-white border-slate-200 text-orange-600 focus:ring-0 rounded-lg cursor-pointer">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 group-hover:text-slate-900 transition-colors">Web Visibility</span>
                                <span class="text-[8px] text-slate-400 font-bold uppercase">Live on Public Library</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:border-orange-600 transition-all group">
                            <input type="checkbox" v-model="form.is_featured" class="w-5 h-5 bg-white border-slate-200 text-orange-600 focus:ring-0 rounded-lg cursor-pointer">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 group-hover:text-slate-900 transition-colors">Premium Showcase</span>
                        </label>
                    </div>

                    <div class="space-y-4 border-t border-slate-100 pt-6">
                        <label v-for="status in ['draft', 'published', 'archived']" :key="status" class="flex items-center gap-4 p-4 bg-slate-50 border border-slate-100 rounded-xl cursor-pointer hover:border-slate-300 transition-all group">
                            <input type="radio" v-model="form.status" :value="status" class="w-4 h-4 bg-white border-slate-200 text-orange-600 focus:ring-0 cursor-pointer">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 group-hover:text-slate-900 transition-colors">{{ status }}</span>
                        </label>
                    </div>

                    <button type="submit" :disabled="form.processing" class="group relative w-full bg-slate-900 py-6 overflow-hidden rounded-2xl transition-all active:scale-95 disabled:opacity-50 shadow-2xl shadow-slate-900/10">
                        <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.4em] text-white transition-colors">
                            {{ form.processing ? 'Transmitting Data...' : 'Deploy Data Update' }}
                        </span>
                        <div class="absolute inset-0 bg-orange-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    </button>
                    
                    <div class="pt-8 border-t border-slate-100">
                        <h3 class="text-[9px] font-black uppercase tracking-[0.4em] text-orange-600 mb-4 italic">Case Study Protocol</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed">
                            Case studies are primary credibility vectors. Ensure high-resolution asset imagery is uploaded via local storage or external URL prior to publication.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>
