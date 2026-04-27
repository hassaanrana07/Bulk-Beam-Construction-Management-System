<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SectionRenderer from '@/Components/SectionRenderer.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    page: Object,
    locations: Array
});

const form = useForm({
    name: '',
    email: '',
    subject: '',
    phone: '',
    company: '',
    message: ''
});

const submit = () => {
    form.post(route('contact.submit'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <PublicLayout :key="$page.url">
        <Head :title="page?.title || 'Contact Us'" />

        <SectionRenderer v-if="page" :sections="page.sections" />

        <!-- Fallback if no sections -->
        <div v-else class="pt-48 pb-32 bg-navy-950 text-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <h1 class="text-6xl md:text-8xl font-black uppercase tracking-tighter mb-8 italic">Contact</h1>
            </div>
        </div>

        <section class="py-32 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-20">
                <!-- Contact Form -->
                <div>
                    <h2 class="text-3xl font-black uppercase tracking-tight mb-12 italic tracking-tighter text-slate-900 underline decoration-primary decoration-4 underline-offset-8">Send an Inquiry</h2>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Full Name</label>
                                <input v-model="form.name" type="text" class="w-full bg-slate-50 border-none px-6 py-4 focus:ring-2 focus:ring-primary transition-all text-sm font-bold uppercase tracking-tight rounded-xl" placeholder="John Doe">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Email Address</label>
                                <input v-model="form.email" type="email" class="w-full bg-slate-50 border-none px-6 py-4 focus:ring-2 focus:ring-primary transition-all text-sm font-bold uppercase tracking-tight rounded-xl" placeholder="john@example.com">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Phone Number</label>
                                <input v-model="form.phone" type="text" class="w-full bg-slate-50 border-none px-6 py-4 focus:ring-2 focus:ring-primary transition-all text-sm font-bold uppercase tracking-tight rounded-xl" placeholder="+1 (555) 000-0000">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Subject</label>
                                <input v-model="form.subject" type="text" class="w-full bg-slate-50 border-none px-6 py-4 focus:ring-2 focus:ring-primary transition-all text-sm font-bold uppercase tracking-tight rounded-xl" placeholder="Project Inquiry">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Company (Optional)</label>
                            <input v-model="form.company" type="text" class="w-full bg-slate-50 border-none px-6 py-4 focus:ring-2 focus:ring-primary transition-all text-sm font-bold uppercase tracking-tight rounded-xl" placeholder="Global Corp">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Project Details</label>
                            <textarea v-model="form.message" rows="6" class="w-full bg-slate-50 border-none px-6 py-4 focus:ring-2 focus:ring-primary transition-all text-sm font-bold uppercase tracking-tight rounded-xl" placeholder="Describe your project requirements..."></textarea>
                        </div>
                        <button type="submit" :disabled="form.processing" class="w-full bg-primary text-white font-black uppercase tracking-widest py-6 hover:translate-y-[-4px] transition-all disabled:opacity-50 rounded-xl shadow-xl shadow-primary/20">
                            {{ form.processing ? 'Transmitting...' : 'Transmit Inquiry Protocol' }}
                        </button>
                        <transition name="fade">
                            <p v-if="$page.props.flash.success" class="text-[10px] font-black text-green-600 uppercase tracking-widest mt-6 text-center bg-green-50 py-4 rounded-xl border border-green-100 italic">
                                {{ $page.props.flash.success }}
                            </p>
                        </transition>
                    </form>
                </div>

                <!-- Office Locations -->
                <div class="space-y-12">
                    <h2 class="text-3xl font-black uppercase tracking-tight mb-12 italic tracking-tighter text-slate-900 underline decoration-primary decoration-4 underline-offset-8">HQ Nodes</h2>
                    <div class="grid grid-cols-1 gap-8">
                        <div v-for="location in locations" :key="location.id" class="p-10 bg-slate-50 border-l-8 border-primary rounded-2xl group hover:bg-slate-100 transition-colors">
                            <h3 class="text-xl font-black uppercase tracking-tight mb-4 text-slate-900 italic">{{ location.name }}</h3>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mb-6 leading-relaxed">{{ location.address }}<br>{{ location.city }}, {{ location.state }} {{ location.zip_code }}</p>
                            <div class="space-y-2 text-xs font-black uppercase tracking-[0.2em]">
                                <p class="text-primary">{{ location.phone }}</p>
                                <p class="text-slate-900">{{ location.email }}</p>
                            </div>
                        </div>
                        
                        <div class="p-10 border-4 border-dashed border-slate-100 bg-white rounded-3xl relative overflow-hidden group">
                            <div class="absolute inset-0 bg-primary/[0.02] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <h3 class="text-xl font-black uppercase tracking-tight mb-4 text-slate-900 relative z-10 italic">Secure Support</h3>
                            <p class="text-[11px] font-bold text-slate-400 mb-8 relative z-10 uppercase tracking-widest leading-relaxed">Need immediate assistance with a structural or site emergency? Our 24/7 hotline is available for verified clients.</p>
                            <p class="text-3xl font-black text-primary tracking-tighter relative z-10 italic">1-800-BRICK-BEAM</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
