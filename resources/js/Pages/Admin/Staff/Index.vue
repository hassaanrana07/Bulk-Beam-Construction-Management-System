<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    staff: Array
});

const search = ref('');

const filteredStaff = computed(() => {
    if (!search.value) return props.staff;
    const lowerSearch = search.value.toLowerCase();
    return props.staff.filter(person => 
        person.name.toLowerCase().includes(lowerSearch) || 
        person.role.toLowerCase().includes(lowerSearch)
    );
});

const deleteStaff = (id) => {
    if (confirm('Decommission this personnel record?')) {
        router.delete(route('admin.staff.destroy', id), {
            preserveScroll: true,
            preserveState: true,
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Staff Command" />

        <ModuleHeader 
            title="Command Staff Matrix" 
            actionText="Deploy Personnel" 
            :actionRoute="route('admin.staff.create')"
            v-model:search="search"
        >
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mt-2">Managing structural leadership and field command officers.</p>
            </template>
        </ModuleHeader>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="person in filteredStaff" :key="person.id" 
                class="bg-black border border-zinc-900 overflow-hidden group hover:border-orange-600 transition-all duration-500 rounded-lg">
                <div class="aspect-[4/3] relative overflow-hidden bg-zinc-900 border-b border-zinc-900">
                    <img :src="person.photo || `https://ui-avatars.com/api/?name=${person.name}&background=ff6b00&color=000&size=512`" 
                         class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-105">
                    
                    <div class="absolute top-4 right-4 flex gap-2">
                        <span v-if="person.is_leadership" class="bg-orange-600 text-black text-[8px] font-black uppercase tracking-widest px-2 py-1 italic rounded-lg">Leadership</span>
                        <span :class="[person.is_active ? 'bg-green-500' : 'bg-red-500', 'w-2 h-2 rounded-full mt-1 border border-black shadow-lg']"></span>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="mb-6">
                        <h3 class="text-xl font-black text-white uppercase tracking-tighter italic group-hover:text-orange-600 transition-colors truncate">{{ person.name }}</h3>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 mt-1 truncate">{{ person.role }}</p>
                    </div>
                    
                    <p class="text-[11px] text-zinc-500 font-bold uppercase tracking-tight leading-relaxed mb-8 line-clamp-2">
                        {{ person.bio || 'Clearance granted for executive operations and structural management.' }}
                    </p>
                    
                    <div class="flex border-t border-zinc-900 pt-8 gap-6">
                        <Link :href="route('admin.staff.edit', person.id)" class="text-[10px] font-black uppercase tracking-[0.3em] text-white hover:text-orange-600 transition-colors">Modify Dossier</Link>
                        <button @click="deleteStaff(person.id)" class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-700 hover:text-red-600 transition-colors">Purge Data</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredStaff.length === 0" class="py-32 border-2 border-dashed border-zinc-900 flex flex-col items-center justify-center text-center">
            <div class="w-16 h-16 bg-zinc-900 flex items-center justify-center mb-6">
                <span class="text-zinc-600 text-4xl">!</span>
            </div>
            <p class="text-xs font-black uppercase tracking-[0.3em] text-zinc-500">No personnel data recovered in current sector.</p>
        </div>
    </AdminLayout>
</template>
