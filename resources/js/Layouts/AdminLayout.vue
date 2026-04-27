<script setup>
import { ref, onMounted, computed, provide } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;
const settings = computed(() => page.props.settings || {});
const companyLogo = computed(() => settings.value?.company_logo || null);
const siteName = computed(() => settings.value?.site_name || 'Brick & Beam');
const headerStyle = computed(() => settings.value?.header_style || 'logo_and_name');
const logoWidth = computed(() => settings.value?.logo_width || 44);
const logoHeight = computed(() => settings.value?.logo_height || 44);
const showCompanyName = computed(() => settings.value?.show_company_name ?? true);
const primaryColor = computed(() => settings.value?.primary_color || '#1656D1');

const sidebarOpen = ref(true);
const userDropdownOpen = ref(false);

const isDark = ref(false);
provide('isDark', isDark);

const permissions = user.permissions || [];
const roles = user.roles || [];
const hasRole = (...roleNames) => roleNames.some(r => roles.includes(r));
const hasPermission = (permission) => permissions.includes(permission);
const isSuperAdmin = hasRole('Super Admin', 'super-admin', 'superadmin');

const ICONS = {
    dashboard:    'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    users:        'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    finance:      'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    leads:        'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    portfolio:    'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
    tasks:        'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
    attendance:   'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    staff:        'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    blog:         'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z',
    pages:        'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z',
    audit:        'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
    settings:     'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
    expenses:     'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
    vendors:      'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    inquiries:    'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
    announcements:'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
    milestones:   'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
    services:     'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    purchase:     'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
    testimonials: 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z',
    faqs:         'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    metrics:      'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
    revenue:      'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    estimate:     'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
    certifications:'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M3 9a9 9 0 0018 0V9a9 9 0 00-18 0zm6 12l-2-2 2-2m6 0l2 2-2 2',
};

const navigation = computed(() => {
    const nav = [
        { name: 'Dashboard', href: route('admin.dashboard'), icon: ICONS.dashboard },
    ];

    // ── System Metrics (Super Admin only) ────────────────────────────────────
    if (isSuperAdmin) {
        nav.push({ name: 'System Metrics', href: route('admin.system-metrics'), icon: ICONS.metrics });
        nav.push({ name: 'Revenue', href: route('admin.finance.index'), icon: ICONS.revenue });
    }

    // ── User Management ──────────────────────────────────────────────────────
    if (hasPermission('manage users') || isSuperAdmin) {
        nav.push({ name: 'Access Matrix', href: route('admin.users.index'), icon: ICONS.users });
    }

    // ── Projects / Portfolio ─────────────────────────────────────────────────
    if (hasPermission('manage portfolios') || isSuperAdmin || hasRole('Admin Manager', 'Manager', 'Finance Manager')) {
        nav.push({ name: 'Project Portfolio', href: route('admin.portfolios.index'), icon: ICONS.portfolio });
    }

    // ── Task Board ───────────────────────────────────────────────────────────
    if (hasPermission('manage tasks') || isSuperAdmin || hasRole('Admin Manager', 'Manager', 'Staff', 'Editor (Staff)', 'Finance Manager')) {
        nav.push({ name: 'Task Board', href: route('admin.tasks.index'), icon: ICONS.tasks });
    }

    // ── Milestones ────────────────────────────────────────────────────────────
    if (isSuperAdmin || hasRole('Admin Manager', 'Manager', 'Finance Manager')) {
        nav.push({ name: 'Milestones', href: route('admin.milestones.index'), icon: ICONS.milestones });
    }

    // ── Staff ────────────────────────────────────────────────────────────────
    if (hasPermission('manage staff') || isSuperAdmin || hasRole('Admin Manager', 'Manager')) {
        nav.push({ name: 'Personnel Matrix', href: route('admin.staff.index'), icon: ICONS.staff });
    }

    // ── Attendance removed per request
    /*
    if (hasPermission('manage attendance') || isSuperAdmin || hasRole('Admin Manager', 'Manager', 'Staff', 'Editor (Staff)')) {
        nav.push({ name: 'Attendance', href: route('admin.attendances.index'), icon: ICONS.attendance });
    }
    */

    // ── Announcements ────────────────────────────────────────────────────────
    if (isSuperAdmin || hasRole('Admin Manager', 'Manager', 'Finance Manager')) {
        nav.push({ name: 'Announcements', href: route('admin.announcements.index'), icon: ICONS.announcements });
    }

    // ── Financial ────────────────────────────────────────────────────────────
    if (hasPermission('view finances') || isSuperAdmin || hasRole('Finance Manager', 'Finance Support', 'Financial Support', 'Admin Manager', 'Manager')) {
        nav.push({ name: 'Revenue', href: route('admin.revenue.index'), icon: ICONS.revenue });
        nav.push({ name: 'Expenses', href: route('admin.expenses.index'), icon: ICONS.expenses });
        nav.push({ name: 'Cost Estimates', href: route('admin.estimates.index'), icon: ICONS.estimate });
        nav.push({ name: 'Procurement', href: route('admin.purchase-requests.index'), icon: ICONS.purchase });
        nav.push({ name: 'Government Registrations', href: route('admin.vendors.index'), icon: ICONS.vendors });
    }

    // ── Leads ────────────────────────────────────────────────────────────────
    if (hasPermission('manage leads') || isSuperAdmin || hasRole('Support', 'Admin Manager', 'Manager')) {
        nav.push({ name: 'Leads', href: route('admin.leads.index'), icon: ICONS.leads });
    }

    // ── Inquiries ────────────────────────────────────────────────────────────
    if (isSuperAdmin || hasRole('Support', 'Admin Manager', 'Manager', 'Editor (Staff)')) {
        nav.push({ name: 'Inquiries', href: route('admin.inquiries.index'), icon: ICONS.inquiries });
    }

    // ── CMS Content ──────────────────────────────────────────────────────────
    if (hasPermission('manage blog') || isSuperAdmin || hasRole('Editor (Staff)', 'Admin Manager', 'Manager')) {
        nav.push({ name: 'Blog / Insights', href: route('admin.blog.index'), icon: ICONS.blog });
        nav.push({ name: 'Testimonials', href: route('admin.testimonials.index'), icon: ICONS.testimonials });
        nav.push({ name: 'FAQs', href: route('admin.faqs.index'), icon: ICONS.faqs });
        nav.push({ name: 'Certifications', href: route('admin.certifications.index'), icon: ICONS.certifications });
    }

    // ── Services ─────────────────────────────────────────────────────────────
    if (isSuperAdmin || hasRole('Admin Manager', 'Manager', 'Editor (Staff)')) {
        nav.push({ name: 'Services', href: route('admin.services.index'), icon: ICONS.services });
    }

    // ── Pages ────────────────────────────────────────────────────────────────
    if (hasPermission('manage pages') || isSuperAdmin) {
        nav.push({ name: 'Site Pages', href: route('admin.pages.index'), icon: ICONS.pages });
    }

    // ── Audit ────────────────────────────────────────────────────────────────
    if (hasPermission('manage audit logs') || isSuperAdmin) {
        nav.push({ name: 'Audit Logs', href: route('admin.audit-logs.index'), icon: ICONS.audit });
    }

    // ── Settings ──────────────────────────────────────────
    if (hasPermission('manage settings') || isSuperAdmin) {
        nav.push({ name: 'Settings', href: route('admin.settings.index'), icon: ICONS.settings });
        // Estimate Settings removed per request
    }

    return nav;
});

const filteredNavigation = navigation;

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
    localStorage.setItem('sidebarState', sidebarOpen.value);
};

const logout = () => {
    router.post(route('logout'));
};

const globalSearchQuery = ref('');
const isGlobalSearchFocused = ref(false);

const performGlobalSearch = () => {
    if (!globalSearchQuery.value) return;
    router.get(route('admin.search'), { search: globalSearchQuery.value });
};

onMounted(() => {
    const savedState = localStorage.getItem('sidebarState');
    if (savedState !== null) {
        sidebarOpen.value = savedState === 'true';
    }
    // Set dark mode as default to match the requested UI
    isDark.value = true;
    document.documentElement.classList.add('dark-mode');
    document.documentElement.classList.remove('light-mode');
});
</script>

<template>
    <div :style="`--accent: ${primaryColor}; --primary-brand: ${primaryColor};`" :class="{ 'dark-mode': isDark }" class="min-h-screen bg-slate-950 flex font-sans selection:bg-amber-500 selection:text-slate-900 transition-colors duration-300">
        <!-- Sidebar -->
        <aside :class="[sidebarOpen ? 'w-72' : 'w-20', 'bg-[#111827] text-slate-300 transition-all duration-300 flex flex-col fixed inset-y-0 z-50 border-r border-slate-800/50 shadow-[20px_0_50px_rgba(0,0,0,0.3)] overflow-visible sidebar-element']">
            <!-- Branding Header -->
            <div class="h-20 flex items-center px-4 border-b border-white/5 header-border relative">
                <Link :href="route('admin.dashboard')" class="flex items-center gap-3 overflow-hidden flex-1 min-w-0 px-2">
                    <!-- Company Logo -->
                    <div v-if="headerStyle === 'logo_and_name' || headerStyle === 'only_logo'" 
                         class="flex-shrink-0 flex items-center justify-start overflow-hidden rounded-lg bg-white/5 p-1"
                         :style="{ width: sidebarOpen ? '44px' : '44px', height: sidebarOpen ? '44px' : '44px' }">
                        <img
                            v-if="companyLogo"
                            :src="companyLogo"
                            class="object-contain w-full h-full"
                            :style="{ maxWidth: '44px', maxHeight: '44px' }"
                            :alt="siteName"
                        />
                        <div v-else class="w-10 h-10 bg-amber-500 flex items-center justify-center font-black text-xl italic skew-x-[-10deg] shadow-[0_0_15px_rgba(245,158,11,0.3)] text-slate-900">{{ siteName.charAt(0) }}</div>
                    </div>
                    <!-- Company Name -->
                    <div v-if="sidebarOpen && showCompanyName && (headerStyle === 'logo_and_name' || headerStyle === 'only_name')" class="flex flex-col">
                        <span class="text-xs font-black uppercase tracking-tighter whitespace-nowrap logo-text transition-all duration-300 truncate text-white">
                            {{ siteName }}
                        </span>
                        <span class="text-[8px] font-black text-slate-500 uppercase tracking-[0.3em]">Operational Terminal</span>
                    </div>
                </Link>

                <!-- Small Toggle Arrow -->
                <button @click="toggleSidebar" class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center hover:bg-amber-500 transition-all group z-50 absolute -right-4 top-1/2 -translate-y-1/2 shadow-2xl">
                    <svg :class="{'rotate-180': !sidebarOpen}" class="w-4 h-4 text-slate-500 group-hover:text-slate-900 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>
            
            <nav class="flex-1 py-10 space-y-1 overflow-y-auto custom-scrollbar pr-2 h-full">
                <Link v-for="item in filteredNavigation" :key="item.name" :href="item.href" 
                    :class="[
                        $page.url.startsWith(item.href) && (item.href !== '/admin/dashboard' || $page.url === '/admin/dashboard')
                        ? 'bg-amber-500 text-slate-900 font-black shadow-[0_4px_20px_rgba(245,158,11,0.2)] rounded-lg mx-3' 
                        : 'text-slate-400 hover:text-white hover:bg-white/5 nav-link mx-3 rounded-lg', 
                        'flex items-center px-4 py-3.5 transition-all duration-300 group relative truncate'
                    ]">
                    <svg :class="$page.url.startsWith(item.href) && (item.href !== '/admin/dashboard' || $page.url === '/admin/dashboard') ? 'text-slate-900' : 'text-slate-500 group-hover:text-white'" class="w-5 h-5 min-w-[1.25rem] transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path :d="item.icon" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="text-[9px] font-black uppercase tracking-[0.2em] ml-4 whitespace-nowrap" v-if="sidebarOpen">{{ item.name }}</span>
                </Link>
            </nav>

            <div class="p-6 border-t border-white/5 bg-slate-950/20 sidebar-footer">
                <button @click="logout" class="group relative w-full bg-slate-800 py-3.5 overflow-hidden transition-all active:scale-95 flex items-center justify-center gap-3 rounded-xl shadow-xl">
                    <svg class="w-4 h-4 text-white relative z-10 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span v-if="sidebarOpen" class="relative z-10 text-[9px] font-black uppercase tracking-[0.3em] text-white transition-colors">
                        Logout
                    </span>
                    <div class="absolute inset-0 bg-amber-500 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main :class="[sidebarOpen ? 'pl-72' : 'pl-20', 'flex-1 transition-all duration-300 min-h-screen flex flex-col relative overflow-x-hidden main-container bg-[#0b0f19]']">
            <!-- Top Header -->
            <header class="h-20 bg-[#0b0f19]/80 backdrop-blur-xl border-b border-white/5 flex items-center justify-between px-8 sticky top-0 z-40 shadow-2xl header-element">
                <div class="flex items-center gap-6 w-full max-w-2xl">
                    <!-- Integrated Global Search -->
                    <div class="relative w-full max-w-md group hidden md:block">
                        <input 
                            v-model="globalSearchQuery"
                            @focus="isGlobalSearchFocused = true"
                            @blur="isGlobalSearchFocused = false"
                            @keyup.enter="performGlobalSearch"
                            type="text" 
                            placeholder="SEARCH PROJECTS, LEADS..." 
                            class="w-full bg-slate-900/50 border border-white/5 text-white text-[10px] font-medium uppercase tracking-widest px-11 py-3.5 focus:border-amber-500/50 focus:ring-0 transition-all placeholder:text-slate-600 rounded-xl search-input shadow-2xl"
                        >
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-600 group-focus-within:text-amber-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Notifications -->
                    <button class="relative p-2 text-slate-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-amber-500 rounded-full"></span>
                    </button>

                    <div class="relative">
                        <button @click="userDropdownOpen = !userDropdownOpen" class="flex items-center gap-4 hover:opacity-80 transition-opacity bg-slate-900/40 p-1.5 pr-4 rounded-xl border border-white/5 shadow-inner">
                            <div class="w-9 h-9 bg-amber-500 flex items-center justify-center font-black text-slate-900 text-sm rounded-lg overflow-hidden avatar-box">
                                {{ user.name.charAt(0) }}
                            </div>
                            <div class="text-left hidden sm:block">
                                <p class="text-[10px] font-black text-white uppercase tracking-widest leading-none profile-name">{{ user.name }}</p>
                                <p class="text-[8px] font-black text-amber-500/80 uppercase tracking-[0.2em] mt-1">{{ user.roles?.[0] || 'Administrator' }}</p>
                            </div>
                            <svg class="w-3 h-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <!-- Dropdown -->
                        <div v-if="userDropdownOpen" 
                            class="absolute right-0 top-full mt-4 w-60 bg-slate-900 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.5)] py-2 z-50 animate-in fade-in slide-in-from-top-2 dropdown-menu rounded-2xl p-2 backdrop-blur-xl">
                            <Link :href="route('profile.edit')" class="block px-4 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white hover:bg-white/5 transition-colors rounded-xl mx-1 dropdown-link">
                                Operational Profile
                            </Link>
                            <Link :href="route('home')" target="_blank" class="block px-4 py-3.5 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white hover:bg-white/5 transition-colors rounded-xl mx-1 dropdown-link">
                                Exit to Terminal
                            </Link>
                            <div class="h-px bg-white/5 my-2"></div>
                            <button @click="logout" class="w-full text-left px-4 py-3.5 text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-red-300 hover:bg-red-500/5 transition-colors flex items-center gap-2 rounded-xl mx-1">
                                Terminate Session
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Viewport -->
            <div class="p-10 lg:p-12 flex-1 relative bg-[#0b0f19] viewport-container min-h-screen">
                <div class="relative z-10 animate-fade-in max-w-7xl">
                    <slot />
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="p-10 border-t border-white/5 bg-[#0b0f19] text-[9px] font-black text-slate-600 uppercase tracking-[0.4em] text-center footer-element transition-colors italic">
                {{ siteName }} // STRATEGIC CONTROL PANEL // SECURED BY ENTROPY
            </footer>
        </main>
        
        <!-- Backdrop for mobile sidebar -->
        <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/90 backdrop-blur-md z-40 lg:hidden"></div>
    </div>
</template>

<style>
/* ═══════════════════════════════════════════
   PREMIUM DARK MODE — SiteForge Elite Theme
════════════════════════════════════════════ */
.dark-mode {
    --bg-main:    #0b0f19;  /* Ultra Dark Navy */
    --bg-card:    #111827;  /* Deep Slate */
    --bg-header:  #0b0f19;
    --bg-sidebar: #111827;
    --text-main:  #F8FAFC;
    --text-muted: #94A3B8;
    --text-sub:   #64748B;
    --border:     rgba(255,255,255,0.05);
    --accent:     #fbbf24;  /* Amber-400 */
}

/* Base styles for dark mode */
.dark-mode, .dark-mode .main-container {
    background-color: var(--bg-main) !important;
    color: var(--text-main) !important;
}

/* Card overrides for all components */
.dark-mode .bg-white,
.dark-mode .bg-slate-50,
.dark-mode .bg-zinc-900,
.dark-mode .bg-zinc-950 {
    background-color: var(--bg-card) !important;
    border-color: var(--border) !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
}

/* Border overrides */
.dark-mode .border-slate-100,
.dark-mode .border-slate-200,
.dark-mode .border-zinc-800,
.dark-mode .border-zinc-900,
.dark-mode .divide-slate-100,
.dark-mode .divide-zinc-800 {
    border-color: var(--border) !important;
}

/* Text overrides */
.dark-mode .text-slate-900,
.dark-mode .text-zinc-900,
.dark-mode .text-slate-800 {
    color: var(--text-main) !important;
}

.dark-mode .text-slate-600,
.dark-mode .text-slate-500,
.dark-mode .text-zinc-500 {
    color: var(--text-muted) !important;
}

.dark-mode .text-slate-400,
.dark-mode .text-zinc-400 {
    color: var(--text-sub) !important;
}

/* Watermark removal or refinement */
.dark-mode .watermark { display: none; }

/* Dashboard Specific Overrides */
.dark-mode .bg-primary\/5,
.dark-mode .bg-primary\/10 {
    background-color: rgba(245, 158, 11, 0.1) !important;
}

.dark-mode .text-primary {
    color: var(--accent) !important;
}

.dark-mode .border-primary {
    border-color: var(--accent) !important;
}

/* Table styling */
.dark-mode table thead tr {
    background-color: rgba(0,0,0,0.2) !important;
}

.dark-mode table tbody tr:hover {
    background-color: rgba(255,255,255,0.02) !important;
}

/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: var(--bg-main); }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }

@keyframes fade-in { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
.animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
</style>
