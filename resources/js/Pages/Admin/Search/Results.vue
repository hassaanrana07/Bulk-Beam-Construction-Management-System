<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    results: Array,
    query: String
});
</script>

<template>
    <AdminLayout>
        <Head :title="`Search Results: ${query}`" />

        <div class="mb-12">
            <h1 class="text-4xl font-black uppercase tracking-tighter text-white italic mb-2">Search Resonance</h1>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600">Global scan results for query: <span class="text-orange-600">{{ query }}</span></p>
        </div>

        <div v-if="results.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <Link v-for="(result, index) in results" :key="index" :href="result.route" 
                class="bg-black border border-zinc-900 p-8 hover:border-orange-600 transition-all group relative overflow-hidden rounded-lg">
                <div class="absolute top-0 right-0 px-4 py-2 bg-zinc-900 text-[8px] font-black uppercase tracking-widest text-zinc-500 group-hover:bg-orange-600 group-hover:text-black transition-colors rounded-bl-lg">
                    {{ result.type }}
                </div>
                
                <h3 class="text-xl font-black text-white uppercase tracking-tighter mb-4 group-hover:text-orange-600 transition-colors">{{ result.name }}</h3>
                
                <div v-if="result.status" class="flex items-center gap-2 mb-4">
                    <span class="text-[8px] font-black uppercase tracking-widest text-zinc-600">Status:</span>
                    <span class="text-[9px] font-black uppercase text-white">{{ result.status }}</span>
                </div>

                <div class="flex items-center gap-2 text-[9px] font-black uppercase tracking-[0.3em] text-zinc-500 group-hover:text-white transition-colors">
                    Access Node <span class="group-hover:translate-x-1 transition-transform inline-block">→</span>
                </div>
                
                <div class="absolute bottom-0 left-0 h-1 bg-orange-600 w-0 group-hover:w-full transition-all duration-500"></div>
            </Link>
        </div>

        <div v-else class="bg-black border border-zinc-900 py-32 flex flex-col items-center justify-center text-center rounded-lg">
            <div class="w-20 h-20 bg-zinc-900 flex items-center justify-center mb-8 border border-zinc-800">
                <span class="text-zinc-700 text-4xl font-black italic">!</span>
            </div>
            <h2 class="text-2xl font-black text-white uppercase tracking-tighter mb-2">Null Sector Detected</h2>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600">No organizational assets match your current search parameters.</p>
            <Link :href="route('admin.dashboard')" class="mt-8 text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 hover:text-white transition-colors">Return to Hub →</Link>
        </div>
    </AdminLayout>
</template>
