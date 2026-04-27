<script setup>
import { computed } from 'vue';

const props = defineProps({
    content: Object,
    globalData: {
        type: Object,
        default: () => ({})
    }
});

const defaultCerts = [
    { title: 'ISO 9001:2015', description: 'Quality Management' },
    { title: 'LEED Platinum', description: 'Sustainable Construction' },
    { title: 'OSHA VPP', description: 'Safety Excellence' },
    { title: 'AISC Certified', description: 'Structural Steel' }
];

const certs = computed(() => {
    // If we have dynamic certifications from database
    if (props.globalData.certifications && props.globalData.certifications.length > 0) {
        return props.globalData.certifications.map(c => ({
            title: c.name,
            description: c.issuing_organization
        }));
    }
    // Fallback to manual features in CMS section
    if (props.content.features && props.content.features.length > 0) {
        return props.content.features;
    }
    return defaultCerts;
});
</script>

<template>
    <section class="py-12 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-[9px] font-black uppercase tracking-[0.5em] text-slate-400 italic">Accreditations & Trust</span>
            </div>
            
            <div class="flex flex-wrap justify-center items-center gap-12 md:gap-24 opacity-40 hover:opacity-80 transition-all duration-700">
                <!-- ISO 9001 -->
                <div class="flex items-center gap-4 text-slate-900 group">
                    <svg class="w-7 h-7 text-slate-300 group-hover:text-primary transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.45l8.1 14.1H3.9L12 5.45z"></path></svg>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">ISO 9001:2015</span>
                </div>
                <!-- OSHA -->
                <div class="flex items-center gap-4 text-slate-900 group">
                    <svg class="w-7 h-7 text-slate-300 group-hover:text-primary transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.47 4.19-3.05 7.85-7 8.97v-8.97H5V6.3l7-3.11v8.8z"></path></svg>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">OSHA PLATINUM</span>
                </div>
                <!-- LEED -->
                <div class="flex items-center gap-4 text-slate-900 group">
                    <svg class="w-7 h-7 text-slate-300 group-hover:text-primary transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66c.95-2.37 2.4-5.28 4.61-7.1 1.7-1.4 3.75-2.43 5.38-2.58 4.2-.38 5.3 4.1 5.3 4.1s-.4-4.82-4-7.42c-2-1.45-3.8-2.67-4.4-4.2-.6-1.53.4-3.5 1.5-4.5 1.1-1 3.5-1.1 5-.3a12.16 12.16 0 011.9 2H23V0h-4c-3.1 0-5.4 1.2-7 1.8-1.6.6-4.6 1.8-4.6 6 0 4.2 3.1 6.5 4.6 7.6 1.5 1.1 4 2.6 5 4.6s-1.1 4-2.5 4.2c-1.4.2-3.1-1.3-4.1-3.6-1.1-2.4-2.2-4.6-2.2-4.6l-1.9.6c1.1 2.3 2.1 4.5 2.1 6.8s-.9 4.3-2.4 4.8-3.4-1.2-4.1-2c-.7-.8-1.5-2.5-2-5.4-.5-2.9-.6-5.8-.6-5.8s1.1 0 1.9.2c.8.2 1.9.5 2.5 1.4s.5 2.6.4 3.8"></path></svg>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">LEED GOLD CERTIFIED</span>
                </div>
                <!-- SAFETY -->
                <div class="flex items-center gap-4 text-slate-900 group">
                    <svg class="w-7 h-7 text-slate-300 group-hover:text-primary transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94A5.01 5.01 0 0011 15.9V19H7v2h10v-2h-4v-3.1a5.01 5.01 0 003.61-2.96c2.47-.31 4.39-2.39 4.39-4.94V7c0-1.1-.9-2-2-2zM5 8V7h2v3.82C5.84 10.4 5 9.3 5 8zm14 0c0 1.3-.84 2.4-2 2.82V7h2v1z"></path></svg>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">SAFETY EXCELLENCE</span>
                </div>
            </div>
        </div>
    </section>
</template>
