<script setup>
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import {ref} from "vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    cities: {
        type: Array,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    lastname: user.lastname,
    email: user.email,
    city_id: user.city_id,
    address: user.address,
    birthday: user.birthday,
    phone: user.phone,
    gender: user.gender,
    role: user.role,
});
console.log('user data' , form)
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            :class="{'w-[50%]' : $page.props.auth.user.role === 'employee'}"
            class="mt-6 space-y-6 w-full"
            id="updateUser"
        >
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.lastname" />
            </div>
            <div>
                <InputLabel for="lastname" value="Lastname" />

                <TextInput
                    id="lastname"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.lastname"
                    required
                    autocomplete="lastname"
                />

                <InputError class="mt-2" :message="form.errors.lastname" />
            </div>
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            <div class="flex space-x-2">
            <div class="w-1/2">
                <InputLabel for="city_id" value="City"/>

                <select
                    name="city_id"
                    id="city_id"
                    ref="input"
                    v-model="form.city_id"
                    class="rounded-md border-gray-300 p-1 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1 block w-full">
                    <option disabled :value="user.city_id" selected>{{user.city?.name}}</option>
                    <option v-for="city in cities" :key="city.id" :value="city.id">{{city.name}}</option>
                </select>

                <InputError class="mt-2" :message="form.errors.city_id"/>
            </div>
            <div class="w-1/2">
                <InputLabel for="address" value="Address" />

                <TextInput
                    id="address"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.address"
                    required
                    autocomplete="address"
                />

                <InputError class="mt-2" :message="form.errors.address" />
            </div>
            </div>
            <div class="flex space-x-2">
                <div class="w-1/2">
                    <InputLabel for="birthday" value="Birthday"/>

                    <TextInput
                        id="birthday"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.birthday"
                        autocomplete="birthday"
                    />

                    <InputError class="mt-2" :message="form.errors.birthday"/>
                </div>
                <div class="w-1/2">
                    <InputLabel for="phone" value="phone"/>

                    <TextInput
                        id="phone"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.phone"
                        autocomplete="phone"
                    />

                    <InputError class="mt-2" :message="form.errors.phone"/>
                </div>
            </div>
            <div class="flex space-x-2">
                <div class="w-1/2">
                    <InputLabel for="gender" value="gender"/>

                    <select
                        name="gender"
                        id="gender"
                        ref="input"
                        v-model="form.gender"
                        class="rounded-md border-gray-300 p-1 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1 block w-full">
                        <option disabled value="">gender</option>
                        <option value="female">Female</option>
                        <option value="male">Male</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.gender"/>
                </div>
                <div class="w-1/2">
                    <InputLabel for="role" value="role"/>

                    <select
                        name="role"
                        id="role"
                        ref="input"
                        v-model="form.role"
                        class="rounded-md border-gray-300 p-1 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1 block w-full "
                        :disabled=" $page.props.auth.user.role === 'admin'"
                        >
                        <option disabled value="">role</option>
                        <option value="employer">Employer</option>
                        <option value="employee">Employee</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.role"/>
                </div>
            </div>


        </form>
    </section>
    <footer class="flex items-center gap-4 mt-9">
        <button type="submit" form="updateUser" class="inline-flex items-center px-5 py-2 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-teal-500 rounded-lg focus:ring-4 focus:ring-primary-200 hover:bg-primary-800">
            Save
        </button>

    </footer>
</template>
