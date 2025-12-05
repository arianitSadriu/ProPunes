<script setup>
/**
 * UsersTable Component
 * Displays a table of system users with their profile information.
 * Allows administrators to delete users with a confirmation modal.
 * Excludes the currently logged-in user from the table.
 */
import { router, useForm } from "@inertiajs/vue3";
import ConfirmationModal from "@/components/ConfirmationModal.vue";
import {ref} from "vue";

/**
 * Component Props
 * @property {Array} Users - Array of user objects to display in the table
 */
const props = defineProps({
    Users: Array,
});

/**
 * Form state using Inertia.js useForm hook
 * Manages the form data for delete operations
 */
const form = useForm({
    id: '',
});

/**
 * Ref to control visibility of the delete confirmation modal
 */
const showDeleteModal = ref(false);
/**
 * Ref to store the ID of the selected user for deletion
 */
const selectedUserId = ref(null);

/**
 * Handle the confirmation of user deletion
 * Sends a delete request via Inertia.js to the 'user.delete' route
 * Closes the modal and resets the selected user ID after deletion
 */
const confirmDelete = () => {
    if (selectedUserId.value !== null) {
        form.delete(route('user.delete', { id: selectedUserId.value }));
        showDeleteModal.value = false;
        selectedUserId.value = null;
    }
};

/**
 * Open the delete confirmation modal for a specific user
 * Sets the selected user ID and shows the modal
 * 
 * @param {number} id - The ID of the user to delete
 */
const openDeleteModal = (id) => {
    selectedUserId.value = id;
    showDeleteModal.value = true;
};

/**
 * Format a date string to a locale-specific format (en-GB)
 * Converts ISO date strings to readable date format
 * 
 * @param {string} dateString - The date string to format
 * @return {string} Formatted date in en-GB locale (DD/MM/YYYY)
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString("en-GB");
}
</script>

<template>
    <!-- Main table container -->
    <div id="table1" class="">
        <div class="">
            <!-- Table wrapper with background and dark mode support -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden">
                <!-- Scrollable table container with max height -->
                <div class="overflow-x-auto max-h-96">
                    <!-- Users table -->
                    <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                        <!-- Table header with column titles -->
                        <thead class="text-xs text-gray-100 uppercase bg-gray-700 sticky top-0 z-10">
                        <tr>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Surname</th>
                            <th scope="col" class="px-6 py-3">City</th>
                            <th scope="col" class="px-6 py-3">Address</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Phone</th>
                            <th scope="col" class="px-6 py-3">Birthday</th>
                            <th scope="col" class="px-6 py-3">Joined At</th>
                            <th scope="col" class="px-6 py-3 text-center">Action</th>
                        </tr>
                        </thead>
                        <!-- Table body rows -->
                        <tbody>
                        <!-- Empty state message when no users exist -->
                        <tr v-if="Users.length === 0">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No Users Yet
                            </td>
                        </tr>
                        <!-- Loop through users (excluding the currently logged-in user) and display each row -->
                        <tr v-for="user in Users.filter(u => u.id !== $page.props.auth.user.id)" :key="user.id" class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <!-- User first name -->
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ user.name }}</td>
                            <!-- User last name -->
                            <td class="px-6 py-4">{{ user.lastname }}</td>
                            <!-- User city -->
                            <td class="px-6 py-4">{{ user.city.name }}</td>
                            <!-- User address -->
                            <td class="px-6 py-4">{{ user.address }}</td>
                            <!-- User email -->
                            <td class="px-6 py-4">{{ user.email }}</td>
                            <!-- User phone number -->
                            <td class="px-6 py-4">{{ user.phone }}</td>
                            <!-- User birthday (formatted) -->
                            <td class="px-6 py-4">{{ formatDate(user.birthday) }}</td>
                            <!-- Account creation date (formatted) -->
                            <td class="px-6 py-4">{{ formatDate(user.created_at) }}</td>
                            <!-- Delete action button -->
                            <td class="px-6 py-4 text-center">
                                    <button
                                        type="button"
                                        @click="openDeleteModal(user.id)"
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                        Delete
                                    </button>

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
        message="Are you sure you want to delete this user?"
        @close="showDeleteModal = false"
        @confirm="confirmDelete"
    />
</template>
