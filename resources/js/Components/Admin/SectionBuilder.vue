<script setup>
import { ref, watch } from 'vue';
import draggable from 'vuedraggable';

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['update:modelValue']);

const sections = ref([...props.modelValue]);

const sectionTypes = [
    { type: 'hero', label: 'Hero Banner', icon: 'M4 4h16v16H4V4zm2 2v12h12V6H6zm3 3h6v2H9V9z' },
    { type: 'credibility', label: 'Credibility Strip', icon: 'M19 3H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zM9 17H7v-2h2v2zm0-4H7v-2h2v2zm0-4H7V7h2v2zm10 8H11v-2h8v2zm0-4H11v-2h8v2zm0-4H11V7h8v2z' },
    { type: 'services_overview', label: 'Services Grid', icon: 'M4 4h16v16H4V4zm2 2v12h12V6H6zm3 3h6v2H9V9zm0 4h6v2H9v-2z' },
    { type: 'featured_projects', label: 'Project Showcase', icon: 'M19 19H5V5h7V3H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2v-7h-2v7z M14 3v2h3.586l-9.293 9.293 1.414 1.414L19 6.414V10h2V3h-7z' },
    { type: 'why_choose_us', label: 'Value Propositions', icon: 'M20 18c0 1.103-.897 2-2 2H6c-1.103 0-2-.897-2-2V7c0-1.103.897-2 2-2h12c1.103 0 2 .897 2 2v11zM6 7v11h12V7H6zm3 3h6v2H9v-2zm0 4h6v2H9v-4z' },
    { type: 'cta', label: 'Call to Action', icon: 'M13 13h1v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7h1' },
    { type: 'testimonials', label: 'Client Feedback', icon: 'M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zM8.5 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm7 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zM12 18c-2.28 0-4.22-1.46-4.9-3.5h9.8c-.68 2.04-2.62 3.5-4.9 3.5z' },
    { type: 'story', label: 'Company Story', icon: 'M19 3H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zm0 16H5V5h14v14zM7 7h10v2H7V7zm0 4h10v2H7v-2zm0 4h7v2H7v-2z' },
    { type: 'mission_values', label: 'Mission & Values', icon: 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5' },
    { type: 'leadership', label: 'Leadership Grid', icon: 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z' },
    { type: 'timeline', label: 'Growth Timeline', icon: 'M21 15.75c0-.414-.336-.75-.75-.75h-1.5v-1.5c0-.414-.336-.75-.75-.75h-1.5v-1.5c0-.414-.336-.75-.75-.75h-1.5v-1.5c0-.414-.336-.75-.75-.75h-1.5v-1.5c0-.414-.336-.75-.75-.75h-1.5v-1.5c0-.414-.336-.75-.75-.75h-1.5V3.75c0-.414-.336-.75-.75-.75s-.75.336-.75.75v10.5H3.75c-.414 0-.75.336-.75.75s.336.75.75.75h10.5v1.5h-1.5c-.414 0-.75.336-.75.75s.336.75.75.75h1.5v1.5h-1.5c-.414 0-.75.336-.75.75s.336.75.75.75H15v1.5h-1.5c-.414 0-.75.336-.75.75s.336.75.75.75h1.5v1.5h-1.5c-.414 0-.75.336-.75.75s.336.75.75.75h1.5v1.5h-1.5c-.414 0-.75.336-.75.75s.336.75.75.75H20.25c.414 0 .75-.336.75-.75s-.336-.75-.75-.75h-1.5v-1.5h1.5c.414 0 .75-.336.75-.75s-.336-.75-.75-.75h-1.5v-1.5h1.5c.414 0 .75-.336.75-.75s-.336-.75-.75-.75h-1.5v-1.5h1.5c.414 0 .75-.336.75-.75s-.336-.75-.75-.75h-1.5v-1.5h1.5c.414 0 .75-.336.75-.75s-.336-.75-.75-.75z' },
    { type: 'certifications', label: 'Certifications', icon: 'M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z' },
    { type: 'before_after', label: 'Before/After Slider', icon: 'M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z' },
    { type: 'safety', label: 'Safety Standards', icon: 'M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2zm0-6h2v4h-2z' },
    { type: 'service_list', label: 'Dynamic Service List', icon: 'M4 4h16boldv16H4V4zm2 2v12h12V6H6zm3 3h6v2H9V9zm0 4h6v2H9v-2z' },
    { type: 'project_list', label: 'Dynamic Project List', icon: 'M23 18V6a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2h18a2 2 0 002-2zM8.5 12.5l2.5 3 3.5-4.5 4.5 6H5l3.5-4.5z' },
    { type: 'blog_list', label: 'Dynamic Blog List', icon: 'M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 18H6V4h12v16zm-2-14H8v2h8V6zm-4 4H8v2h4v-2zm4 0h-2v2h2v-2zm-4 4H8v2h4v-2zm4 0h-2v2h2v-2z' },
    { type: 'pricing', label: 'Estimation/Pricing', icon: 'M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z' },
];

const addSection = (type) => {
    sections.value.push({
        id: Str_uid(),
        type,
        content: { 
            title: '', 
            subtitle: '', 
            description: '',
            button_text: '',
            button_link: '',
            image: '',
            image_url: '',
            features: []
        },
        settings: {
            background: 'default',
            padding: 'normal'
        }
    });
};

const removeSection = (index) => {
    if (confirm('Eradicate this structural node?')) {
        sections.value.splice(index, 1);
    }
};

const Str_uid = () => Math.random().toString(36).substring(2, 11).toUpperCase();

watch(sections, (newVal) => {
    emit('update:modelValue', newVal);
}, { deep: true });

const handleSectionImage = (e, index) => {
    const file = e.target.files[0];
    if (file) {
        sections.value[index].content.image = file;
    }
};

const previewFile = (file) => {
    if (!file) return '';
    return URL.createObjectURL(file);
};

const resolveImage = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    return path.startsWith('/') ? path : '/storage/' + path;
};

watch(() => props.modelValue, (newVal) => {
    if (JSON.stringify(newVal) !== JSON.stringify(sections.value)) {
        sections.value = [...newVal];
    }
}, { deep: true });
</script>

<template>
    <div class="space-y-8">
        <!-- Section Assembly -->
        <draggable 
            v-model="sections" 
            item-key="id"
            handle=".drag-handle"
            class="space-y-6"
            ghost-class="opacity-50"
        >
            <template #item="{ element, index }">
                <div class="bg-white border border-slate-200 group shadow-sm overflow-hidden rounded-xl">
                    
                    <!-- Section Header/Controls -->
                    <div class="flex justify-between items-center px-8 py-4 bg-slate-50 border-b border-slate-100">
                        <div class="flex items-center gap-4">
                            <div class="drag-handle cursor-move w-8 h-8 bg-white flex items-center justify-center border border-slate-200 hover:border-primary transition-colors rounded-lg">
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-primary" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M10 9h4V7h-4v2zm0 4h4v-2h-4v2zm0 4h4v-2h-4v2zm-7-8h4V7H3v2zm0 4h4v-2H3v2zm0 4h4v-2H3v2z" />
                                </svg>
                            </div>
                            <div class="w-8 h-8 bg-primary/10 flex items-center justify-center rounded-lg">
                                <span class="text-[10px] font-black text-primary">{{ index + 1 }}</span>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-900 italic">
                                {{ sectionTypes.find(t => t.type === element.type)?.label || 'GENERIC_NODE' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-6">
                            <button @click="removeSection(index)" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all rounded-lg">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6L6 18M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Field Inputs -->
                    <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Heading Factor</label>
                                <input v-model="element.content.title" type="text" class="w-full bg-white border border-slate-200 focus:border-primary px-5 py-4 text-xs font-bold tracking-tight text-slate-900 transition-all rounded-xl focus:ring-0">
                            </div>
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Sub-Heading / Alt Logic</label>
                                <textarea v-model="element.content.subtitle" rows="2" class="w-full bg-white border border-slate-200 focus:border-primary px-5 py-4 text-xs font-bold tracking-tight text-slate-900 transition-all rounded-xl focus:ring-0"></textarea>
                            </div>
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Narrative / Description</label>
                                <textarea v-model="element.content.description" rows="4" class="w-full bg-white border border-slate-200 focus:border-primary px-5 py-4 text-xs font-medium tracking-tight text-slate-600 transition-all rounded-xl focus:ring-0"></textarea>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Action Text</label>
                                    <input v-model="element.content.button_text" type="text" class="w-full bg-white border border-slate-200 focus:border-primary px-5 py-4 text-xs font-bold uppercase tracking-tight text-slate-900 transition-all rounded-xl focus:ring-0" placeholder="e.g. CONSULT">
                                </div>
                                <div>
                                    <label class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Action Vector (URL)</label>
                                    <input v-model="element.content.button_link" type="text" class="w-full bg-white border border-slate-200 focus:border-primary px-5 py-4 text-xs font-bold tracking-tight text-slate-900 transition-all rounded-xl focus:ring-0" placeholder="/contact">
                                </div>
                            </div>
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Visual Asset Integration</label>
                                <div class="flex items-center gap-6 p-6 bg-slate-50 border border-slate-100 rounded-2xl">
                                    <div class="w-20 h-20 bg-white border border-slate-200 flex items-center justify-center overflow-hidden rounded-xl shadow-sm group/thumb relative">
                                        <img v-if="element.content.image" 
                                             :src="typeof element.content.image === 'string' ? resolveImage(element.content.image) : previewFile(element.content.image)" 
                                             class="w-full h-full object-cover">
                                        <span v-else class="text-slate-300 text-xl font-black">?</span>
                                    </div>
                                    <div class="flex-1 space-y-3">
                                        <label class="block px-4 py-3 bg-white border border-slate-200 hover:border-primary text-slate-500 hover:text-primary text-[9px] font-black uppercase tracking-[0.3em] transition-all cursor-pointer text-center rounded-lg shadow-sm">
                                            Select Asset
                                            <input type="file" class="hidden" accept="image/*" @change="(e) => handleSectionImage(e, index)">
                                        </label>
                                        <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest text-center italic">JPG, PNG, WebP · Max 2MB</p>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-500 mb-2 block">Direct Asset URL Vector</label>
                                <input v-model="element.content.image_url" type="text" 
                                    class="w-full bg-white border border-slate-200 focus:border-primary px-5 py-4 text-[10px] font-bold tracking-tight text-slate-900 transition-all rounded-xl focus:ring-0" 
                                    placeholder="https://images.unsplash.com/...">
                                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest italic">Injected URL overrides local binary assets if provided.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section Footer/Metadata -->
                    <div class="bg-slate-50 px-10 py-4 border-t border-slate-100 flex justify-between items-center">
                        <div class="flex items-center gap-6">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">ID: {{ element.id || 'LEGACY_NODE' }}</span>
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">TYPE: {{ element.type.toUpperCase() }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em]">Operational Node Synchronized</span>
                        </div>
                    </div>
                </div>
            </template>
        </draggable>

        <!-- Add Section Interface -->
        <div class="border-2 border-dashed border-slate-200 bg-slate-50/50 p-16 text-center shadow-inner relative overflow-hidden group rounded-2xl">
            <div class="absolute inset-0 bg-primary/[0.02] opacity-0 group-hover:opacity-100 transition-opacity"></div>
            
            <p class="text-[10px] font-black uppercase tracking-[0.5em] text-slate-400 mb-12 relative z-10">Inject Structural Component to Matrix</p>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 relative z-10">
                <button v-for="type in sectionTypes" :key="type.type" 
                    @click="addSection(type.type)"
                    class="group/btn flex flex-col items-center gap-4 p-6 bg-white border border-slate-200 hover:border-primary hover:bg-primary hover:shadow-xl hover:shadow-primary/20 transition-all duration-300 rounded-xl">
                    <svg class="w-6 h-6 text-slate-300 group-hover/btn:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                        <path :d="type.icon" />
                    </svg>
                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-500 group-hover/btn:text-white transition-colors">{{ type.label }}</span>
                </button>
            </div>
        </div>
        
        <div v-if="sections.length === 0" class="py-20 text-center">
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 italic">No structural components assembled for this node.</p>
        </div>
    </div>
</template>

<style scoped>
input:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 20px rgba(22, 86, 209, 0.1);
}
</style>
