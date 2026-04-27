<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    content: Object,
    globalData: {
        type: Object,
        default: () => ({})
    }
});

const page = usePage();
const leaders = computed(() => props.globalData.team || page.props.team || []);
</script>

<template>
    <section class="py-32 bg-white dark:bg-navy-950 overflow-hidden relative">
        <!-- Vertical Accent -->
        <div class="absolute left-1/4 top-0 w-px h-full bg-gray-100 dark:bg-zinc-900 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div v-reveal class="reveal reveal-up flex flex-col md:flex-row justify-between items-end mb-24 gap-8">
                <div class="max-w-2xl">
                    <span class="text-xs font-black text-primary uppercase tracking-[0.3em] mb-4 block">{{ content.subtitle || 'Strategic Command' }}</span>
                    <h2 class="text-5xl md:text-7xl font-black uppercase tracking-tighter leading-none m-0 italic">
                        {{ content.heading || 'Expert' }} <span class="text-gray-300 dark:text-gray-800">{{ content.subheading || 'Leadership.' }}</span>
                    </h2>
                </div>
                <div class="text-[10px] font-black uppercase tracking-[0.5em] text-zinc-400 [writing-mode:vertical-lr] hidden md:block">
                    {{ content.vertical_text || 'INDUSTRIAL COMMAND STAFF' }}
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-12 gap-y-24">
                <div v-for="(leader, idx) in (leaders.length > 0 ? leaders : [])" :key="leader.id" 
                    v-reveal 
                    :class="['reveal reveal-up group', `delay-${(idx + 1) * 100}`]">
                    <div class="aspect-[4/5] overflow-hidden bg-zinc-100 dark:bg-zinc-900 grayscale hover:grayscale-0 transition-all duration-1000 mb-8 shadow-2xl relative rounded-lg">
                        <img :src="leader.photo || `https://ui-avatars.com/api/?name=${leader.name}&background=ff6b00&color=000&size=512`" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" :alt="leader.name">
                        <div class="absolute inset-x-0 bottom-0 h-0 bg-primary group-hover:h-2 transition-all duration-500"></div>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black uppercase tracking-tighter text-black dark:text-white leading-none italic">{{ leader.name }}</h3>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary mt-3">{{ leader.role }}</p>
                    </div>
                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-zinc-900 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-tight leading-relaxed line-clamp-2">
                            {{ leader.bio || 'Executive-level structural management and operational oversight.' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Fallback if no leaders -->
            <div v-if="leaders.length === 0" class="text-center py-20 border-2 border-dashed border-gray-100 dark:border-zinc-900">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">Command personnel data pending synchronization.</p>
            </div>
        </div>
    </section>
</template>
