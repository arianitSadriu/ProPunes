<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import {Head} from '@inertiajs/vue3';
import UpdateCompany from "@/Pages/Profile/Partials/UpdateCompany.vue";
import UpdateCompanyImg from "@/Pages/Profile/Partials/UpdateCompanyImg.vue";
import UpdateProfileImage from "@/Pages/Profile/Partials/UpdateProfileImage.vue";

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    authUser: {
        type: Number,
    },
    company: {
        type: Object,
    },
    cities: {
        type: Array,
    },
});
console.log('useri te edit.vue', props.authUser)
console.log('kompania te edit', props.company)
</script>

<template>
    <Head title="Profile"/>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="md:flex justify-between px-4 md:space-x-4 space-y-4 md:space-y-0">
                <div class="w-full md:w-1/2 space-y-8"
                    :class="{'md:w-full' : $page.props.auth.user.role === 'employee' || $page.props.auth.user.role === 'admin' }">
                    <div
                        :class="{'w-full' : $page.props.auth.user.role === 'employee'}"
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                    >
                        <UpdateProfileImage
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            class="max-w-xl"
                        />
                    </div>
                    <div
                        :class="{'w-full' : $page.props.auth.user.role === 'employee'}"
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8 "
                    >
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            :cities="cities"
                            class="max-w-xl"
                        />
                    </div>

                </div>
                <div v-if="$page.props.auth.user.role === 'employer'" class="w-full md:w-1/2 space-y-8">
                    <div
                        id="companyImg"
                        v-if="$page.props.auth.user.role === 'employer'"
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                    >
                        <UpdateCompanyImg
                            id="company"
                            :company="company"
                            :authUser="authUser"
                            class="max-w-xl"
                        />
                    </div>
                    <div
                        v-if="$page.props.auth.user.role === 'employer'"
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                    >
                        <UpdateCompany
                            :company="company"
                            :authUser="authUser"
                            class="max-w-xl"
                        />
                    </div>
                </div>
                </div>
                <div class="px-4">
                    <div
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                    >
                        <UpdatePasswordForm class="max-w-xl"/>
                    </div>
                </div>
                <div class="px-4">
                    <div
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                    >
                        <DeleteUserForm class="max-w-xl"/>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
