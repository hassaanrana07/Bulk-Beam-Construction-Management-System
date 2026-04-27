<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    project: Object
});

const resolveImage = (path) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    if (path.startsWith('/storage/')) return path;
    if (path.startsWith('storage/')) return '/' + path;
    return '/storage/' + path;
};
</script>

<template>
    <PublicLayout :key="$page.url">
        <Head :title="project.title" />

        <div class="pt-32 pb-20 bg-black text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <Link :href="route('portfolio')" class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 mb-8 inline-block hover:opacity-70 transition-opacity">
                    ← Back to Archive Library
                </Link>
                <div class="flex flex-col lg:flex-row justify-between items-end gap-12">
                    <div class="max-w-4xl">
                        <h1 class="text-6xl md:text-8xl font-black uppercase tracking-tighter leading-none mb-8">
                            {{ project.title }}<span class="text-orange-600">.</span>
                        </h1>
                    </div>
                    <div class="flex gap-12 border-l border-white/10 pl-12 pb-4 hidden lg:flex">
                        <div class="space-y-1">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Segment</p>
                            <p class="text-sm font-black uppercase">{{ project.project_type }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Image -->
        <section class="h-[70vh] w-full bg-gray-100 overflow-hidden">
            <img :src="resolveImage(project.featured_image) || 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop'" class="w-full h-full object-cover grayscale">
        </section>

        <section class="py-32 bg-white dark:bg-black">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-24">
                <div class="lg:col-span-2 space-y-12">
                    <!-- Structural Analysis Protocol Panel -->
                    <div v-if="project.base_structure || project.structural_features?.length" class="mb-20 space-y-12">
                        <div class="flex items-center gap-6">
                            <h2 class="text-4xl font-black uppercase tracking-tighter">Structural Protocol<span class="text-orange-600">.</span></h2>
                            <div class="h-px bg-zinc-100 dark:bg-zinc-900 flex-1 mt-2"></div>
                        </div>

                        <!-- Analysis Grid Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div v-if="project.base_structure" class="p-8 bg-zinc-50 dark:bg-zinc-950 border border-zinc-100 dark:border-zinc-900 rounded-2xl group hover:border-orange-600/50 transition-all">
                                <span class="text-[9px] font-black uppercase text-zinc-400 block mb-3 tracking-[0.2em]">Base Architecture</span>
                                <span class="text-sm font-black uppercase tracking-widest text-zinc-200 group-hover:text-orange-600 transition-colors">{{ project.base_structure }}</span>
                            </div>
                            <div v-if="project.foundation_type" class="p-8 bg-zinc-50 dark:bg-zinc-950 border border-zinc-100 dark:border-zinc-900 rounded-2xl group hover:border-orange-600/50 transition-all">
                                <span class="text-[9px] font-black uppercase text-zinc-400 block mb-3 tracking-[0.2em]">System Foundation</span>
                                <span class="text-sm font-black uppercase tracking-widest text-zinc-200 group-hover:text-orange-600 transition-colors">{{ project.foundation_type }}</span>
                            </div>
                            <div v-if="project.total_floors" class="p-8 bg-zinc-50 dark:bg-zinc-950 border border-zinc-100 dark:border-zinc-900 rounded-2xl group hover:border-orange-600/50 transition-all">
                                <span class="text-[9px] font-black uppercase text-zinc-400 block mb-3 tracking-[0.2em]">Vertical Scale</span>
                                <span class="text-sm font-black uppercase tracking-widest text-zinc-200 group-hover:text-orange-600 transition-colors">{{ project.total_floors }} Levels</span>
                            </div>
                        </div>

                        <!-- List Matrix -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                            <!-- Structural Features -->
                            <div v-if="project.structural_features?.length" class="space-y-6">
                                <h3 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500 border-l-2 border-orange-600 pl-4">Structural Features</h3>
                                <ul class="space-y-4">
                                    <li v-for="(feat, i) in project.structural_features" :key="i" class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-widest text-zinc-400">
                                        <span class="w-1.5 h-1.5 bg-zinc-800 rounded-full"></span>
                                        {{ feat }}
                                    </li>
                                </ul>
                            </div>

                            <!-- Capabilities -->
                            <div v-if="project.capabilities?.length" class="space-y-6">
                                <h3 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500 border-l-2 border-orange-600 pl-4">Operational Capabilities</h3>
                                <ul class="space-y-4">
                                    <li v-for="(cap, i) in project.capabilities" :key="i" class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-widest text-zinc-400">
                                        <span class="w-1.5 h-1.5 bg-zinc-800 rounded-full"></span>
                                        {{ cap }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Technical Specification Footer -->
                        <div class="p-10 bg-zinc-50 dark:bg-zinc-950 border border-zinc-100 dark:border-zinc-900 rounded-[2rem] grid grid-cols-1 md:grid-cols-2 gap-12">
                            <div v-if="project.technology_used || project.tools_used?.length">
                                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 mb-6">Technical Tools & Tech</h4>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span v-for="tool in project.tools_used" :key="tool" class="px-3 py-1 bg-white dark:bg-black border border-zinc-200 dark:border-zinc-800 text-[8px] font-black uppercase tracking-widest rounded-full">
                                        {{ tool }}
                                    </span>
                                </div>
                                <p v-if="project.technology_used" class="text-[11px] font-black uppercase tracking-widest text-zinc-500">{{ project.technology_used }}</p>
                            </div>
                            <div v-if="project.construction_technology || project.framework_type">
                                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 mb-6">Framework & Execution</h4>
                                <p v-if="project.framework_type" class="text-sm font-black uppercase text-zinc-200 mb-2">{{ project.framework_type }}</p>
                                <p v-if="project.construction_technology" class="text-[11px] font-black uppercase tracking-widest text-zinc-500">{{ project.construction_technology }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-2xl font-black uppercase tracking-tighter mb-8">Architectural Narrative</h2>
                        <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-400 text-lg leading-relaxed space-y-8" v-html="project.description"></div>
                    </div>
                    
                    <div v-if="project.gallery" class="grid grid-cols-2 gap-8 pt-12">
                        <div v-for="(img, idx) in project.gallery" :key="idx" class="aspect-square bg-gray-100 overflow-hidden group rounded-lg">
                            <img :src="resolveImage(img)" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        </div>
                    </div>
                </div>
                
                <aside class="space-y-12">
                    <div class="p-10 bg-gray-50 dark:bg-gray-950 border-l-4 border-orange-600 space-y-10 rounded-lg">
                        <h3 class="text-xl font-black uppercase tracking-tight">Technical Data</h3>
                        <div class="space-y-6">
                            <div class="border-b border-gray-100 dark:border-gray-900 pb-4" v-if="project.case_study_category">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Case Category</p>
                                <p class="text-sm font-black uppercase text-orange-600">{{ project.case_study_category }}</p>
                            </div>
                            <div class="border-b border-gray-100 dark:border-gray-900 pb-4" v-if="project.case_study_scope">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Project Scope</p>
                                <p class="text-sm font-black uppercase">{{ project.case_study_scope }}</p>
                            </div>
                            <div class="border-b border-gray-100 dark:border-gray-900 pb-4" v-if="project.case_study_sector">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Execution Sector</p>
                                <p class="text-sm font-black uppercase">{{ project.case_study_sector }}</p>
                            </div>
                            <div class="border-b border-gray-100 dark:border-gray-900 pb-4">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Execution Status</p>
                                <p class="text-sm font-black uppercase" :class="project.execution_status === 'Completed' ? 'text-green-500' : 'text-orange-600'">{{ project.execution_status }}</p>
                            </div>
                            <div class="border-b border-gray-100 dark:border-gray-900 pb-4" v-if="project.cs_duration_weeks">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Project Duration</p>
                                <p class="text-sm font-black uppercase">{{ project.cs_duration_weeks }} Weeks</p>
                            </div>
                            <div class="border-b border-gray-100 dark:border-gray-900 pb-4" v-if="project.cs_team">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Assigned Team</p>
                                <p class="text-sm font-black uppercase tracking-tighter">{{ project.cs_team }}</p>
                            </div>
                            <div class="border-b border-gray-100 dark:border-gray-900 pb-4" v-if="project.cs_total_value">
                                <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Assessment Value</p>
                                <p class="text-sm font-black uppercase">{{ project.cs_total_value }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Execution Phases -->
                    <div v-if="project.cs_phase_1" class="p-10 bg-black text-white space-y-8 rounded-lg">
                        <h3 class="text-xs font-black uppercase tracking-[0.3em] text-orange-600">Deployment Phases</h3>
                        <div class="space-y-6">
                            <div v-for="i in 5" :key="i">
                                <div v-if="project['cs_phase_' + i]" class="flex gap-4">
                                    <span class="text-[10px] font-black text-zinc-700">0{{ i }}</span>
                                    <p class="text-[11px] font-bold uppercase tracking-widest text-zinc-400 leading-relaxed">{{ project['cs_phase_' + i] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 p-10 space-y-8 rounded-lg">
                        <h3 class="text-xl font-black uppercase tracking-tight text-orange-600">Request Audit</h3>
                        <p class="text-xs text-gray-500 uppercase tracking-widest leading-relaxed">Interested in the technical feasibility of a similar structural deployment? Connect with our project leads.</p>
                        <Link :href="route('contact')" class="block w-full py-5 border-2 border-white/20 hover:border-orange-600 hover:text-orange-600 transition-all text-center text-[10px] font-black uppercase tracking-widest rounded-lg">
                            Commence Project Feasibility
                        </Link>
                    </div>
                </aside>
            </div>
        </section>
    </PublicLayout>
</template>
