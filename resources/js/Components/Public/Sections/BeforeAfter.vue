<script setup>
defineProps({
    content: Object
});
</script>

<template>
    <section class="py-32 bg-white dark:bg-navy-950 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="space-y-12">
                    <div class="space-y-4">
                        <span class="text-xs font-black text-primary uppercase tracking-[0.3em] block">{{ content.subtitle || 'The Transformation' }}</span>
                        <h2 class="text-5xl md:text-7xl font-black uppercase tracking-tighter leading-none m-0">
                            {{ content.heading || 'Visible' }} <span class="text-gray-300 dark:text-gray-800">{{ content.subheading || 'Precision.' }}</span>
                        </h2>
                    </div>
                    <p class="text-gray-500 text-lg leading-relaxed max-w-xl font-medium">
                        {{ content.description || 'Our structural overhauls take neglected assets and convert them into high-performance industrial environments.' }}
                    </p>
                    <div class="flex gap-10">
                        <div class="p-6 bg-gray-50 dark:bg-navy-900 border-l-4 border-primary">
                            <p class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Duration</p>
                            <p class="text-sm font-black uppercase text-black dark:text-white">{{ content.duration || '14 Months' }}</p>
                        </div>
                        <div class="p-6 bg-gray-50 dark:bg-navy-900 border-l-4 border-black dark:border-white">
                            <p class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Impact</p>
                            <p class="text-sm font-black uppercase text-black dark:text-white">{{ content.impact || '+400% Capacity' }}</p>
                        </div>
                    </div>
                </div>

                <div class="relative group cursor-ew-resize">
                    <div class="aspect-[4/3] relative overflow-hidden">
                        <!-- After -->
                        <img :src="content.image || 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop'" class="w-full h-full object-cover">
                        
                        <!-- Before Overlay (Static for now, but design suggests slider) -->
                        <div class="absolute inset-0 w-1/2 overflow-hidden border-r-2 border-primary shadow-2xl">
                            <img :src="content.before_image || 'https://images.unsplash.com/photo-1541888946425-d81bb19480c5?q=80&w=2070&auto=format&fit=crop'" class="w-[200%] max-w-none h-full object-cover grayscale brightness-50">
                            <div class="absolute top-4 left-4 bg-black/60 text-white text-[9px] font-black uppercase tracking-widest px-2 py-1">Phase 1: Initial Vector</div>
                        </div>
                        <div class="absolute top-4 right-4 bg-primary text-white text-[9px] font-black uppercase tracking-widest px-2 py-1">Phase 4: Optimization</div>
                    </div>
                    <p class="text-center text-[10px] uppercase font-black tracking-widest text-gray-400 mt-6">Interactive structural comparison — hover to audit</p>
                </div>
            </div>
        </div>
    </section>
</template>
