<script setup>
/**
 * Filter Component
 * Provides filtering options for job listings including location, category, job type, and salary range.
 * Uses Inertia.js for form handling and reactive state management.
 */
import {Head, Link, useForm} from "@inertiajs/vue3";
import {ref, reactive, watch} from "vue";

/**
 * Component Props
 * @property {Array} cities - Array of available cities for location filtering
 * @property {Array} categories - Array of available job categories for filtering
 */
defineProps({
    cities: {
        type:Array,
    },
    categories:{
        type:Array,
    },
})

/**
 * Reactive filters object that holds the current filter state
 * - location: Selected city ID for location filtering
 * - category: Selected category ID for category filtering
 * - type: Array of selected job types (Full-Time, Part-Time, Remote)
 * - salary: Array of selected salary ranges
 */
const filters = reactive({
    location: "",
    category: "",
    type: [],
    salary: [],
});

/**
 * Inertia.js form instance for managing filter form state
 * Initialized with current filter values
 */
const form = useForm({
    location: filters.location,
    category: filters.category,
    type: filters.type,
    salary: filters.salary,
});

/**
 * Apply the current filters to the job listings
 * Sends a GET request to jobs.index route with filter parameters
 * Preserves component state to maintain pagination and other page state
 */
const applyFilters = () => {
    form.get(route("jobs.index"), { preserveState: true });
};

/**
 * Clear all filters and reset the job listing to default view
 * Resets all filter fields to empty values
 * Does not preserve state to ensure fresh page load
 */
const clearFilters = () => {
    form.city = "";
    form.category = "";
    form.type = [];
    form.salary = [];
    form.get(route("jobs.index"), { preserveState: false });
};

</script>

<template>
    <!-- Filter sidebar container -->
    <section id="left" class="w-4/5 mx-auto lg:w-1/3 h-full bg-white rounded-lg border border-gray-500 lg:border-0">
        <!-- Filter header -->
        <div class="flex justify-between py-3 px-6">
            <span class="font-bold text-xl">Filter</span>
        </div>

        <!-- Location filter dropdown -->
        <div class="px-6 py-2">
            <span class="font-bold">Location</span>
            <select
                v-model="form.location"
                class="rounded-md border-gray-300 p-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1 block w-full bg-transparent">
                <option disabled selected value="">City</option>
                <!-- Loop through available cities and create option for each -->
                <option v-for="city in cities" :key="city.id" :value="city.id">{{city.name}}</option>
            </select>
        </div>

        <!-- Category filter dropdown -->
        <div class="px-6 py-2">
            <span class="font-bold">Category</span>
            <select
                v-model="form.category"
                class="rounded-md border-gray-300 p-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1 block w-full bg-transparent">
                <option disabled selected value="">Category</option>
                <!-- Loop through available categories and create option for each -->
                <option v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option>
            </select>
        </div>

        <!-- Job type filter checkboxes -->
        <div class="px-6 py-2">
            <span class="font-bold">Job Type</span>
            <!-- Multiple selection checkboxes for job types -->
            <ul>
                <li class="space-x-2 flex items-center" v-for="type in ['Full-Time', 'Part-Time', 'Remote']" :key="type">
                    <input type="checkbox" :value="type" v-model="form.type" />
                    <label>{{ type }}</label>
                </li>
            </ul>
        </div>

        <!-- Salary range filter checkboxes -->
        <div class="px-6 py-2">
            <span class="font-bold">Expected Salary</span>
            <!-- Multiple selection checkboxes for salary ranges -->
            <ul>
                <li class="space-x-2 flex items-center" v-for="range in ['$350-$700', '$700-$1000', '+$1000']" :key="range">
                    <input type="checkbox" :value="range" v-model="form.salary" />
                    <label>{{ range }}</label>
                </li>
            </ul>
        </div>

        <!-- Action buttons -->
        <div class="px-6 py-4 grid w-3/4 gap-2 mx-auto">
            <!-- Apply filters button - sends filter request -->
            <button
                @click="applyFilters"
                class="bg-blue-500 text-white text-sm px-4 py-2 rounded-md shadow hover:bg-blue-600"
            >
                Apply Filters
            </button>
            <!-- Clear filters button - resets all filters to default -->
            <button
                @click="clearFilters"
                class="bg-red-600 text-white text-sm px-4 py-2 rounded-md shadow hover:bg-red-800"
            >
                Remove Filters
            </button>
        </div>
    </section>
</template>
