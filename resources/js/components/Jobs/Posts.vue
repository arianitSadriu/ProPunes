<script setup>
/**
 * Posts Component
 * Displays a list of job postings with details including title, salary, location, and applicant count.
 * Provides functionality to save/unsave posts for authenticated employees.
 * Shows different views based on user role (employee vs employer/owner).
 */
import {formatDistanceToNow} from "date-fns";
import {Link, useForm, usePage} from "@inertiajs/vue3";
import InputError from "@/components/InputError.vue";
import JobsTextInput from "@/components/Jobs/JobsTextInput.vue";


/**
 * Component Props
 * @property {Object} posts - Paginated posts object containing array of post data
 * @property {Array} savedPost - Array of saved post IDs for the current user (default: [])
 * @property {Boolean} searchQueryPerformed - Flag indicating if a search was performed (default: false)
 */
const props = defineProps({
    posts: {
        type: Object,
    },
    savedPost: {
        type: Array,
        default: () => [],
    },
    searchQueryPerformed:{
        type: Boolean,
        default: false,
    },
})
console.log('array: ', props.posts)

/**
 * Truncate text to a specified length and add ellipsis
 * Prevents overflow of long titles and descriptions
 * 
 * @param {string} text - The text to truncate
 * @param {number} length - Maximum length before truncation
 * @return {string} Truncated text with ellipsis or original text if shorter than length
 */
function truncate(text, length) {
    if (!text) return '';
    return text.length > length ? text.substring(0, length) + '...' : text;
}


/**
 * Format a date to relative time format (e.g., "2 hours ago")
 * Uses date-fns library for formatting
 * 
 * @param {string|Date} date - The date to format
 * @return {string} Formatted relative time string
 */
function formatTime(date) {
    return formatDistanceToNow(new Date(date), {addSuffix: true});
}

/**
 * Inertia.js form instance for managing save/unsave post operations
 * Contains user_id, post_id, and saved_post_id for tracking saved posts
 */
const form = useForm({
    user_id: usePage().props.auth.user.id,
    post_id: null,
    saved_post_id: null,
})

/**
 * Submit a save request for a job post
 * Sends POST request to 'jobs.storeSaved' route to save the post
 */
const submitSaved = () => {
    form.post(route('jobs.storeSaved'))
};

/**
 * Delete a saved post from the user's saved list
 * Sends DELETE request to 'jobs.deleteSaved' route
 * 
 * @param {number} savedPostId - The ID of the saved post record to delete
 */
const deleteSaved = (savedPostId) => {
    form.saved_post_id = savedPostId;
    form.delete(route('jobs.deleteSaved', {id: savedPostId}));
}

</script>

<template>

    <!-- Loop through each post in the paginated posts data -->
    <div v-for="post in posts.data" id="posts" class="bg-white rounded-lg mb-4 hover:shadow-xl transition w-64 md:w-full mx-auto">
        <!-- Link to job post details page -->
        <Link
            :href="route('jobs.show', { id: post.id })"
        >
        <!-- Post header section with company image and main details -->
        <section class="p-6 flex justify-between">
            <div class="grid md:flex justify-start items-center md:space-x-4 w-full">
                <!-- Company logo image -->
                <img :src="'storage/'+ post.company.image" class="w-16 h-16 rounded border border-gray-200" alt="">

                    <!-- Post information container -->
                    <div class="space-y-2">
                    <!-- Job title (truncated) -->
                    <span class="font-bold text-lg md:text-xl break-words">
                        {{ truncate(post.tittle, 20) }}
                    </span>
                        <!-- Job metadata (type, salary, applicants, location) -->
                        <section class="md:space-x-8 text-xs md:text-base grid grid-cols-2 gap-x-8 md:flex whitespace-nowrap items-center">
                        <!-- Employment type (Full-Time, Part-Time, Remote) -->
                        <span class="flex items-center gap-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" width="20px" height="20px"
                                 viewBox="0 0 24 24"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g
                                id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g
                                id="SVGRepo_iconCarrier"><path fill-opacity=".9"
                                                               d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path></g></svg>
                            {{ post.type }}
                        </span>
                            <!-- Salary range -->
                            <span class="flex items-center gap-x-1">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g
                                id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g
                                id="SVGRepo_iconCarrier"> <path
                                d="M16 8.94444C15.1834 7.76165 13.9037 7 12.4653 7C9.99917 7 8 9.23858 8 12C8 14.7614 9.99917 17 12.4653 17C13.9037 17 15.1834 16.2384 16 15.0556M7 10.5H11M7 13.5H11M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                            ${{ post.salary }}
                        </span>
                            <!-- Number of available positions/applicants -->
                            <span class="flex items-center gap-x-1">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g
                                id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g
                                id="SVGRepo_iconCarrier"> <path
                                d="M20 18L14 18M17 15V21M4 21C4 17.134 7.13401 14 11 14C11.695 14 12.3663 14.1013 13 14.2899M15 7C15 9.20914 13.2091 11 11 11C8.79086 11 7 9.20914 7 7C7 4.79086 8.79086 3 11 3C13.2091 3 15 4.79086 15 7Z"
                                stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                            {{ post.nrWorkers }} Applicants
                        </span>
                            <!-- Job location -->
                            <span class="flex items-center gap-x-1">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g
                                id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g
                                id="SVGRepo_iconCarrier"> <path
                                d="M12 21C15.5 17.4 19 14.1764 19 10.2C19 6.22355 15.866 3 12 3C8.13401 3 5 6.22355 5 10.2C5 14.1764 8.5 17.4 12 21Z"
                                stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path
                                d="M12 12C13.1046 12 14 11.1046 14 10C14 8.89543 13.1046 8 12 8C10.8954 8 10 8.89543 10 10C10 11.1046 10.8954 12 12 12Z"
                                stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                            {{ post.location.name }}
                        </span>
                        </section>
                    </div>

            </div>
            <!-- "Your Post" indicator - shown if user is the post owner -->
            <div v-if="post.user_id === $page.props.auth.user.id" class="w-1/4 text-end">
                <h2 class="font-bold text-md md:text-lg text-indigo-950">Your Post</h2>
            </div>
            <!-- Save/Unsave buttons - only shown for employee users -->
            <div v-if="$page.props.auth.user.role === 'employee'" class="flex justify-end w-1/2 md:items-center items-start">
                <!-- Save post button - shown if post is not saved -->
                <button v-if="!savedPost.some(saved => saved.post_id === post.id)" class="hover:scale-105 transition"
                        @click.prevent="() => { form.post_id = post.id; submitSaved(); }" type="submit">
                    <svg width="40px" height="40px" class="border-gray-500 border rounded-lg"
                         viewBox="-4.8 -4.8 33.60 33.60" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0">
                            <rect x="-4.8" y="-4.8" width="33.60" height="33.60" rx="3.36" fill=""
                                  strokewidth="0"></rect>
                        </g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M19 19.2674V7.84496C19 5.64147 17.4253 3.74489 15.2391 3.31522C13.1006 2.89493 10.8994 2.89493 8.76089 3.31522C6.57467 3.74489 5 5.64147 5 7.84496V19.2674C5 20.6038 6.46752 21.4355 7.63416 20.7604L10.8211 18.9159C11.5492 18.4945 12.4508 18.4945 13.1789 18.9159L16.3658 20.7604C17.5325 21.4355 19 20.6038 19 19.2674Z"
                                stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </g>
                    </svg>
                </button>
                <!-- Unsave post button - shown if post is already saved (filled bookmark icon) -->
                <button v-if="savedPost.some(saved => saved.post_id === post.id)"
                        @click.prevent="() => {
                        const savedPostEntry = savedPost.find(saved => saved.post_id === post.id);
                        deleteSaved(savedPostEntry.saved_post_id);
                    }"
                        class="hover:scale-105 transition"
                        type="submit">
                    <svg width="40px" height="40px" class="border-gray-500 border rounded-lg"
                         viewBox="-4.8 -4.8 33.60 33.60" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0">
                            <rect x="-4.8" y="-4.8" width="33.60" height="33.60" rx="3.36" fill=""
                                  strokewidth="0"></rect>
                        </g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#FFB81C"
                           stroke-width="0.9600000000000002">
                            <path
                                d="M15.0309 3.30271C13.0299 2.8991 10.9701 2.8991 8.96913 3.30271C6.66186 3.76809 5 5.82231 5 8.20894V18.6292C5 20.4579 6.9567 21.596 8.51221 20.6721L11.3451 18.9895C11.7496 18.7492 12.2504 18.7492 12.6549 18.9895L15.4878 20.6721C17.0433 21.596 19 20.4579 19 18.6292V8.20894C19 5.82231 17.3381 3.76809 15.0309 3.30271Z"
                                fill="#FFB81C"></path>
                        </g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M15.0309 3.30271C13.0299 2.8991 10.9701 2.8991 8.96913 3.30271C6.66186 3.76809 5 5.82231 5 8.20894V18.6292C5 20.4579 6.9567 21.596 8.51221 20.6721L11.3451 18.9895C11.7496 18.7492 12.2504 18.7492 12.6549 18.9895L15.4878 20.6721C17.0433 21.596 19 20.4579 19 18.6292V8.20894C19 5.82231 17.3381 3.76809 15.0309 3.30271Z"
                                fill="#FFB81C"></path>
                        </g>
                    </svg>
                </button>
            </div>
            <div>
            </div>
        </section>
        <!-- Post description section -->
        <section class="px-6 pb-4 h-20 text-sm md:text-lg">
            <p>
                {{ truncate(post.description, 80) }}
            </p>
        </section>
        <!-- Post footer with category badge and creation date -->
        <section class="w-full flex items-center justify-between text-gray-500 px-6 pb-4 mt-2">
            <!-- Job category badge -->
            <span
                class="px-4 md:px-8 bg-emerald-300 bg-opacity-50 py-0.5 rounded-lg text-emerald-900 text-center text-xs md:text-sm flex items-center md:text-md">
                {{post.category.name }}
            </span>
            <!-- Relative time since post creation -->
            <span class="text-xs md:text-sm ">{{ formatTime(post.created_at) }}</span>
        </section>
        </Link>
    </div>

    <!-- Empty state message when no posts exist -->
    <div v-if="posts.length === 0" class="bg-white rounded-lg mb-4 h-20 flex justify-center items-center ">
        <!-- Show different message based on whether a search was performed -->
        <span v-if="searchQueryPerformed">NO RESULTS</span>
        <span v-else>NO POSTS YET</span>
    </div>

</template>
