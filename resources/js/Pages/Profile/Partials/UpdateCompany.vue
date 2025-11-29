<script setup>
import InputError from "@/components/InputError.vue";
import InputLabel from "@/components/InputLabel.vue";
import {Link, useForm} from "@inertiajs/vue3";
import TextInput from "@/components/TextInput.vue";
import TextArea from "@/components/Jobs/TextArea.vue";
import ConfirmationModal from "@/components/ConfirmationModal.vue";
import {ref} from "vue";

const props = defineProps({
    authUser: {
        type: Number,
    },
    company: {
        type: Object,
    }
});
const form = useForm({
    user_id: props.authUser,
    name: props.company?.name,
    description: props.company?.description,
    phone: props.company?.phone,
    address: props.company?.address,
    website: props.company?.website,
    email: props.company?.email,
})
const showDeleteModal = ref(false);

const confirmDelete = () => {
    form.delete(route('deleteCompany', { id: props.company.id }));
    showDeleteModal.value = false;
};
</script>

<template>

    <section v-if="company">
        <header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">
                        Company Information
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Update your company`s information.
                    </p>
                </div>
                <form id="deleteCompany" @submit.prevent="form.delete(route('deleteCompany', {id: props.company.id}))"></form>
                <div>
                    <button
                        @click="showDeleteModal=true"
                        class="inline-flex items-center px-3 py-2 md:px-5 md:py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-red-600 rounded-lg focus:ring-4 focus:ring-primary-200 hover:bg-primary-800">
                        Delete Company
                    </button>
                </div>
            </div>
        </header>

        <form
            @submit.prevent="form.patch(route('updateCompany', {id: props.company.id}, { forceFormData: true }))"
            class="mt-6 space-y-6"
        >
            <input type="hidden" name="user_id" v-model="form.user_id">

            <div>
                <InputLabel for="name" value="Name"/>

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name"/>

            </div>
            <div>
                <InputLabel for="description" value="Description"/>

                <TextArea
                    id="description"
                    type="text"
                    class="mt-1 block w-full bg-white"
                    v-model="form.description"
                    required
                    autocomplete="description"
                />
                <InputError class="mt-2" :message="form.errors.description"/>

            </div>
            <div>
                <InputLabel for="address" value="Address"/>

                <TextInput
                    id="address"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.address"
                    required
                    autocomplete="address"
                />
                <InputError class="mt-2" :message="form.errors.address"/>

            </div>
            <div>
                <InputLabel for="phone" value="Phone"/>

                <TextInput
                    id="phone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    required
                    autocomplete="phone"
                />
                <InputError class="mt-2" :message="form.errors.phone"/>

            </div>
            <div>
                <InputLabel for="website" value="Website"/>

                <TextInput
                    id="website"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.website"
                    required
                    autocomplete="website"
                />
                <InputError class="mt-2" :message="form.errors.website"/>

            </div>
            <div>
                <InputLabel for="email" value="Email"/>

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email"/>

            </div>


        </form>
        <footer class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-5 py-2 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-teal-500 rounded-lg focus:ring-4 focus:ring-primary-200 hover:bg-primary-800">
                Update
            </button>
        </footer>
    </section>
    <section v-if="!company">
        <header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">You don`t have a company</h2>
                </div>
            </div>
        </header>
    </section>
    <ConfirmationModal
        :show="showDeleteModal"
        title="Confirm Deletion"
        message="Are you sure you want to delete your Company?"
        @close="showDeleteModal = false"
        @confirm="confirmDelete"
    />
</template>
