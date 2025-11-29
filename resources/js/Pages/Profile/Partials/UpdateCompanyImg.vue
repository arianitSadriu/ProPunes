<script setup>
import InputError from "@/components/InputError.vue";
import InputLabel from "@/components/InputLabel.vue";
import ImgInput from "@/components/ImgInput.vue";
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({
    authUser: {type: Number},
    company: {type: Object},
});
const form = useForm({image: ""});
const imageSrc = ref(props.company?.image ? `/storage/${props.company?.image}` : "");
const updateImage = () => {
    form.post(route("updateCompanyImage", props.company.id), {
        preserveScroll: true,
        onSuccess: () => {
            imageSrc.value = form.image ? URL.createObjectURL(form.image) : `/storage/${props.company.image}`;
            form.image = "";
            document.getElementById("image").value = "";
        },
    });
};
const previewImage = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        imageSrc.value = URL.createObjectURL(file);
    }
};
</script>

<template>
    <section v-if="company">
        <header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Company Image</h2>
                    <p class="mt-1 text-sm text-gray-600">Update your company's image.</p>
                </div>
            </div>
        </header>

        <form @submit.prevent="updateImage">
            <div>
                <div v-if="imageSrc" class="mt-2">
                    <img
                        :src="imageSrc"
                        alt="Image Preview"
                        class="w-36 h-36 object-cover rounded-full border-2 border-indigo-500"
                    />
                </div>
                <ImgInput
                    id="image"
                    accept="image/*"
                    class="hidden"
                    name="image"
                    @change="previewImage"
                />
                <InputError class="mt-2" :message="form.errors.image"/>
            </div>


            <div class="flex justify-start space-x-4">
                <label
                    for="image"
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
    <section v-if="!company">
        <header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">You don`t have a company</h2>
                </div>
            </div>
        </header>
    </section>
</template>
