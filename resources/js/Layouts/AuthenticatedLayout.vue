<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showingSidebar = ref(true);
const page = usePage();
const user = page.props.auth.user;

const navigation = computed(() => {
    const roles = user.roles || [];
    const nav = [
        { name: 'Dashboard', href: route('admin.dashboard'), icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', current: route().current('admin.dashboard') },
    ];

    if (roles.includes('Super Admin')) {
        nav.push(
            { name: 'Projects', href: route('admin.portfolios.index'), icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', current: route().current('admin.portfolios.*') },
            { name: 'Users', href: '#', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', current: false },
            { name: 'Leads', href: route('admin.leads.index'), icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', current: route().current('admin.leads.*') },
            { name: 'CMS', href: route('admin.pages.index'), icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', current: route().current('admin.pages.*') },
            { name: 'System Logs', href: '#', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', current: false },
            { name: 'Settings', href: route('admin.settings.index'), icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.714 1.714 0 001.573 1.232l.704-.153c1.77-.384 3.197 2.103 2.13 3.596l-.427.599a1.714 1.714 0 00.129 2.185l.504.593c1.176 1.385-.24 3.596-2.008 3.113l-.715-.196a1.714 1.714 0 00-1.936 1.059l-.317.844c-.75 2 2-3.238 2.768-2.71c.426 1.756-2.924 1.756-3.35 0a1.714 1.714 0 00-1.573-1.232l-.704.153c-1.77.384-3.197-2.103-2.13-3.596l.427-.599a1.714 1.714 0 00-.129-2.185l-.504-.593c-1.176-1.385.24-3.596 2.008-3.113l.715.196a1.714 1.714 0 001.936-1.059l.317-.844c.426-1.756 2.924-1.756 3.35 0z', current: route().current('admin.settings.index') },
        );
    }

    if (roles.includes('Manager')) {
        nav.push(
            { name: 'My Projects', href: route('admin.portfolios.index'), icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', current: route().current('admin.portfolios.*') },
            { name: 'Tasks', href: '#', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', current: false },
            { name: 'Team', href: '#', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857', current: false },
            { name: 'Documents', href: '#', icon: 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z', current: false },
            { name: 'Approvals', href: '#', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', current: false },
        );
    }

    if (roles.includes('Staff')) {
        nav.push(
            { name: 'My Tasks', href: '#', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', current: false },
            { name: 'Attendance', href: '#', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', current: false },
            { name: 'Requests', href: '#', icon: 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', current: false },
        );
    }

    if (roles.includes('Finance Manager') || roles.includes('Finance Support')) {
        nav.push(
            { name: 'Revenue', href: route('admin.finance.index'), icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', current: route().current('admin.finance.*') },
            { name: 'Expenses', href: '#', icon: 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', current: false },
            { name: 'Vendors', href: '#', icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', current: false },
        );
    }

    return nav;
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-slate-900 flex font-sans selection:bg-primary selection:text-white">
        <!-- Sidebar -->
        <aside 
            :class="['fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 transition-transform duration-300 lg:translate-x-0', showingSidebar ? 'translate-x-0' : '-translate-x-full shadow-2xl']"
        >
            <div class="flex flex-col h-full font-sans">
                <!-- Sidebar Header -->
                <div class="h-24 flex items-center px-8 border-b border-slate-100 bg-white">
                    <Link :href="route('dashboard')" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary flex items-center justify-center font-black text-white italic text-xl rounded-lg">B</div>
                        <span class="font-black uppercase tracking-tighter text-xl text-slate-900">Brick & Beam</span>
                    </Link>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 overflow-y-auto py-8 px-4 space-y-2">
                    <Link 
                        v-for="item in navigation" 
                        :key="item.name" 
                        :href="item.href"
                        :class="['flex items-center gap-4 px-4 py-3.5 text-[10px] font-black uppercase tracking-widest transition-all group rounded-xl', item.current ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-500 hover:text-primary hover:bg-primary/5']"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" :d="item.icon" />
                        </svg>
                        {{ item.name }}
                    </Link>
                </nav>

                <!-- Sidebar Footer -->
                <div class="p-4 border-t border-slate-100 bg-white">
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center font-black text-primary text-xl">
                            {{ user.name.charAt(0) }}
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="text-[10px] font-black uppercase tracking-widest truncate text-slate-900">{{ user.name }}</p>
                            <p class="text-[8px] text-slate-500 truncate uppercase tracking-widest">{{ user.email }}</p>
                        </div>
                        <Link :href="route('logout')" method="post" as="button" class="text-slate-400 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div :class="['flex-1 flex flex-col min-w-0 transition-all duration-300', showingSidebar ? 'lg:ml-72' : '']">
            <!-- Header -->
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-40 backdrop-blur-md bg-white/80">
                <div class="flex items-center gap-8">
                    <button @click="showingSidebar = !showingSidebar" class="text-slate-400 hover:text-primary transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                    </button>
                    <div v-if="$slots.header" class="font-black uppercase tracking-widest text-xs text-slate-900">
                        <slot name="header" />
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="hidden md:flex flex-col items-end">
                        <span class="text-[8px] font-black uppercase tracking-[0.3em] text-primary">System Clock</span>
                        <span class="text-[10px] font-black text-slate-900 italic">{{ new Date().toLocaleTimeString() }}</span>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <Link :href="route('home')" target="_blank" class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-900 hover:border-primary hover:text-primary transition-all text-[10px] font-black uppercase tracking-widest rounded-lg transition-transform hover:scale-105 active:scale-95 shadow-sm">
                        Public Site
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </Link>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-8 bg-gray-50 overflow-y-auto">
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>

        <!-- Mobile Overlay -->
        <div 
            v-if="showingSidebar" 
            @click="showingSidebar = false" 
            class="fixed inset-0 bg-black/80 z-40 lg:hidden backdrop-blur-sm"
        ></div>
    </div>
</template>

<style>
/* Global overrides for admin panel components */
.v-reveal-admin {
    opacity: 0;
    transform: translateY(20px);
    animation: revealAdmin 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes revealAdmin {
    to { opacity: 1; transform: translateY(0); }
}

/* Scrollbar for sidebar and main content */
::-webkit-scrollbar {
    width: 6px;
}
::-webkit-scrollbar-track {
    @apply bg-zinc-950;
}
::-webkit-scrollbar-thumb {
    @apply bg-zinc-900 hover:bg-primary rounded-full transition-colors;
}
</style>
