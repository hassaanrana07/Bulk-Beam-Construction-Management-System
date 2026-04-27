<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    full_name: '',
    email: '',
    project_type: '',
    details: ''
});

const submit = () => {
    form.post(route('contact.submit'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <section class="py-32 bg-[#0b0f19] px-4 md:px-8 font-sans">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row gap-20">
                <!-- Content Left -->
                <div class="lg:w-5/12 space-y-12">
                    <div class="space-y-6">
                        <h2 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter leading-tight">
                            Let's build something extraordinary together.
                        </h2>
                        <p class="text-slate-500 font-medium">
                            Our team of experts is ready to assist you with comprehensive project management and construction expertise.
                        </p>
                    </div>

                    <div class="space-y-8">
                        <!-- Contact Info -->
                        <div v-for="(info, idx) in [
                            { label: 'Email Us', value: 'admin@brickandbeam.com', icon: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' },
                            { label: 'Call Specialist', value: '+1 (555) 762-2345', icon: 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z' }
                        ]" :key="info.label" class="flex items-start gap-6 group">
                            <div class="w-12 h-12 bg-slate-900 border border-white/5 flex items-center justify-center text-amber-500 group-hover:bg-amber-500 group-hover:text-slate-900 transition-all rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="info.icon"></path></svg>
                            </div>
                            <div>
                                <p class="text-white font-black uppercase tracking-widest text-xs">{{ info.label }}</p>
                                <p class="text-slate-500 text-xs mt-1">{{ info.value }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Right -->
                <div class="lg:w-7/12">
                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-slate-900/40 p-10 md:p-14 rounded-[2.5rem] border border-white/5 shadow-2xl">
                        <!-- Inputs -->
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Full Name</label>
                            <input v-model="form.full_name" type="text" placeholder="John Doe" class="w-full bg-[#0b0f19] border border-white/5 rounded-xl px-6 py-4 text-white text-sm focus:border-amber-500/50 focus:ring-0 transition-all placeholder:text-slate-700">
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 block">Business Email</label>
                            <input v-model="form.email" type="email" placeholder="john@company.com" class="w-full bg-[#0b0f19] border border-white/5 rounded-xl px-6 py-4 text-white text-sm focus:border-amber-500/50 focus:ring-0 transition-all placeholder:text-slate-700">
                        </div>

                        <div class="space-y-4 md:col-span-2">
                            <textarea v-model="form.details" rows="4" placeholder="How can we help you?" class="w-full bg-[#0b0f19] border border-white/5 rounded-xl px-6 py-4 text-white text-sm focus:border-amber-500/50 focus:ring-0 transition-all placeholder:text-slate-700 resize-none"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <button type="submit" class="w-full bg-amber-500 text-slate-900 py-6 text-[10px] font-black uppercase tracking-[0.5em] hover:bg-white transition-all rounded-xl shadow-2xl shadow-amber-500/20">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>
