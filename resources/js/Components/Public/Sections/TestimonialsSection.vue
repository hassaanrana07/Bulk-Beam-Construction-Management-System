<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

const props = defineProps({
    content: Object,
    globalData: Object
});

const page = usePage();
const testimonials = computed(() => props.globalData?.testimonials || page.props.testimonials || []);

const modules = [Navigation, Pagination, Autoplay];
</script>

<template>
    <section class="py-32 bg-[#0b0f19] overflow-hidden border-y border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 space-y-4">
                <h2 class="text-3xl font-black text-white uppercase tracking-tight">Client Testimonials</h2>
                <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.3em]">Trusted by developers, architects, and site managers</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div v-for="t in testimonials.slice(0, 3)" :key="t.id" 
                    class="p-10 bg-slate-900 shadow-2xl border border-white/5 rounded-[2rem] flex flex-col hover:border-amber-500/30 transition-all group">
                    <!-- Stars -->
                    <div class="flex gap-1 mb-8">
                        <svg v-for="i in 5" :key="i" class="w-4 h-4 text-amber-500 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    
                    <p class="text-slate-400 text-sm font-medium leading-relaxed mb-10 italic">
                        "{{ t.testimonial }}"
                    </p>
                    
                    <div class="flex items-center gap-4 mt-auto">
                        <div class="w-10 h-10 rounded-full bg-slate-800 border-2 border-white/10 overflow-hidden">
                             <img :src="`https://ui-avatars.com/api/?name=${t.client_name}&background=fbbf24&color=0b0f19`" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black uppercase tracking-widest text-white">{{ t.client_name }}</span>
                            <span class="text-[8px] font-black uppercase tracking-widest text-slate-500">{{ t.client_company }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
:deep(.swiper-pagination-bullet) {
    width: 40px;
    height: 2px;
    background: #3f3f46;
    border-radius: 0;
    opacity: 1;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

:deep(.swiper-pagination-bullet-active) {
    background: #1656D1;
    width: 100px;
    box-shadow: 0 0 15px rgba(22, 86, 209, 0.4);
}

:deep(.swiper-slide) {
    height: auto !important;
}

@media (max-width: 768px) {
    :deep(.swiper-pagination-bullet) {
        width: 20px;
    }
    :deep(.swiper-pagination-bullet-active) {
        width: 40px;
    }
}
</style>
