<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    title: String,
    actionText: String,
    actionRoute: String,
    search: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['update:search', 'search']);
</script>

<template>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12 border-b border-slate-200 pb-8 transition-colors">
        <div>
            <h1 class="text-3xl font-black uppercase tracking-tight text-slate-900 mb-2">{{ title }}</h1>
            <slot name="subtitle"></slot>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
            <div v-if="$attrs.onSearch || $attrs['onUpdate:search']" class="relative group">
                <input 
                    :value="search"
                    @input="$emit('update:search', $event.target.value)"
                    type="text" 
                    placeholder="SEARCH MATRIX..." 
                    class="w-full sm:w-64 bg-white border border-slate-200 text-slate-900 text-xs font-bold uppercase tracking-widest px-4 py-3 pl-10 focus:border-primary focus:ring-0 transition-all placeholder:text-slate-400 rounded-lg group-hover:border-slate-300"
                >
                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <slot name="actions">
                <div v-if="actionRoute">
                    <Link :href="actionRoute" class="px-8 py-3 bg-primary text-white text-[10px] font-black uppercase tracking-[0.2em] hover:bg-slate-900 transition-all shadow-lg shadow-primary/20 active:scale-95 flex items-center justify-center gap-2 border border-transparent h-full whitespace-nowrap rounded-lg">
                        <span class="text-lg">+</span> {{ actionText }}
                    </Link>
                </div>
            </slot>
        </div>
    </div>
</template>
