<script setup>
import Checkbox from '@/components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>
        <div class="z-30 mt-6 w-full overflow-hidden bg-gradient-to-b from-cyan-100 via-white to-white px-6 pb-4 shadow-lg sm:max-w-sm sm:rounded-lg border border-gray-100">
            <img src="/storage/images/logo.png" width="150px" height="150px" class="mx-auto" alt="logo">
            <span class="text-xl font-bold flex justify-center mb-2">Welcome Back</span>
            <p class="text-center text-gray-600 mb-6">Log in to continue your journey with us.</p>
        <form @submit.prevent="submit" class="">
            <div>
                <span class="absolute flex mt-1.5 ml-2 bg-transparent ">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect width="24" height="24" fill="none"></rect> <path fill-rule="evenodd" clip-rule="evenodd" d="M5.96802 4H18.032C18.4706 3.99999 18.8491 3.99998 19.1624 4.02135C19.4922 4.04386 19.8221 4.09336 20.1481 4.22836C20.8831 4.53284 21.4672 5.11687 21.7716 5.85195C21.9066 6.17788 21.9561 6.50779 21.9787 6.83762C22 7.15088 22 7.52936 22 7.96801V16.032C22 16.4706 22 16.8491 21.9787 17.1624C21.9561 17.4922 21.9066 17.8221 21.7716 18.1481C21.4672 18.8831 20.8831 19.4672 20.1481 19.7716C19.8221 19.9066 19.4922 19.9561 19.1624 19.9787C18.8491 20 18.4706 20 18.032 20H5.96801C5.52936 20 5.15088 20 4.83762 19.9787C4.50779 19.9561 4.17788 19.9066 3.85195 19.7716C3.11687 19.4672 2.53284 18.8831 2.22836 18.1481C2.09336 17.8221 2.04386 17.4922 2.02135 17.1624C1.99998 16.8491 1.99999 16.4706 2 16.032V7.96802C1.99999 7.52937 1.99998 7.15088 2.02135 6.83762C2.04386 6.50779 2.09336 6.17788 2.22836 5.85195C2.53284 5.11687 3.11687 4.53284 3.85195 4.22836C4.17788 4.09336 4.50779 4.04386 4.83762 4.02135C5.15088 3.99998 5.52937 3.99999 5.96802 4ZM4.31745 6.27777C4.68114 5.86214 5.3129 5.82002 5.72854 6.1837L11.3415 11.095C11.7185 11.4249 12.2815 11.4249 12.6585 11.095L18.2715 6.1837C18.6871 5.82002 19.3189 5.86214 19.6825 6.27777C20.0462 6.69341 20.0041 7.32517 19.5885 7.68885L13.9755 12.6002C12.8444 13.5899 11.1556 13.5899 10.0245 12.6002L4.41153 7.68885C3.99589 7.32517 3.95377 6.69341 4.31745 6.27777Z" fill="#454545"></path> </g></svg>
                </span>
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full pl-8"
                    v-model="form.email"
                    placeholder="Email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <span class="absolute mt-1.5 ml-2 flex">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 14.5V16.5M7 10.0288C7.47142 10 8.05259 10 8.8 10H15.2C15.9474 10 16.5286 10 17 10.0288M7 10.0288C6.41168 10.0647 5.99429 10.1455 5.63803 10.327C5.07354 10.6146 4.6146 11.0735 4.32698 11.638C4 12.2798 4 13.1198 4 14.8V16.2C4 17.8802 4 18.7202 4.32698 19.362C4.6146 19.9265 5.07354 20.3854 5.63803 20.673C6.27976 21 7.11984 21 8.8 21H15.2C16.8802 21 17.7202 21 18.362 20.673C18.9265 20.3854 19.3854 19.9265 19.673 19.362C20 18.7202 20 17.8802 20 16.2V14.8C20 13.1198 20 12.2798 19.673 11.638C19.3854 11.0735 18.9265 10.6146 18.362 10.327C18.0057 10.1455 17.5883 10.0647 17 10.0288M7 10.0288V8C7 5.23858 9.23858 3 12 3C14.7614 3 17 5.23858 17 8V10.0288" stroke="#3f3f3f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                </span>
                <TextInput
                    id="password"
                    type="password"
                    placeholder="Password"
                    class="mt-1 block w-full pl-8"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-2 flex justify-between">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600"
                        >Remember me</span
                    >
                </label>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Forgot your password?
                </Link>
            </div>

            <div class="mt-4 flex items-center ">
                <PrimaryButton
                    class="w-full bg-gray-800 hover:bg-gray-700 border border-gray-200 py-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    <span class="mx-auto ">Log In</span>
                </PrimaryButton>
            </div>
            <div class="w-full flex items-center text-sm text-center text-gray-600 my-4">
                <hr class="flex-grow border-t border-gray-300">
                <span class="px-2">Or sign in with</span>
                <hr class="flex-grow border-t border-gray-300">
            </div>
          <div class="mt-4 flex items-center ">
    <a
        :href="route('auth.google')"
        class="w-full bg-white hover:bg-gray-100 shadow-md border border-gray-200 py-2.5 flex justify-center rounded-md"
    >
      <span class="mx-auto text-emerald-600">
<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
  <path fill="#EA4335" d="M10 3.48c1.69 0 3.05.73 3.75 1.34l2.76-2.76C13.05.87 10.69 0 10 0 6.48 0 3.46 2.11 2.05 5.19l3.24 2.52C5.34 5.33 6.96 3.48 10 3.48z"/>
  <path fill="#34A853" d="M17.64 9.2c0-.64-.06-1.12-.15-1.61H10v3.05h4.84c-.2 1.06-1.3 3.1-4.84 3.1-2.9 0-5.33-1.9-6.2-4.45l-3.2 2.46C1.72 15.32 5.02 18 10 18c5.52 0 8.64-4.56 8.64-8.8z"/>
  <path fill="#FBBC05" d="M2.85 10.2l-3.2-2.46C-.3 8.52-.3 9.48.02 10.2H2.85z"/>
  <path fill="#4285F4" d="M10 3.48c1.69 0 3.05.73 3.75 1.34l2.76-2.76C13.05.87 10.69 0 10 0z"/>
</svg>

      </span>
    </a>
</div>
        </form>
        </div>
    </GuestLayout>
</template>
