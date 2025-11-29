<script setup>
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

// Get the authenticated user
const user = usePage().props.auth.user;

// Form setup for image upload
const form = useForm({
    image: null, // Initialize the form with an empty image field
});

// Ref for image preview
const imagePreview = ref(user.image.startsWith('http') ? user.image : '/storage/' + user.image || ''); // Assuming `user.profile_image_url` contains the current image URL

// Method to handle file input change
const handleFileChange = (event) => {
    const file = event.target.files[0];
    form.image = file;

    // Generate a preview URL for the selected file
    if (file) {
        imagePreview.value = URL.createObjectURL(file);
    } else {
        imagePreview.value = user.image || ''; // Reset to the current profile image if no file is selected
    }
};
</script>

<template>
    <section>
        <header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-medium text-gray-900"> Profile Image</h2>
                    <p class="mt-1 text-sm text-gray-600">Update your profile image.</p>
                </div>
            </div>
        </header>

        <form
            @submit.prevent="form.post(route('updateUserImage'))"
            enctype="multipart/form-data"
        >
            <div>
                <!-- Preview of the current or selected image -->
                <div  v-if="imagePreview" class="mt-2">
                    <img :src="imagePreview" alt="Profile Preview" class="w-36 h-36 object-cover rounded-full border-2 border-indigo-500" />
                </div>

                <input
                    id="image_profile"
                    name="image"
                    type="file"
                    class="hidden"
                    @change="handleFileChange"
                    accept="image/*"
                />

                <InputError class="mt-2" :message="form.errors.image" />
            </div>

            <div class="flex justify-start space-x-4">
                <label
                    for="image_profile"
                    class="inline-flex items-center px-3 py-2 md:px-5 md:py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-500 rounded-lg focus:ring-4 focus:ring-primary-200 hover:bg-primary-800"
                >
                    Choose File
                </label>
                <button
                    type="submit"
                    class="inline-flex items-center px-3 py-2 md:px-5 md:py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-teal-500 rounded-lg focus:ring-4 focus:ring-primary-200 hover:bg-primary-800"
                >
                    Update Image
                </button>
            </div>
        </form>

    </section>
</template>
