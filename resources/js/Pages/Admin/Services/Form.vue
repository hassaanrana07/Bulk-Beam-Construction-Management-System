<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ service: Object });

const imagePreview = ref(null);
const technicalLayoutPreview = ref(null);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.featured_image = file;
        form.image_url = ''; // Clear URL if file is selected
        imagePreview.value = URL.createObjectURL(file);
    }
};

const handleLayoutImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.technical_layout_image = file;
        technicalLayoutPreview.value = URL.createObjectURL(file);
    }
};

const parseBullets = (val) => {
    if (!val) return [];
    if (Array.isArray(val)) return val;
    try { return JSON.parse(val); } catch { return []; }
};

const form = useForm({
    title:                   props.service?.title || '',
    slug:                    props.service?.slug || '',
    short_description:       props.service?.short_description || '',
    description:             props.service?.description || '',
    status:                  props.service?.status || 'published',
    is_featured:             props.service?.is_featured || false,
    is_public:               props.service?.is_public ?? true,
    is_public_visible:       props.service?.is_public_visible ?? true,
    featured_image:          null,
    image_url:               props.service?.image_url || '',
    // Capability Scope
    capability_features:     props.service?.capability_features || [],
    capability_deliverables: props.service?.capability_deliverables || [],
    capability_exclusions:   props.service?.capability_exclusions || [],
    capability_tools:        props.service?.capability_tools || [],
    capability_scope_description: props.service?.capability_scope_description || '',
    // Product Structure Analysis
    structural_type:         props.service?.structural_type || '',
    technical_breakdown:     props.service?.technical_breakdown || '',
    materials_used:          props.service?.materials_used || [],
    architecture_layout:     props.service?.architecture_layout || [],
    // Product Structure (Timeline)
    structure_description:   props.service?.structure_description || '',
    timeline_summary:        props.service?.timeline_summary || '',
    phases_details:          props.service?.phases_details || [],
    technical_layout_image:  null,
    // Operations & Vacations
    operations_description:  props.service?.operations_description || '',
    operations_bullets:      parseBullets(props.service?.operations_bullets),
    operations_timeline:     props.service?.operations_timeline || '',
    operations_team:         props.service?.operations_team || '',
    vacations_description:   props.service?.vacations_description || '',
    vacations_bullets:       parseBullets(props.service?.vacations_bullets),
    vacations_timeline:      props.service?.vacations_timeline || '',
});

const addPhase = () => {
    form.phases_details.push({ title: '', description: '' });
};
const removePhase = (index) => {
    form.phases_details.splice(index, 1);
};

const newFeature = ref('');
const addFeature = () => { if (newFeature.value.trim()) { form.capability_features.push(newFeature.value.trim()); newFeature.value = ''; } };

const newDeliverable = ref('');
const addDeliverable = () => { if (newDeliverable.value.trim()) { form.capability_deliverables.push(newDeliverable.value.trim()); newDeliverable.value = ''; } };

const newExclusion = ref('');
const addExclusion = () => { if (newExclusion.value.trim()) { form.capability_exclusions.push(newExclusion.value.trim()); newExclusion.value = ''; } };

const newTool = ref('');
const addTool = () => { if (newTool.value.trim()) { form.capability_tools.push(newTool.value.trim()); newTool.value = ''; } };

const newMaterial = ref('');
const addMaterial = () => { if (newMaterial.value.trim()) { form.materials_used.push(newMaterial.value.trim()); newMaterial.value = ''; } };

const newLayout = ref('');
const addLayout = () => { if (newLayout.value.trim()) { form.architecture_layout.push(newLayout.value.trim()); newLayout.value = ''; } };

const removeBullet = (targetArray, index) => {
    targetArray.splice(index, 1);
};

// ── legacy bullet helpers ──────────────────────────────────────
const newOpBullet = ref('');
const addOpBullet = () => {
    if (!newOpBullet.value.trim()) return;
    form.operations_bullets.push(newOpBullet.value.trim());
    newOpBullet.value = '';
};
const removeOpBullet = (index) => {
    form.operations_bullets.splice(index, 1);
};

const newVacBullet = ref('');
const addVacBullet = () => {
    if (!newVacBullet.value.trim()) return;
    form.vacations_bullets.push(newVacBullet.value.trim());
    newVacBullet.value = '';
};
const removeVacBullet = (index) => {
    form.vacations_bullets.splice(index, 1);
};

const submit = () => {
    if (props.service) {
        form.transform(data => ({
            ...data,
            _method: 'PUT'
        })).post(route('admin.services.update', props.service.id), {
            forceFormData: true,
        });
    } else {
        form.post(route('admin.services.store'), {
            forceFormData: true,
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head :title="service ? 'Engineer Capability' : 'Propose New Capability'" />

        <ModuleHeader :title="service ? `Service: ${service.title}` : 'New Capability'">
            <template #subtitle>
                <Link :href="route('admin.services.index')" class="text-[10px] font-black text-slate-400 hover:text-primary uppercase tracking-[0.3em] flex items-center gap-2 mt-2 transition-colors">
                    <span>←</span> Return to Capability Matrix
                </Link>
            </template>
        </ModuleHeader>

        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Column -->
            <div class="lg:col-span-2 space-y-10">
                <!-- Core Data -->
                <div class="bg-white border border-slate-200 p-10 space-y-8 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 border-b border-slate-100 pb-4 italic">Structural Parameters</h3>

                    <!-- Image Integration Module -->
                    <div class="space-y-4 pb-8 border-b border-slate-100">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Featured Identity Asset</label>
                        <div class="flex items-center gap-6">
                            <div class="w-32 h-32 bg-slate-50 border-2 border-slate-100 flex items-center justify-center overflow-hidden rounded-xl group relative shadow-inner">
                                <img v-if="imagePreview || form.image_url || service?.featured_image" :src="imagePreview || form.image_url || service?.featured_image" class="w-full h-full object-cover">
                                <span v-else class="text-slate-300 text-3xl font-black italic">?</span>
                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-[8px] font-black text-white uppercase tracking-widest">Live Preview</span>
                                </div>
                            </div>
                            <div class="flex-1 space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="block px-6 py-3 bg-slate-50 border-2 border-dashed border-slate-200 hover:border-primary text-slate-400 hover:text-primary text-[10px] font-black uppercase tracking-[0.3em] transition-all cursor-pointer text-center rounded-xl">
                                        Upload Asset
                                        <input type="file" class="hidden" accept="image/*" @change="handleImageChange">
                                    </label>
                                    <input v-model="form.image_url" type="url" @input="form.featured_image = null" placeholder="Or provide asset URL..." class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-3 text-[10px] font-bold text-slate-600 focus:ring-0 rounded-xl">
                                </div>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed">Visual documentation required for public capability matrix. URLs bypass local storage.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Service Label</label>
                            <input v-model="form.title" type="text" required class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-sm font-black uppercase tracking-tight text-slate-900 transition-all focus:ring-0 rounded-xl">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">URL Slug</label>
                            <input v-model="form.slug" type="text" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-xs font-bold tracking-tight text-slate-900 transition-all focus:ring-0 placeholder-slate-300 rounded-xl" placeholder="auto-generated">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Short Overview</label>
                        <textarea v-model="form.short_description" rows="3" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-sm text-slate-600 transition-all focus:ring-0 rounded-xl"></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Full Operational Description</label>
                        <textarea v-model="form.description" rows="8" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-sm leading-relaxed text-slate-600 transition-all focus:ring-0 rounded-xl"></textarea>
                    </div>
                </div>

                <!-- Capability Scope Section -->
                <div class="bg-white border border-slate-200 p-10 space-y-8 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-primary border-b border-slate-100 pb-4 italic">Capability Scope Activation</h3>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Functional Scope Description</label>
                        <textarea v-model="form.capability_scope_description" rows="4" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-sm text-slate-600 transition-all focus:ring-0 rounded-xl" placeholder="Functional breadth of this service..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Features -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">Service Features</label>
                            <div class="space-y-2">
                                <div v-for="(item, i) in form.capability_features" :key="i" class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl group hover:border-primary transition-all">
                                    <span class="flex-1 text-[10px] font-bold text-slate-600">{{ item }}</span>
                                    <button type="button" @click="removeBullet(form.capability_features, i)" class="text-red-400 hover:text-red-600 text-xs transition-colors">✕</button>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <input v-model="newFeature" @keydown.enter.prevent="addFeature" type="text" placeholder="Add feature..." class="flex-1 bg-slate-50 border-2 border-slate-100 text-[11px] text-slate-900 rounded-xl p-3 focus:border-primary focus:ring-0 transition-all">
                                <button type="button" @click="addFeature" class="bg-slate-900 hover:bg-primary px-4 text-white text-[10px] font-black uppercase rounded-xl transition-all">Add</button>
                            </div>
                        </div>

                        <!-- Deliverables -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">Included Deliverables</label>
                            <div class="space-y-2">
                                <div v-for="(item, i) in form.capability_deliverables" :key="i" class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl group hover:border-primary transition-all">
                                    <span class="flex-1 text-[10px] font-bold text-slate-600">{{ item }}</span>
                                    <button type="button" @click="removeBullet(form.capability_deliverables, i)" class="text-red-400 hover:text-red-600 text-xs transition-colors">✕</button>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <input v-model="newDeliverable" @keydown.enter.prevent="addDeliverable" type="text" placeholder="Add deliverable..." class="flex-1 bg-slate-50 border-2 border-slate-100 text-[11px] text-slate-900 rounded-xl p-3 focus:border-primary focus:ring-0 transition-all">
                                <button type="button" @click="addDeliverable" class="bg-slate-900 hover:bg-primary px-4 text-white text-[10px] font-black uppercase rounded-xl transition-all">Add</button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Exclusions -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">Excluded Items</label>
                            <div class="space-y-2">
                                <div v-for="(item, i) in form.capability_exclusions" :key="i" class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl group hover:border-primary transition-all">
                                    <span class="flex-1 text-[10px] font-bold text-slate-600">{{ item }}</span>
                                    <button type="button" @click="removeBullet(form.capability_exclusions, i)" class="text-red-400 hover:text-red-600 text-xs transition-colors">✕</button>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <input v-model="newExclusion" @keydown.enter.prevent="addExclusion" type="text" placeholder="Add exclusion..." class="flex-1 bg-slate-50 border-2 border-slate-100 text-[11px] text-slate-900 rounded-xl p-3 focus:border-primary focus:ring-0 transition-all">
                                <button type="button" @click="addExclusion" class="bg-slate-900 hover:bg-primary px-4 text-white text-[10px] font-black uppercase rounded-xl transition-all">Add</button>
                            </div>
                        </div>

                        <!-- Tools -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">Tools / Technologies Used</label>
                            <div class="space-y-2">
                                <div v-for="(item, i) in form.capability_tools" :key="i" class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl group hover:border-primary transition-all">
                                    <span class="flex-1 text-[10px] font-bold text-slate-600">{{ item }}</span>
                                    <button type="button" @click="removeBullet(form.capability_tools, i)" class="text-red-400 hover:text-red-600 text-xs transition-colors">✕</button>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <input v-model="newTool" @keydown.enter.prevent="addTool" type="text" placeholder="Add tool..." class="flex-1 bg-slate-50 border-2 border-slate-100 text-[11px] text-slate-900 rounded-xl p-3 focus:border-primary focus:ring-0 transition-all">
                                <button type="button" @click="addTool" class="bg-slate-900 hover:bg-primary px-4 text-white text-[10px] font-black uppercase rounded-xl transition-all">Add</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Structure Analysis Section -->
                <div class="bg-white border border-slate-200 p-10 space-y-8 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-900 border-b border-slate-100 pb-4 italic">Product Structure Analysis</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Structural Type</label>
                            <input v-model="form.structural_type" type="text" placeholder="e.g. Residential Building" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-sm text-slate-900 rounded-xl focus:ring-0 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Technical Breakdown</label>
                            <textarea v-model="form.technical_breakdown" rows="2" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-sm text-slate-600 rounded-xl focus:ring-0 transition-all"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">Materials Used</label>
                            <div class="space-y-2">
                                <div v-for="(item, i) in form.materials_used" :key="i" class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl hover:border-primary transition-all">
                                    <span class="flex-1 text-[10px] font-bold text-slate-600">{{ item }}</span>
                                    <button type="button" @click="removeBullet(form.materials_used, i)" class="text-red-400 hover:text-red-600 text-xs">✕</button>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <input v-model="newMaterial" @keydown.enter.prevent="addMaterial" type="text" placeholder="Add material..." class="flex-1 bg-slate-50 border-2 border-slate-100 text-[11px] text-slate-900 rounded-xl p-3 focus:border-primary focus:ring-0 transition-all">
                                <button type="button" @click="addMaterial" class="bg-slate-900 hover:bg-primary px-4 text-white text-[10px] font-black uppercase rounded-xl transition-all">Add</button>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">Architecture Layout</label>
                            <div class="space-y-2">
                                <div v-for="(item, i) in form.architecture_layout" :key="i" class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl hover:border-primary transition-all">
                                    <span class="flex-1 text-[10px] font-bold text-slate-600">{{ item }}</span>
                                    <button type="button" @click="removeBullet(form.architecture_layout, i)" class="text-red-400 hover:text-red-600 text-xs">✕</button>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <input v-model="newLayout" @keydown.enter.prevent="addLayout" type="text" placeholder="Add layout note..." class="flex-1 bg-slate-50 border-2 border-slate-100 text-[11px] text-slate-900 rounded-xl p-3 focus:border-primary focus:ring-0 transition-all">
                                <button type="button" @click="addLayout" class="bg-slate-900 hover:bg-primary px-4 text-white text-[10px] font-black uppercase rounded-xl transition-all">Add</button>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 pt-6 border-t border-slate-100">
                        <label class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 block italic mb-4">Timeline & Deployment Context</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[8px] font-black uppercase text-slate-500">Timeline Summary</label>
                                <input v-model="form.timeline_summary" type="text" class="w-full bg-slate-50 border-2 border-slate-100 text-sm text-slate-900 px-4 py-3 rounded-xl focus:border-primary focus:ring-0 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[8px] font-black uppercase text-slate-500 block mb-2">Technical Layout Blueprint</label>
                                <div class="flex items-center gap-4">
                                    <div class="w-20 h-20 bg-white border-2 border-slate-100 rounded-xl flex items-center justify-center overflow-hidden shadow-inner">
                                        <img v-if="technicalLayoutPreview || service?.technical_layout_image" :src="technicalLayoutPreview || service?.technical_layout_image" class="w-full h-full object-cover">
                                        <span v-else class="text-slate-200 text-xl font-black italic">!</span>
                                    </div>
                                    <label class="flex-1 px-4 py-3 bg-slate-50 border-2 border-slate-200 hover:border-primary text-slate-400 hover:text-primary text-[9px] font-black uppercase tracking-widest cursor-pointer transition-all rounded-xl text-center">
                                        Select Layout Asset
                                        <input type="file" class="hidden" accept="image/*" @change="handleLayoutImageChange">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <textarea v-model="form.structure_description" rows="3" class="w-full bg-slate-50 border-2 border-slate-100 text-sm text-slate-600 mt-4 rounded-xl p-4 focus:border-primary focus:ring-0 transition-all" placeholder="General structural description..."></textarea>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-slate-100">
                        <div class="flex justify-between items-center">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">Deployment Phases</label>
                            <button type="button" @click="addPhase" class="text-[8px] font-black uppercase bg-slate-50 px-4 py-2 rounded-lg text-slate-400 border border-slate-200 hover:border-primary hover:text-primary transition-all shadow-sm">+ Add Phase</button>
                        </div>
                        <div class="space-y-6">
                            <div v-for="(phase, index) in form.phases_details" :key="index" class="p-8 bg-slate-50 border-2 border-slate-100 rounded-2xl relative group flex items-center gap-10 hover:border-primary transition-all">
                                <button type="button" @click="removePhase(index)" class="absolute -top-3 -right-3 w-8 h-8 bg-white border border-slate-200 text-red-500 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-lg z-10 hover:scale-110">✕</button>
                                
                                <div class="w-16 h-16 bg-white border-2 border-slate-100 flex items-center justify-center rounded-2xl flex-shrink-0 shadow-sm">
                                    <span class="text-xl font-black italic text-primary">0{{ index + 1 }}</span>
                                </div>
 
                                <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                                    <div class="md:col-span-1">
                                        <label class="text-[8px] font-black uppercase text-slate-400 mb-1 block">Phase Title</label>
                                        <input v-model="phase.title" type="text" class="w-full bg-white border-slate-200 text-xs font-black uppercase tracking-widest text-slate-900 rounded-lg focus:border-primary focus:ring-0">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-[8px] font-black uppercase text-slate-400 mb-1 block">Phase Orchestration Details</label>
                                        <textarea v-model="phase.description" rows="1" class="w-full bg-white border-slate-200 text-xs text-slate-600 rounded-lg focus:border-primary focus:ring-0"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Operations Section -->
                <div class="bg-white border border-slate-200 p-10 space-y-8 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-primary/70 border-b border-slate-100 pb-4 italic">Operations Module</h3>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Operations Description</label>
                        <textarea v-model="form.operations_description" rows="4" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-sm text-slate-600 transition-all focus:ring-0 rounded-xl" placeholder="Describe the operations scope..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Operations Timeline</label>
                            <input v-model="form.operations_timeline" type="text" placeholder="e.g. 8–12 weeks" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-sm text-slate-900 transition-all focus:ring-0 rounded-xl">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Assigned Team</label>
                            <input v-model="form.operations_team" type="text" placeholder="e.g. Structural Engineers" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-sm text-slate-900 transition-all focus:ring-0 rounded-xl">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Operational Protocols (Bullets)</label>
                        <div class="space-y-2">
                            <div v-for="(bullet, i) in form.operations_bullets" :key="i"
                                class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl hover:border-primary transition-all group">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full flex-shrink-0"></span>
                                <span class="flex-1 text-sm text-slate-600">{{ bullet }}</span>
                                <button type="button" @click="removeOpBullet(i)" class="text-red-400 hover:text-red-600 text-xs font-black">✕</button>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <input v-model="newOpBullet" type="text" @keydown.enter.prevent="addOpBullet" placeholder="Add protocol..." class="flex-1 bg-slate-50 border-2 border-slate-100 focus:border-primary px-4 py-3 text-sm text-slate-900 rounded-xl focus:ring-0 transition-all">
                            <button type="button" @click="addOpBullet" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase rounded-xl hover:bg-primary transition-all shadow-md">Add</button>
                        </div>
                    </div>
                </div>

                <!-- Vacations Section -->
                <div class="bg-white border border-slate-200 p-10 space-y-8 shadow-xl shadow-slate-200/50 rounded-2xl">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400/70 border-b border-slate-100 pb-4 italic">Vacations & Leave Module</h3>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Vacations Description</label>
                        <textarea v-model="form.vacations_description" rows="4" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-sm text-slate-600 transition-all focus:ring-0 rounded-xl" placeholder="Describe the vacation/leave policy..."></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Vacations Timeline</label>
                        <input v-model="form.vacations_timeline" type="text" placeholder="e.g. Annual / Q2 Leave Window" class="w-full bg-slate-50 border-2 border-slate-100 focus:border-primary px-6 py-4 text-sm text-slate-900 transition-all focus:ring-0 rounded-xl">
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Policy Details (Bullets)</label>
                        <div class="space-y-2">
                            <div v-for="(bullet, i) in form.vacations_bullets" :key="i"
                                class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-xl hover:border-primary transition-all group">
                                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full flex-shrink-0"></span>
                                <span class="flex-1 text-sm text-slate-600">{{ bullet }}</span>
                                <button type="button" @click="removeVacBullet(i)" class="text-red-400 hover:text-red-600 text-xs font-black">✕</button>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <input v-model="newVacBullet" type="text" @keydown.enter.prevent="addVacBullet" placeholder="Add policy detail..." class="flex-1 bg-slate-50 border-2 border-slate-100 focus:border-primary px-4 py-3 text-sm text-slate-900 rounded-xl focus:ring-0 transition-all">
                            <button type="button" @click="addVacBullet" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase rounded-xl hover:bg-slate-600 transition-all shadow-md">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Controls -->
            <div class="lg:col-span-1 space-y-10">
                <div class="bg-white border border-slate-200 p-10 space-y-10 shadow-xl shadow-slate-200/50 rounded-2xl sticky top-32">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-slate-400 italic">Operational Logic</h3>

                    <div class="space-y-4">
                        <label class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 cursor-pointer hover:border-primary transition-all group rounded-xl shadow-inner">
                            <input type="checkbox" v-model="form.is_public" class="w-6 h-6 bg-white border-slate-200 text-primary focus:ring-0 rounded-lg cursor-pointer">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 group-hover:text-slate-900 transition-colors">Internal Public</span>
                                <span class="text-[8px] text-slate-300 font-bold uppercase">Legacy Access</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 cursor-pointer hover:border-primary transition-all group rounded-xl shadow-[0_0_15px_rgba(59,130,246,0.1)]">
                            <input type="checkbox" v-model="form.is_public_visible" class="w-6 h-6 bg-white border-slate-200 text-primary focus:ring-0 rounded-lg cursor-pointer">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-primary group-hover:text-slate-900 transition-colors">Web Visibility</span>
                                <span class="text-[8px] text-slate-400 font-black uppercase">Live on SiteMatrix Public</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 cursor-pointer hover:border-primary transition-all group rounded-xl shadow-inner">
                            <input type="checkbox" v-model="form.is_featured" class="w-6 h-6 bg-white border-slate-200 text-primary focus:ring-0 rounded-lg cursor-pointer">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 group-hover:text-slate-900 transition-colors">Featured Offering</span>
                        </label>
                    </div>

                    <div class="space-y-3">
                        <label v-for="status in ['draft', 'published']" :key="status"
                            class="flex items-center gap-4 p-5 bg-slate-50 border border-slate-100 cursor-pointer hover:border-primary transition-all group rounded-xl shadow-sm">
                            <input type="radio" v-model="form.status" :value="status" class="w-5 h-5 bg-white border-slate-200 text-primary focus:ring-0 cursor-pointer">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 group-hover:text-slate-900 transition-colors">{{ status }}</span>
                        </label>
                    </div>

                    <button type="submit" :disabled="form.processing" class="group relative w-full bg-primary py-6 overflow-hidden transition-all active:scale-95 disabled:opacity-50 rounded-2xl shadow-lg shadow-primary/20">
                        <span class="relative z-10 text-[10px] font-black uppercase tracking-[0.4em] text-white">
                            {{ form.processing ? 'Transmitting...' : 'Commit Capability' }}
                        </span>
                        <div class="absolute inset-0 bg-slate-900 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    </button>
                </div>
            </div>
        </form>
    </AdminLayout>
</template>
