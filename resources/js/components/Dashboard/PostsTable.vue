<script setup>
    /**
     * PostsTable Component
     * Displays a table of job posts with actions to view details or delete posts.
     * Uses Inertia.js for form handling and routing.
     * Includes a confirmation modal for delete operations.
     */
    import {useForm, Link} from "@inertiajs/vue3";
    import {ref} from "vue";
    import ConfirmationModal from "@/components/ConfirmationModal.vue";

    /**
     * Component Props
     * @property {Array} Posts - Array of job post objects to display in the table
     */
    const props = defineProps({
        Posts:Array,
    });
    /**
     * Form state using Inertia.js useForm hook
     * Manages the form data for delete operations
     */
    const form = useForm({
       id:''
    });

    /**
     * Ref to control visibility of the delete confirmation modal
     */
    const showDeleteModal = ref(false);
    /**
     * Ref to store the ID of the selected post for deletion
     */
    const selectedPostId = ref(null);

    /**
     * Handle the confirmation of post deletion
     * Sends a delete request via Inertia.js to the 'post.delete' route
     * Closes the modal and resets the selected post ID after deletion
     */
    const confirmDelete = () => {
        if (selectedPostId.value !== null) {
            form.delete(route('post.delete', { id: selectedPostId.value }));
            showDeleteModal.value = false;
            selectedPostId.value = null;
        }
    };

    /**
     * Open the delete confirmation modal for a specific post
     * Sets the selected post ID and shows the modal
     * 
     * @param {number} id - The ID of the post to delete
     */
    const openDeleteModal = (id) => {
        selectedPostId.value = id;
        showDeleteModal.value = true;
    };

</script>

<template>
    <!-- Main table container -->
    <div id="table1" >
        <div class="mx-auto w-full ">
            <!-- Table wrapper with shadow and rounded corners -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">

                <!-- Scrollable table with max height -->
                <div class="overflow-x-auto max-h-96">
                    <!-- Posts table -->
                    <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                        <!-- Table header with column titles -->
                        <thead class="text-xs text-gray-100 uppercase bg-gray-700 sticky top-0 z-10">
                        <tr>
                            <th scope="col" class="px-6 py-3">Titulli</th>
                            <th scope="col" class="px-6 py-3">Kompania</th>
                            <th scope="col" class="px-6 py-3">Expiration</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th colspan="5" class="px-6 py-3 text-center">Action</th>
                        </tr>
                        </thead>
                        <!-- Table body rows -->
                        <tbody>
                        <!-- Empty state message when no posts exist -->
                        <tr v-if="Posts.length === 0">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No Posts Yet
                            </td>
                        </tr>
                        <!-- Loop through posts and display each row -->
                        <tr v-for="post in Posts" :key="post.id" class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <!-- Post title -->
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ post.tittle }}</td>
                            <!-- Company name -->
                            <td class="px-6 py-4">{{ post.company.name}}</td>
                            <!-- Job expiration date -->
                            <td class="px-6 py-4">{{ post.expiration_date }}</td>
                            <!-- Company email -->
                            <td class="px-6 py-4">{{ post.user.email }}</td>

                            <!-- Delete button -->
                            <td class=" py-4 text-center">
                                    <button
                                        type="button"
                                        @click="openDeleteModal(post.id)"
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                        Delete
                                    </button>

                            </td>
                            <!-- Details link to view full post information -->
                            <td class=" py-4 text-center">
                                <Link
                                    :href="route('jobs.show', {id: post.id})"
                                    class="text-blue-500 underline "
                                >
                                    Details
                                </Link>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete confirmation modal component -->
    <ConfirmationModal
        :show="showDeleteModal"
        title="Confirm Deletion"
        message="Are you sure you want to delete this post?"
        @close="showDeleteModal = false"
        @confirm="confirmDelete"
    />
</template>


