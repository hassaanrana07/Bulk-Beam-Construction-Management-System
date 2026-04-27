<script setup>
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const page = usePage();
const settings = computed(() => page.props.settings || {});
const siteName = computed(() => settings.value?.site_name || 'Brick & Beam');
const companyLogo = computed(() => settings.value?.company_logo || null);

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const brandingMode = computed(() => settings.value?.login_branding_mode || 'both');

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log In | Brick & Beam" />

    <div class="min-h-screen flex flex-col md:flex-row font-sans overflow-hidden">
        <!-- Left Side: Login Inputs (Dark) -->
        <div class="w-full md:w-1/2 bg-navy-950 flex flex-col justify-center px-12 lg:px-24 py-20 relative shadow-2xl z-20">
            <div class="max-w-md mx-auto w-full space-y-12 animate-fade-in-left">
                <!-- Mobile Branding -->
                <div v-if="brandingMode !== 'hidden'" class="md:hidden mb-12 flex items-center gap-4">
                    <img v-if="(brandingMode === 'logo_only' || brandingMode === 'both') && companyLogo" 
                         :src="companyLogo" class="h-16 w-auto object-contain drop-shadow-xl" :alt="siteName" />
                    <h1 v-if="brandingMode === 'name_only' || brandingMode === 'both'" 
                        class="text-3xl font-black uppercase tracking-tighter leading-none flex items-center gap-1">
                        <span class="text-white">{{ siteName.split(' ')[0] }}</span>
                        <span v-if="siteName.split(' ')[1]" class="text-primary/50">&</span>
                        <span class="text-primary">{{ siteName.split(' ').slice(1).join(' ') }}</span>
                    </h1>
                </div>

                <div class="space-y-2">
                    <h2 class="text-3xl font-black text-white uppercase tracking-tight">System Access</h2>
                    <p class="text-zinc-500 text-xs font-bold uppercase tracking-widest">Authenticate credentials to proceed</p>
                </div>

                <div v-if="status" class="p-4 bg-primary/10 border-l-4 border-primary text-primary text-xs font-black uppercase tracking-widest">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-8">
                    <div class="space-y-4">
                        <div class="group">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 group-focus-within:text-primary transition-colors block mb-2">Identifier</label>
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                autofocus
                                class="w-full bg-zinc-900 border-l-4 border-transparent focus:border-primary focus:bg-black focus:ring-0 px-6 py-4 text-white font-bold placeholder:text-zinc-700 transition-all outline-none"
                                placeholder="ACCESS ID"
                            />
                            <div v-if="form.errors.email" class="text-[10px] font-black text-red-600 mt-2 uppercase tracking-widest">{{ form.errors.email }}</div>
                        </div>

                        <div class="group">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 group-focus-within:text-primary transition-colors block mb-2">Secure Key</label>
                            <input
                                v-model="form.password"
                                type="password"
                                required
                                class="w-full bg-zinc-900 border-l-4 border-transparent focus:border-primary focus:bg-black focus:ring-0 px-6 py-4 text-white font-bold placeholder:text-zinc-700 transition-all outline-none"
                                placeholder="••••••••"
                            />
                            <div v-if="form.errors.password" class="text-[10px] font-black text-red-600 mt-2 uppercase tracking-widest">{{ form.errors.password }}</div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center group cursor-pointer">
                            <input type="checkbox" v-model="form.remember" class="w-4 h-4 bg-zinc-900 border-zinc-700 text-primary focus:ring-0 rounded-none cursor-pointer" />
                            <span class="ms-3 text-[10px] font-black text-zinc-500 uppercase tracking-widest group-hover:text-white transition-colors">Maintain Session</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-primary hover:bg-primary-hover text-white py-5 px-8 font-black uppercase tracking-[0.3em] transition-all active:scale-95 disabled:opacity-50 shadow-[0_10px_30px_-10px_#1656D1] rounded-lg"
                    >
                        {{ form.processing ? 'Authenticating...' : 'Establish Connection' }}
                    </button>
                    
                    <div class="flex flex-col items-center gap-4 pt-4">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-[10px] font-black text-zinc-600 hover:text-primary uppercase tracking-widest transition-colors"
                        >
                            Reset Access Protocols
                        </Link>
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] text-zinc-700 font-bold uppercase tracking-widest">New to the Matrix?</span>
                            <Link
                                :href="route('register')"
                                class="text-[10px] font-black text-white hover:text-primary uppercase tracking-widest transition-colors underline decoration-blue-600/30 underline-offset-4"
                            >
                                Request Enrollment
                            </Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Side: Company Info (Blue) -->
        <div class="w-full md:w-1/2 bg-primary relative overflow-hidden flex flex-col justify-center px-12 lg:px-24 py-20">
            <!-- Background Decoration -->
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M0 40L40 0H20L0 20M40 40V20L20 40" stroke="white" stroke-width="2" fill="none"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern)"/>
                </svg>
            </div>
            
            <div v-if="brandingMode !== 'hidden'" class="relative z-10 space-y-10 text-white max-w-lg animate-fade-in-right">
                <div>
                    <div v-if="(brandingMode === 'logo_only' || brandingMode === 'both') && companyLogo" class="flex items-center gap-6 mb-8">
                        <img :src="companyLogo" class="h-32 w-auto object-contain drop-shadow-2xl" :alt="siteName" />
                    </div>
                    <h1 v-if="brandingMode === 'name_only' || brandingMode === 'both'" class="text-7xl lg:text-8xl font-black uppercase tracking-tighter leading-none mb-6 flex flex-col">
                        <span class="text-white">{{ siteName.split(' ')[0] }}</span>
                        <div v-if="siteName.split(' ').length > 1" class="flex items-center gap-4">
                            <span class="text-black/30">&</span>
                            <span class="text-black">{{ siteName.split(' ').slice(1).join(' ') }}</span>
                        </div>
                    </h1>
                    <p class="text-xl font-bold uppercase tracking-widest opacity-90 border-l-4 border-black pl-6 py-2">
                        Enterprise Performance & Operational Orchestration
                    </p>
                </div>

                <div class="space-y-6 pt-8">
                    <div class="flex items-center gap-4 group">
                        <div class="w-12 h-12 bg-black flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <span class="text-sm font-black uppercase tracking-[0.2em]">Real-time Lead Tracking</span>
                    </div>

                    <div class="flex items-center gap-4 group">
                        <div class="w-12 h-12 bg-black flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        </div>
                        <span class="text-sm font-black uppercase tracking-[0.2em]">Project Analytics</span>
                    </div>

                    <div class="flex items-center gap-4 group">
                        <div class="w-12 h-12 bg-black flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-sm font-black uppercase tracking-[0.2em]">Team Management</span>
                    </div>

                    <div class="flex items-center gap-4 group">
                        <div class="w-12 h-12 bg-black flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                        </div>
                        <span class="text-sm font-black uppercase tracking-[0.2em]">Full CMS Control</span>
                    </div>
                </div>

                <div class="pt-12 text-[10px] font-black text-black uppercase tracking-[0.5em] opacity-60">
                    &copy; {{ new Date().getFullYear() }} {{ siteName }} // INFRASTRUCTURE
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes pulse-slow {
    0%, 100% { transform: scale(1.1); opacity: 0.2; }
    50% { transform: scale(1.15); opacity: 0.25; }
}
.animate-pulse-slow {
    animation: pulse-slow 20s infinite ease-in-out;
}

@keyframes slide-right {
    from { transform: translateX(-100px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
.animate-slide-right {
    animation: slide-right 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes fade-up {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
.animate-fade-up {
    animation: fade-up 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes zoom-fade {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.animate-zoom-fade {
    animation: zoom-fade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.delay-200 { animation-delay: 0.2s; }
.delay-300 { animation-delay: 0.4s; }

.animate-fade {
    animation: fade-up 1s ease-out forwards 0.8s;
    opacity: 0;
}
</style>
