<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    services: Array
});

const search = ref('');

const filteredServices = computed(() => {
    if (!search.value) return props.services;
    const lowerSearch = search.value.toLowerCase();
    return props.services.filter(service => 
        service.title.toLowerCase().includes(lowerSearch) || 
        (service.short_description && service.short_description.toLowerCase().includes(lowerSearch))
    );
});

const deleteService = (id) => {
    if (confirm('Decommission this service permanently?')) {
        router.delete(route('admin.services.destroy', id), {
            preserveScroll: true,
            preserveState: true,
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Service Infrastructure" />

        <ModuleHeader 
            title="Core Capabilities" 
            actionText="Propose Service" 
            :actionRoute="route('admin.services.create')"
            v-model:search="search"
        >
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mt-2">Operational verticals & deployed expertise.</p>
            </template>
        </ModuleHeader>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="service in filteredServices" :key="service.id" 
                class="bg-black border border-zinc-900 overflow-hidden group hover:border-orange-600 transition-all duration-500 rounded-lg">
                
                <div class="aspect-video relative overflow-hidden bg-zinc-900 border-b border-zinc-900">
                    <img v-if="service.featured_image" :src="service.featured_image" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-105 opacity-80 group-hover:opacity-100">
                    <div v-else class="w-full h-full flex flex-col items-center justify-center bg-zinc-950">
                        <span class="text-zinc-800 text-4xl font-black italic">!</span>
                        <span class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-700 mt-2">No Visual Asset</span>
                    </div>
                    
                    <div class="absolute top-4 right-4 flex gap-2">
                        <span v-if="service.is_featured" class="bg-orange-600 text-black text-[8px] font-black uppercase tracking-widest px-2 py-1 shadow-lg rounded-lg">Featured</span>
                        <span :class="[
                            service.status === 'active' ? 'bg-green-500' : 'bg-zinc-700',
                            'w-2 h-2 rounded-full mt-1 border border-black shadow-lg'
                        ]"></span>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="mb-6">
                        <h3 class="text-xl font-black text-white uppercase tracking-tighter italic group-hover:text-orange-600 transition-colors truncate">{{ service.title }}</h3>
                        <div class="w-8 h-1 bg-zinc-800 mt-4 group-hover:bg-orange-600 group-hover:w-full transition-all duration-500"></div>
                    </div>
                    
                    <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-widest leading-relaxed mb-8 h-12 overflow-hidden line-clamp-2">
                        {{ service.short_description || 'Operational parameters undisclosed.' }}
                    </p>
                    
                    <div class="flex justify-between items-center pt-6 border-t border-zinc-900">
                        <Link :href="route('admin.services.edit', service.id)" class="text-[9px] font-black uppercase tracking-[0.3em] text-white hover:text-orange-600 transition-colors border-b border-transparent hover:border-orange-600 pb-1">Modify Logic</Link>
                        <button @click="deleteService(service.id)" class="text-[9px] font-black uppercase tracking-[0.3em] text-zinc-700 hover:text-red-600 transition-colors border-b border-transparent hover:border-red-600 pb-1">Decommission</button>
                    </div>
                </div>
            </div>
            
            <!-- Empty State -->
            <div v-if="filteredServices.length === 0" class="col-span-full py-24 flex flex-col items-center justify-center text-center border-2 border-dashed border-zinc-900 bg-zinc-950/30">
                <div class="w-16 h-16 bg-zinc-900 flex items-center justify-center mb-6">
                    <span class="text-zinc-600 text-4xl font-black">?</span>
                </div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">No operational services defined.</p>
                <Link :href="route('admin.services.create')" class="mt-8 text-[10px] font-black uppercase tracking-[0.3em] text-orange-600 hover:text-white transition-colors">Initialize Service Protocol</Link>
            </div>
        </div>
    </AdminLayout>
</template>
