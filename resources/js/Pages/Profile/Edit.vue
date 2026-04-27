<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ModuleHeader from '@/Components/Admin/ModuleHeader.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Operator Profile" />

    <AdminLayout>
        <ModuleHeader title="Operator Profile">
            <template #subtitle>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-600 mt-2">
                    Managing extensive personal data and access credentials.
                </p>
            </template>
        </ModuleHeader>

        <div class="space-y-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="space-y-12">
                     <div class="bg-black border border-zinc-900 p-10 shadow-2xl">
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500 border-b border-zinc-900 pb-4 italic mb-6">Identity Protocols</h3>
                        <div class="theme-admin-form">
                            <UpdateProfileInformationForm
                                :must-verify-email="mustVerifyEmail"
                                :status="status"
                                class="max-w-xl"
                            />
                        </div>
                    </div>

                    <div class="bg-black border border-zinc-900 p-10 shadow-2xl">
                        <h3 class="text-xs font-black uppercase tracking-[0.4em] text-zinc-500 border-b border-zinc-900 pb-4 italic mb-6">Security Clearance</h3>
                        <div class="theme-admin-form">
                            <UpdatePasswordForm class="max-w-xl" />
                        </div>
                    </div>
                </div>

                <div class="bg-red-950/10 border border-red-900/30 p-10 shadow-2xl h-fit">
                    <h3 class="text-xs font-black uppercase tracking-[0.4em] text-red-600 border-b border-red-900/30 pb-4 italic mb-6">Danger Zone</h3>
                    <div class="theme-admin-form">
                        <DeleteUserForm class="max-w-xl" />
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
/* Override partials styles globally for this page */
.theme-admin-form input {
    background-color: #09090b !important;
    border-color: #27272a !important;
    color: white !important;
}
.theme-admin-form label {
    color: #a1a1aa !important;
    text-transform: uppercase;
    font-size: 0.65rem;
    font-weight: 900;
    letter-spacing: 0.2em;
}
.theme-admin-form button {
    background-color: #ff6b00 !important;
    color: black !important;
    font-weight: 900 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.2em !important;
    border-radius: 0 !important;
}
.theme-admin-form section header h2 {
    display: none;
}
.theme-admin-form section header p {
    color: #52525b !important;
    font-size: 0.65rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.1em !important; 
    margin-bottom: 1.5rem;
}
</style>
