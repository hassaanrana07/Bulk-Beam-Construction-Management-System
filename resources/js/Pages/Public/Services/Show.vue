<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    service: Object,
    related_services: Array
});
</script>

<template>
    <PublicLayout :key="$page.url">
        <Head :title="service.title" />

        <div class="relative pt-32 pb-20 bg-black text-white overflow-hidden min-h-[60vh] flex flex-col justify-end">
            <!-- Background Image -->
            <div v-if="service.featured_image" class="absolute inset-0 z-0">
                <img :src="service.featured_image" class="w-full h-full object-cover opacity-50 transition-all duration-700 hover:scale-105" :alt="service.title">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                <Link :href="route('services')" class="text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 mb-8 inline-block hover:opacity-70 transition-opacity">
                    ← Back to Operations Matrix
                </Link>
                <div class="space-y-4">
                    <span v-if="service.structural_type" class="text-xs font-black uppercase tracking-[0.4em] text-orange-600 italic block">{{ service.structural_type }}</span>
                    <h1 class="text-6xl md:text-8xl font-black uppercase tracking-tighter leading-none mb-8">
                        {{ service.title }}<span class="text-orange-600">.</span>
                    </h1>
                </div>
                <p class="text-xl text-gray-300 max-w-3xl leading-relaxed font-medium">
                    {{ service.short_description }}
                </p>
            </div>
        </div>

        <section class="py-32 bg-white dark:bg-black">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Main Architectural Brief -->
                <article class="prose prose-xl dark:prose-invert max-w-none text-center mb-32">
                    <div class="text-zinc-600 dark:text-zinc-400 leading-relaxed space-y-12" v-html="service.description"></div>
                </article>

                <!-- Technical Specification Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-start">
                    <!-- Left Column: Capability & Scope -->
                    <div class="space-y-24">
                        <!-- Applications Used (Capability Tools) -->
                        <div v-if="service.capability_tools && service.capability_tools.length" class="space-y-8">
                            <h3 class="text-xs font-black uppercase tracking-[0.5em] text-zinc-400 border-l-4 border-orange-600 pl-6">Applications Used</h3>
                            <div class="flex flex-wrap gap-3">
                                <span v-for="tool in service.capability_tools" :key="tool" class="px-5 py-2 bg-zinc-100 dark:bg-zinc-900 text-[10px] font-black uppercase tracking-widest rounded-full border border-zinc-200 dark:border-zinc-800">
                                    {{ tool }}
                                </span>
                            </div>
                        </div>

                        <!-- Service Scope (Capability Scope) -->
                        <div v-if="service.capability_scope_description || (service.capability_features && service.capability_features.length)" class="space-y-8">
                            <h3 class="text-xs font-black uppercase tracking-[0.5em] text-zinc-400 border-l-4 border-orange-600 pl-6">Service Scope</h3>
                            <div class="space-y-6">
                                <p v-if="service.capability_scope_description" class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed italic">
                                    {{ service.capability_scope_description }}
                                </p>
                                <ul v-if="service.capability_features && service.capability_features.length" class="grid grid-cols-1 gap-4">
                                    <li v-for="(item, i) in service.capability_features" :key="i" class="flex items-start gap-4">
                                        <span class="w-2 h-2 bg-orange-600 rounded-full mt-1.5 flex-shrink-0"></span>
                                        <span class="text-[11px] font-black uppercase tracking-widest text-zinc-800 dark:text-zinc-200 leading-tight">{{ item }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Deliverables -->
                        <div v-if="service.capability_deliverables && service.capability_deliverables.length" class="space-y-8">
                            <h3 class="text-xs font-black uppercase tracking-[0.5em] text-zinc-400 border-l-4 border-orange-600 pl-6">Deliverables Matrix</h3>
                            <div class="grid grid-cols-1 gap-3">
                                <div v-for="(item, i) in service.capability_deliverables" :key="i" class="p-4 bg-zinc-50 dark:bg-zinc-950 border border-zinc-100 dark:border-zinc-900 text-[10px] font-bold uppercase tracking-widest">
                                    {{ item }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Operational & Structural -->
                    <div class="space-y-24">
                        <!-- Operational Modules -->
                        <div v-if="service.operations_description" class="space-y-8 p-10 bg-black text-white rounded-2xl shadow-2xl relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-orange-600/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
                            <h3 class="text-xl font-black uppercase tracking-tighter text-orange-600 relative z-10">Operational Module</h3>
                            <p class="text-sm text-zinc-400 leading-relaxed relative z-10">{{ service.operations_description }}</p>
                            
                            <div class="grid grid-cols-2 gap-6 pt-6 border-t border-white/10 relative z-10">
                                <div v-if="service.operations_timeline">
                                    <span class="text-[9px] font-black uppercase text-zinc-600 block mb-1">Timeline</span>
                                    <span class="text-[10px] font-black uppercase text-white tracking-widest">{{ service.operations_timeline }}</span>
                                </div>
                                <div v-if="service.operations_team">
                                    <span class="text-[9px] font-black uppercase text-zinc-600 block mb-1">Deployment Team</span>
                                    <span class="text-[10px] font-black uppercase text-white tracking-widest">{{ service.operations_team }}</span>
                                </div>
                            </div>

                            <ul v-if="service.operations_bullets && service.operations_bullets.length" class="space-y-3 pt-6 relative z-10">
                                <li v-for="(bullet, i) in service.operations_bullets" :key="i" class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-zinc-400">
                                    <span class="w-1.5 h-1.5 bg-orange-600 rounded-full"></span>
                                    {{ bullet }}
                                </li>
                            </ul>
                        </div>

                        <!-- Structure Analysis -->
                        <div v-if="service.structural_type" class="space-y-8">
                            <h3 class="text-xs font-black uppercase tracking-[0.5em] text-zinc-400 border-l-4 border-orange-600 pl-6">Structural Analysis</h3>
                            <div class="p-10 bg-zinc-50 dark:bg-zinc-950 border border-zinc-100 dark:border-zinc-900 rounded-2xl space-y-8">
                                <div>
                                    <span class="text-[9px] font-black uppercase text-zinc-400 block mb-2">Classification</span>
                                    <p class="text-lg font-black uppercase tracking-widest text-orange-600">{{ service.structural_type }}</p>
                                </div>
                                <div v-if="service.technical_breakdown">
                                    <span class="text-[9px] font-black uppercase text-zinc-400 block mb-2">Technical Parameters</span>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed font-medium">{{ service.technical_breakdown }}</p>
                                </div>
                                <div v-if="service.materials_used && service.materials_used.length">
                                    <span class="text-[9px] font-black uppercase text-zinc-400 block mb-4">Material Inventory</span>
                                    <div class="flex flex-wrap gap-2">
                                        <span v-for="m in service.materials_used" :key="m" class="px-3 py-1 bg-white dark:bg-black border border-zinc-200 dark:border-zinc-800 text-[8px] font-black uppercase tracking-widest rounded-lg">
                                            {{ m }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vacations Module (Minimal) -->
                        <div v-if="service.vacations_description" class="space-y-6">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-400">Policy & Compliance</h3>
                            <div class="p-8 border border-zinc-100 dark:border-zinc-900 rounded-xl space-y-4">
                                <p class="text-[11px] text-zinc-500 font-medium leading-relaxed">{{ service.vacations_description }}</p>
                                <div v-if="service.vacations_timeline" class="flex items-center gap-3 text-[9px] font-black uppercase tracking-widest text-zinc-400">
                                    <span class="w-2 h-px bg-zinc-300"></span>
                                    {{ service.vacations_timeline }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deployment Phases Section (Full Width Below) -->
                <div v-if="service.phases_details && service.phases_details.length" class="mt-40 pt-40 border-t border-zinc-100 dark:border-zinc-900 space-y-20">
                    <div class="text-center space-y-4">
                        <h3 class="text-5xl font-black uppercase tracking-tighter">Deployment Strategy<span class="text-orange-600">.</span></h3>
                        <p class="text-[10px] font-black uppercase tracking-[0.5em] text-zinc-400">Phased Execution Matrix</p>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-12 max-w-4xl mx-auto">
                        <div v-for="(phase, index) in service.phases_details" :key="index" 
                            class="flex items-center gap-12 p-10 bg-white dark:bg-black border border-zinc-100 dark:border-zinc-900 rounded-3xl group hover:border-orange-600 transition-all shadow-xl hover:shadow-2xl">
                            <div class="w-24 h-24 bg-zinc-50 dark:bg-zinc-950 border border-zinc-100 dark:border-zinc-900 flex items-center justify-center rounded-2xl flex-shrink-0 group-hover:bg-orange-600 group-hover:border-orange-600 transition-all transform group-hover:rotate-6">
                                <span class="text-3xl font-black italic text-zinc-300 dark:text-zinc-700 group-hover:text-white transition-all">0{{ index + 1 }}</span>
                            </div>
                            <div class="flex-1 space-y-2">
                                <h4 class="text-lg font-black uppercase tracking-widest text-zinc-800 dark:text-zinc-200">{{ phase.title }}</h4>
                                <p class="text-sm text-zinc-500 dark:text-gray-400 leading-relaxed">{{ phase.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Operations -->
                <div v-if="related_services && related_services.length" class="mt-40 pt-20 border-t border-zinc-100 dark:border-zinc-900">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.5em] text-zinc-400 mb-10 text-center">Interlinked Capabilities</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Link v-for="related in related_services" :key="related.id" :href="route('services.show', related.slug)" 
                            class="p-8 bg-zinc-50 dark:bg-zinc-950 border border-zinc-100 dark:border-zinc-900 hover:border-orange-600 transition-all flex justify-between items-center group rounded-2xl">
                            <span class="text-[11px] font-black uppercase tracking-[0.2em] text-zinc-500 group-hover:text-white transition-colors">{{ related.title }}</span>
                            <span class="text-orange-600 translate-x-4 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all">→</span>
                        </Link>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="mt-40 text-center space-y-12 bg-black p-20 rounded-[3rem] shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-64 h-64 bg-orange-600/10 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
                    <div class="space-y-4 relative z-10">
                        <h2 class="text-4xl md:text-6xl font-black uppercase tracking-tighter text-white">Need Custom Specifications?</h2>
                        <p class="text-zinc-500 font-bold uppercase tracking-widest text-xs">Architectural consultation available for complex deployments</p>
                    </div>
                    <Link :href="route('contact')" class="inline-block px-12 py-6 bg-orange-600 text-white text-[10px] font-black uppercase tracking-[0.5em] hover:bg-white hover:text-black transition-all rounded-full relative z-10">
                        Contact Us
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
