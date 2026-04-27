<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SectionRenderer from '@/Components/SectionRenderer.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    faqs: Array,
    page: Object
});

const activeFaq = ref(null);
</script>

<template>
    <PublicLayout>
        <Head :title="page?.title || 'Intelligence Base & FAQs'" />

        <SectionRenderer v-if="page" :sections="page.sections" />

        <!-- Fallback if no sections -->
        <div v-else class="pt-48 pb-32 bg-navy-950 text-white relative overflow-hidden text-center">
            <h1 class="text-6xl md:text-9xl font-black uppercase tracking-tighter mb-10 italic">FAQs</h1>
        </div>

        <!-- FAQ Content -->
        <section class="py-32 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="space-y-6">
                    <div v-for="faq in faqs" :key="faq.id" 
                        class="group border-b border-slate-100 last:border-0 pb-6 transition-all duration-500">
                        <button 
                            @click="activeFaq = activeFaq === faq.id ? null : faq.id"
                            class="w-full flex justify-between items-center text-left py-8 focus:outline-none"
                        >
                            <div class="space-y-2">
                                <span class="text-[10px] font-black text-primary uppercase tracking-[0.3em] block italic">{{ faq.category || 'General protocol' }}</span>
                                <h3 class="text-xl md:text-2xl font-black uppercase tracking-tighter transition-colors group-hover:text-primary italic" :class="activeFaq === faq.id ? 'text-primary' : 'text-slate-900'">
                                    {{ faq.question }}
                                </h3>
                            </div>
                            <div class="relative w-12 h-12 flex items-center justify-center shrink-0 ml-4">
                                <span class="absolute w-6 h-[2.5px] bg-slate-200 transition-all duration-500" :class="{ 'rotate-90 opacity-0': activeFaq === faq.id }"></span>
                                <span class="w-6 h-[2.5px] bg-primary"></span>
                            </div>
                        </button>
                        
                        <transition name="fade-slide">
                            <div v-show="activeFaq === faq.id" class="pb-12">
                                <p class="text-lg text-slate-500 font-bold uppercase tracking-tight leading-relaxed max-w-3xl border-l-8 border-primary/20 pl-8 ml-2 italic">
                                    {{ faq.answer }}
                                </p>
                            </div>
                        </transition>
                    </div>
                </div>

                <!-- Contact CTA -->
                <div class="mt-32 p-16 bg-slate-50 border border-slate-100 text-center rounded-3xl relative overflow-hidden group">
                    <div class="absolute inset-0 bg-primary/[0.02] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <h4 class="text-2xl font-black uppercase tracking-tighter mb-4 italic text-slate-900 relative z-10">Still Have Queries?</h4>
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.4em] mb-12 relative z-10">Direct communication line is open for custom requirements.</p>
                    <a href="/contact" class="inline-block bg-primary text-white px-12 py-6 text-[10px] font-black uppercase tracking-[0.3em] hover:translate-y-[-4px] transition-all shadow-xl shadow-primary/20 relative z-10 rounded-xl">
                        Initiate Inquiry Protocol
                    </a>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<style scoped>
.fade-slide-enter-active, .fade-slide-leave-active {
    transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}
.fade-slide-enter-from, .fade-slide-leave-to {
    transform: translateY(-20px);
    opacity: 0;
}
</style>
