<script setup>
import { Link, Head, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const settings = computed(() => page.props.settings || {});
const faqs = computed(() => page.props.faqs || []);

const siteName = computed(() => settings.value?.site_name || 'Brick & Beam');
const companyLogo = computed(() => settings.value?.company_logo || null);
const headerStyle = computed(() => settings.value?.header_style || 'logo_and_name');
const companyInfo = computed(() => settings.value?.company_info || 'Building the future with architectural excellence and industrial-grade integrity.');
const contactEmail = computed(() => settings.value?.contact_email || '');
const contactPhone = computed(() => settings.value?.contact_phone || '');
const logoHeight = computed(() => settings.value?.logo_height || 64);
const showCompanyName = computed(() => settings.value?.show_company_name ?? true);

import { useForm } from '@inertiajs/vue3';
const newsletterForm = useForm({
    email: ''
});

const submitNewsletter = () => {
    newsletterForm.post(route('newsletter.subscribe'), {
        preserveScroll: true,
        onSuccess: () => newsletterForm.reset(),
    });
};
</script>

<template>
    <div class="min-h-screen bg-[#0b0f19] text-white font-sans selection:bg-amber-500 selection:text-slate-900">

        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-[#0b0f19]/80 backdrop-blur-xl border-b border-white/5 pr-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <Link :href="route('home')" class="flex items-center gap-3 group">
                             <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center shadow-2xl shadow-amber-500/20">
                                <svg class="w-6 h-6 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                             </div>
                             <span class="text-xl font-black text-white uppercase tracking-tighter leading-none">{{ siteName }}</span>
                        </Link>
                    </div>
                    
                    <!-- Center Nav -->
                    <div class="hidden md:flex items-center gap-10">
                        <Link v-for="link in [
                            { name: 'Home', href: route('home'), active: $page.url === '/' },
                            { name: 'About', href: route('about'), active: $page.url.startsWith('/about') },
                            { name: 'Services', href: route('services'), active: $page.url.startsWith('/services') },
                            { name: 'Portfolio/Projects', href: route('portfolio'), active: $page.url.startsWith('/portfolio') },
                            { name: 'Blogs', href: route('insights'), active: $page.url.startsWith('/insights') },
                            { name: 'Contact Us', href: route('contact'), active: $page.url.startsWith('/contact') },
                        ]" :key="link.name" :href="link.href" 
                        class="text-[10px] font-black uppercase tracking-[0.2em] transition-colors" 
                        :class="link.active ? 'text-amber-500' : 'text-slate-400 hover:text-white'">
                            {{ link.name }}
                        </Link>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- No search icon or extra button here as per user request -->
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-[#0b0f19] text-white pt-32 pb-16 border-t border-white/5 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 lg:gap-24">
                    <!-- Column 1 -->
                    <div class="space-y-8">
                        <Link :href="route('home')" class="flex items-center gap-3">
                             <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center shadow-2xl shadow-amber-500/20">
                                <svg class="w-6 h-6 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                             </div>
                             <span class="text-xl font-black text-white uppercase tracking-tighter leading-none">{{ siteName }}</span>
                        </Link>
                        <p class="text-slate-500 text-sm font-medium leading-relaxed max-w-sm">
                            {{ companyInfo }}
                        </p>
                        <div class="flex gap-4">
                            <button v-for="i in 2" :key="i" class="w-10 h-10 border border-white/10 flex items-center justify-center rounded-full hover:bg-amber-500 transition-all text-slate-500 hover:text-slate-900">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" v-if="i === 1"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" v-if="i === 2"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.238 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div>
                        <h4 class="text-white text-[10px] font-black uppercase tracking-[0.3em] mb-8">Company</h4>
                        <ul class="space-y-4">
                            <li><Link href="#" class="text-slate-500 text-xs font-bold hover:text-amber-500 transition-colors uppercase tracking-widest">About Us</Link></li>
                            <li><Link href="#" class="text-slate-500 text-xs font-bold hover:text-amber-500 transition-colors uppercase tracking-widest">Our Capabilities</Link></li>
                            <li><Link href="#" class="text-slate-500 text-xs font-bold hover:text-amber-500 transition-colors uppercase tracking-widest">Partenerships</Link></li>
                            <li><Link href="#" class="text-slate-500 text-xs font-bold hover:text-amber-500 transition-colors uppercase tracking-widest">Sustainability Report</Link></li>
                        </ul>
                    </div>

                    <!-- Column 3 -->
                    <div>
                        <h4 class="text-white text-[10px] font-black uppercase tracking-[0.3em] mb-8">Service</h4>
                        <ul class="space-y-4">
                            <li><Link href="#" class="text-slate-500 text-xs font-bold hover:text-amber-500 transition-colors uppercase tracking-widest">Project Planning</Link></li>
                            <li><Link href="#" class="text-slate-500 text-xs font-bold hover:text-amber-500 transition-colors uppercase tracking-widest">Quantity Surveying</Link></li>
                            <li><Link href="#" class="text-slate-500 text-xs font-bold hover:text-amber-500 transition-colors uppercase tracking-widest">Site Surveyors</Link></li>
                            <li><Link href="#" class="text-slate-500 text-xs font-bold hover:text-amber-500 transition-colors uppercase tracking-widest">BIM Modeling</Link></li>
                        </ul>
                    </div>

                    <!-- Column 4 -->
                    <div class="space-y-8">
                        <h4 class="text-white text-[10px] font-black uppercase tracking-[0.3em] mb-8">Get Started</h4>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Subscribe to our newsletter for industry insights.</p>
                        <form @submit.prevent="submitNewsletter" class="relative group">
                            <input v-model="newsletterForm.email" type="email" placeholder="Email address" required 
                                 class="bg-slate-900 border border-white/5 rounded-lg px-5 py-4 text-xs text-white focus:border-amber-500 focus:ring-0 w-full pr-12 transition-all">
                            <button :disabled="newsletterForm.processing" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-amber-500 text-slate-900 flex items-center justify-center rounded-md hover:bg-white transition-all disabled:opacity-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Bottom bar -->
                <div class="mt-32 pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-8">
                    <p class="text-slate-600 text-[9px] font-black uppercase tracking-[0.4em]">© 2024 {{ siteName }} CMS. ALL RIGHTS RESERVED.</p>
                    <div class="flex gap-10">
                        <Link href="#" class="text-slate-600 text-[9px] font-black uppercase tracking-[0.4em] hover:text-white transition-colors">PRIVACY POLICY</Link>
                        <Link href="#" class="text-slate-600 text-[9px] font-black uppercase tracking-[0.4em] hover:text-white transition-colors">TERMS OF SERVICE</Link>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

