<script setup>
/**
 * Dashboard Page Component
 * 
 * This component serves as the main admin dashboard for the ProPunes job board platform.
 * It provides comprehensive statistics and visualizations including:
 * - Doughnut chart showing platform statistics (employers, employees, posts, applications)
 * - Interactive Leaflet map displaying user distribution by city with custom markers
 * - Bar chart visualizing application acceptance/rejection rates
 * - Tabbed interface for viewing users and posts tables
 * 
 * The dashboard retrieves data from AdminController and displays real-time platform metrics
 * to help administrators monitor platform health and activity trends across all features.
 */

import {onMounted, ref} from "vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import DashboardInfoCard from "@/components/Dashboard/DashboardInfoCard.vue";
import Chart from "chart.js/auto";
import UsersTable from "@/components/Dashboard/UsersTable.vue";
import PostsTable from "@/components/Dashboard/PostsTable.vue";
import "leaflet/dist/leaflet.css";
import L from "leaflet";

/**
 * Dashboard Component Props
 * 
 * @property {Number} EmployeeCount - Total number of registered employees (job seekers) on the platform
 * @property {Number} EmployerCount - Total number of registered employers on the platform
 * @property {Number} PostCount - Total number of active job listings available
 * @property {Number} ApplicationCount - Total number of submitted job applications
 * @property {Array} Users - Array of user objects for map marker placement and users table display
 * @property {Array} Posts - Array of post objects containing location data for map visualization
 * @property {Number} accepted - Count of accepted applications for bar chart visualization
 * @property {Number} rejected - Count of rejected applications for bar chart visualization
 */
const props = defineProps({
    EmployeeCount: Number,
    EmployerCount: Number,
    PostCount: Number,
    ApplicationCount: Number,
    Users: Array,
    Posts:Array,
    accepted: Number,
    rejected: Number,
});

/**
 * @ref {String} currentTab - Reactive reference tracking active tab ('users' or 'posts')
 * Controls which table is displayed in the dashboard tabbed section at the bottom
 */
const currentTab = ref('users');

/**
 * DOM Element References for Chart.js Canvases and Leaflet Map Container
 * 
 * @ref {HTMLCanvasElement} chartRef - Canvas element for the doughnut chart (platform statistics)
 * @ref {HTMLCanvasElement} chartRef2 - Canvas element for the bar chart (application acceptance/rejection)
 * @ref {HTMLElement} mapRef - Div element container for the Leaflet interactive map
 * @ref {LayerGroup} markersLayer - Leaflet layer group containing all map markers for user locations
 */
const chartRef = ref(null);
const chartRef2 = ref(null);
const mapRef = ref(null);
const markersLayer = ref(null);

/**
 * onMounted - Lifecycle Hook
 * 
 * Executed when the component is mounted to the DOM. This hook initializes all dashboard visualizations:
 * 
 * 1. DOUGHNUT CHART INITIALIZATION (Statistics Overview)
 *    - Creates a Chart.js doughnut chart displaying four key platform metrics
 *    - Shows distribution of Employers (green), Employees (blue), Posts (orange), Applications (pink)
 *    - Uses color-coded segments for easy visual distinction
 *    - Positioned at bottom for legend display with bold, larger font labels
 *    - Hover effect lifts segments 10px for interactive feedback
 *    - Maintains responsive layout while preserving aspect ratio control
 * 
 * 2. LEAFLET MAP INITIALIZATION (User Geographic Distribution)
 *    - Creates an interactive map centered on Albania (coordinates: 42.6026, 20.9030)
 *    - Fixed zoom level at 8 for country-wide view (minZoom and maxZoom both 8)
 *    - Uses OpenStreetMap tiles for basemap
 *    - Groups users by their city location
 *    - Creates custom icon markers at each city with professional.png image (26x32 pixels)
 *    - Each marker displays city name and user count in a popup on click
 *    - All markers added to markersLayer LayerGroup for easy management
 * 
 * 3. BAR CHART INITIALIZATION (Application Acceptance/Rejection Rate)
 *    - Creates a Chart.js bar chart comparing accepted vs rejected applications
 *    - Green bars (#4CAF50) represent accepted applications
 *    - Blue bars (#2196F3) represent rejected applications
 *    - Bar thickness: 150px with specific padding and percentage settings
 *    - Y-axis: Begins at zero with step size of 1, white labels, gray grid lines
 *    - X-axis: White labels with grid lines, reduced bar width for clarity
 *    - Tooltips: Dark background with white text for visibility
 *    - Legend: Disabled (only needed for single dataset)
 * 
 * All visualizations use responsive configuration to adapt to different screen sizes
 * while maintaining proper proportions and readability.
 */
onMounted(() => {
    /**
     * DOUGHNUT CHART - Platform Statistics
     * 
     * Visualizes four key metrics about the platform:
     * - Green (#4CAF50): Employer accounts registered
     * - Blue (#2196F3): Employee/job seeker accounts
     * - Orange (#FF9800): Active job postings available
     * - Pink (#E91E63): Total job applications submitted
     * 
     * Hover effect: Segments lift 10px when hovered for interactive feedback
     * Legend: Displayed at bottom with bold font, custom spacing and sizing
     * Tooltip: Dark background (80% opacity) with 14px font for readability
     */
    if (chartRef.value) {
        new Chart(chartRef.value, {
            type: "doughnut",
            data: {
                labels: ["Employers", "Employees", "Posts", "Applications"],
                datasets: [{
                    label: "Statistics",
                    data: [props.EmployerCount, props.EmployeeCount, props.PostCount, props.ApplicationCount],
                    backgroundColor: ["#4CAF50", "#2196F3", "#FF9800", "#E91E63"],
                    borderColor: "#fff",
                    borderWidth: 2,
                    hoverOffset: 10, // Adds a hover effect
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Allows resizing
                layout: {
                    padding: 20, // Adds padding around the chart
                },
                plugins: {
                    legend: {
                        display: true,
                        position: "bottom", // Moves labels to the side
                        labels: {
                            align: "start",
                            boxWidth: 20, // Makes color boxes smaller
                            padding: 15, // Adds spacing between labels
                            font: {
                                size: 14, // Increases label font size
                                weight: "bold",
                            },
                        },
                    },
                    tooltip: {
                        enabled: true, // Enables tooltips
                        backgroundColor: "rgba(0, 0, 0, 0.8)", // Darker tooltip
                        bodyFont: {
                            size: 14,
                        },
                    },
                },
            },
        });
    }

    /**
     * LEAFLET MAP - Geographic User Distribution
     * 
     * Displays an interactive map showing the distribution of users across different cities
     * - Base map: OpenStreetMap tiles with standard attribution
     * - Center point: Albania (42.6026, 20.9030) - center of job market
     * - Zoom level: Fixed at 8 (no zoom in/out allowed for consistent view)
     * - Layer management: Uses L.layerGroup() to group all city markers
     * 
     * User Grouping Process:
     * 1. Iterate through all users and group them by city name
     * 2. Extract unique cities with coordinates (latitude/longitude)
     * 3. Count users per city for display in popup information
     * 4. Create custom icon markers at each city's coordinates
     * 
     * Custom Icon Properties:
     * - Icon Image: professional.png from storage
     * - Size: 26x32 pixels (width x height)
     * - Anchor: (16, 32) - points to bottom center of icon
     * - Popup Anchor: (0, -32) - positions popup above icon
     * 
     * Marker Information: Shows city name and user count in popup
     */
    if (mapRef.value) {
        const map = L.map(mapRef.value, {
            minZoom: 8,
            maxZoom: 8
        }).setView([42.6026, 20.9030], 8);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        markersLayer.value = L.layerGroup().addTo(map);
        const cityData = {};
        const customIcon = L.icon({
            iconUrl: '/storage/images/professional.png',
            iconSize: [26, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        /**
         * USER LOCATION AGGREGATION
         * 
         * Process all users to group them by city:
         * - Check if user has city data with latitude/longitude coordinates
         * - Create cityData object with name, count, and coordinates
         * - Increment count for each user in that city
         * - Skip users without city information
         */
        props.Users.forEach(user => {
            if (user.city && user.city.latitude && user.city.longitude) {
                const cityKey = user.city.name;
                if (!cityData[cityKey]) {
                    cityData[cityKey] = {
                        name: user.city.name,
                        count: 0,
                        latitude: user.city.latitude,
                        longitude: user.city.longitude
                    };
                }
                cityData[cityKey].count++;
            }
        });

        /**
         * CREATE AND DISPLAY CITY MARKERS
         * 
         * For each city in cityData:
         * - Create a marker at city coordinates using the custom icon
         * - Add marker to the markersLayer group
         * - Bind popup showing city name and total users count
         * - Popup displays: "<b>CityName</b><br>Users: count"
         */
        Object.values(cityData).forEach(city => {
            L.marker([city.latitude, city.longitude], {icon: customIcon})
                .addTo(markersLayer.value)
                .bindPopup(`<b>${city.name}</b><br>Users: ${city.count}`);
        });

    }

    /**
     * BAR CHART - Application Acceptance/Rejection Statistics
     * 
     * Horizontal bar chart comparing application outcomes
     * - Green (#4CAF50): Accepted applications with darker border (#388E3C)
     * - Blue (#2196F3): Rejected applications with darker border (#2196F5)
     * - Bar height: 150px for prominent display
     * - Bar spacing: 10% of chart width, 60% category percentage
     * 
     * Axis Configuration:
     * - Y-axis: Begins at zero with white text, step size 1, gray grid lines
     * - X-axis: White labels, gray grid lines at 1px thickness
     * - Both axes display grid lines for easier data reading
     * 
     * Interactive Features:
     * - Tooltips enabled with dark background and white text
     * - Legend disabled (not necessary with only 2 categories)
     * - Maintains responsive layout with true aspect ratio
     * 
     * Use Case: Helps administrators quickly see approval trends and application quality
     */
    if (chartRef2.value) {
        new Chart(chartRef2.value, {
            type: "bar",
            data: {
                labels: ['Accepted', 'Rejected'],
                datasets: [{
                    label: "Applications",
                    data: [props.accepted, props.rejected],
                    backgroundColor: ["#4CAF50", "#2196F3"],
                    borderColor: ['#388E3C', '#2196F5'],
                    borderWidth: 1,
                    barThickness: 150,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true, // Allows resizing
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: "#ffffff",  // White color for y-axis labels
                        },
                        grid: {
                            color: "#6b7280", // Grid line color for y-axis (change to desired color)
                            lineWidth: 1,     // Thickness of the grid lines
                        },
                    },
                    x: {
                        barPercentage: 0.1,  // Reduces the width of each bar
                        categoryPercentage: 0.6,  // Adjusts the space between bars
                        ticks: {
                            color: "#ffffff",  // White color for x-axis labels
                        },
                        grid: {
                            color: "#6b7280", // Grid line color for x-axis (change to desired color)
                            lineWidth: 1,     // Thickness of the grid lines
                        },
                    },
                },
                plugins: {
                    legend: {
                        display: false,
                        },
                    },
                    tooltip: {
                        enabled: true, // Enables tooltips
                        backgroundColor: "rgba(0, 0, 0, 0.8)", // Darker tooltip
                        bodyFont: {
                            size: 14,
                        },
                    },
            },
        });
    }
});

</script>

<template>
    <!-- Document Head: Sets page title for browser tab -->
    <Head title="Dashboard"/>
    
    <!-- Main Layout Container with authenticated layout wrapper -->
    <AuthenticatedLayout>
        <!-- Header Section: Page Title -->
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
        </template>

        <!-- STATISTICS CARDS SECTION
             Four responsive info cards displaying key platform metrics
             Layout: Stacked on mobile (space-y-4), 4-column grid on desktop (md:grid-cols-4)
             Each card shows an icon, label, and dynamic value from props
        -->
        <section class="space-y-4 md:space-y-0 md:grid md:grid-cols-4 gap-6 py-8 px-6">
            <!-- EMPLOYERS CARD
                 Displays total number of registered employer accounts
                 Icon: User silhouette (person icon)
                 Data binding: {{ EmployerCount }}
            -->
            <DashboardInfoCard>
                <template #Information> Employers</template>
                <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="white" class="h-5 w-5 md:h-10 md:w-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </template>
                <template #DbInfo> {{ EmployerCount }}</template>
            </DashboardInfoCard>

            <!-- EMPLOYEES CARD
                 Displays total number of registered employee/job seeker accounts
                 Icon: User silhouette (person icon)
                 Data binding: {{ EmployeeCount }}
            -->
            <DashboardInfoCard>
                <template #Information> Employees</template>
                <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="white" class="h-5 w-5 md:h-10 md:w-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                </template>
                <template #DbInfo> {{ EmployeeCount }}</template>
            </DashboardInfoCard>

            <!-- POSTS CARD
                 Displays total number of active job postings
                 Icon: Document/sheet icon (representing job listings)
                 Data binding: {{ PostCount }}
            -->
            <DashboardInfoCard>
                <template #Information> Posts</template>
                <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="white" class="h-5 w-5 md:h-10 md:w-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                    </svg>
                </template>
                <template #DbInfo> {{ PostCount }}</template>
            </DashboardInfoCard>

            <!-- APPLICATIONS CARD
                 Displays total number of submitted job applications
                 Icon: Target/application icon (representing application submissions)
                 Data binding: {{ ApplicationCount }}
            -->
            <DashboardInfoCard>
                <template #Information> Applications</template>
                <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="white" class="h-5 w-5 md:h-10 md:w-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672Zm-7.518-.267A8.25 8.25 0 1 1 20.25 10.5M8.288 14.212A5.25 5.25 0 1 1 17.25 10.5"/>
                    </svg>
                </template>
                <template #DbInfo> {{ ApplicationCount }}</template>
            </DashboardInfoCard>
        </section>

        <!-- CHARTS AND INFORMATION SECTION
             Two-column responsive layout with visualization and explanatory text
             Layout: Stacked on mobile, side-by-side on tablet/desktop (md:grid-cols-2)
             Contains: Doughnut chart and dashboard overview explanation
        -->

        <section class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <!-- DOUGHNUT CHART CONTAINER
                 Displays the statistics doughnut chart with gradient background
                 Styling: 
                 - Gradient: gray-100 to gray-300 (light), gray-700 to gray-900 (dark mode)
                 - Border: gray-300 (light), gray-700 (dark)
                 - Shadow: Large with hover effect for depth
                 - Responsive height: 384px (h-96)
                 - Canvas ref="chartRef": Contains the Chart.js doughnut visualization
            -->
            <div class="bg-gradient-to-br from-gray-100 to-gray-300 border border-gray-300
                rounded-2xl shadow-lg p-6 flex justify-center items-center dark:bg-gray-800 dark:border-gray-700
                dark:from-gray-700 dark:to-gray-900 transition-transform transform hover:shadow-xl ">
                <div class="w-full h-96 md:w-full md:h-96">
                    <span class="absolute text-gray-50 text-2xl font-bold">Chart</span>
                    <canvas ref="chartRef" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- DASHBOARD OVERVIEW INFORMATION SECTION
                 Explanatory text and bullet points describing the dashboard
                 Styling: White background with borders, darker in dark mode
                 Content includes:
                 - Title: "Dashboard Overview"
                 - Yellow divider line (border-yellow-500)
                 - Description paragraph explaining chart purpose
                 - Bullet list of the four metrics (Employers, Employees, Posts, Applications)
                 - Additional insights paragraph about platform activity trends
            -->
            <div class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700
                rounded-2xl shadow-lg p-8 transition-transform transform  hover:shadow-xl">
                <!-- Dashboard Title -->
                <h3 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white tracking-wide">
                    Dashboard Overview
                </h3>

                <!-- Yellow Decorative Divider -->
                <div class="w-16 border-t-4 border-yellow-500 my-4"></div>

                <!-- Introductory Description -->
                <p class="mt-4 text-sm md:text-base lg:text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                    This interactive chart provides a **visual breakdown** of key platform statistics, offering
                    insights into overall user engagement and activity.
                </p>

                <!-- METRICS EXPLANATION LIST
                     Describes each of the four metrics shown in the doughnut chart
                     - Employers: Companies that post job listings
                     - Employees: Individual job seekers/applicants
                     - Posts: Active job listings available for applications
                     - Applications: Submitted applications from employees to job postings
                -->
                <ul class="mt-6 space-y-3 text-gray-800 dark:text-gray-300">
                    <!-- Employers Metric -->
                    <li class="flex items-center">
                        <span class="text-sm md:text-base lg:text-lg"> <strong>Employers</strong> - Registered on the platform. </span>
                    </li>
                    <!-- Employees Metric -->
                    <li class="flex items-center">
                        <span class="text-sm md:text-base lg:text-lg"> <strong>Employees</strong> - Active job seekers. </span>
                    </li>
                    <!-- Posts Metric -->
                    <li class="flex items-center">
                        <span class="text-sm md:text-base lg:text-lg"> <strong>Posts</strong> - Total job listings currently available. </span>
                    </li>
                    <!-- Applications Metric -->
                    <li class="flex items-center">
                        <span class="text-sm md:text-base lg:text-lg"> <strong>Applications</strong> - Submitted job applications. </span>
                    </li>
                </ul>

                <!-- Final Insights Description -->
                <p class="mt-6 text-sm md:text-base lg:text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                    These insights help in understanding the **platform's activity trends** over time, ensuring better
                    decision-making for both employers and employees.
                </p>
            </div>
        </section>

        <!-- MAP AND BAR CHART SECTION
             Two-column responsive layout displaying:
             1. Leaflet map showing geographic distribution of users
             2. Bar chart showing application acceptance/rejection statistics
             Layout: Stacked on mobile, side-by-side on tablet/desktop (md:flex with md:w-1/2)
        -->
        <div class="md:flex mx-6 md:space-x-6">
            <!-- USER LOCATION MAP CONTAINER
                 Displays the Leaflet interactive map with user markers
                 Styling:
                 - Background: Dark gray (gray-800)
                 - Border: Light gray with shadow
                 - Height: 384px (h-96), rounded corners (rounded-xl)
                 - Responsive: Full width on mobile, 50% width on desktop (md:w-1/2)
                 - div ref="mapRef": Leaflet map initialization target
            -->
            <div class="bg-gray-800 border border-gray-300 rounded-2xl shadow-lg p-6 w-full md:w-1/2 items-center space-y-4">
                <span class="text-gray-50 text-2xl font-bold">Map of users</span>
                <div ref="mapRef" class="w-full h-96 rounded-xl z-40"></div>
            </div>

            <!-- APPLICATION ACCEPTANCE/REJECTION CHART CONTAINER
                 Displays the bar chart comparing accepted vs rejected applications
                 Styling:
                 - Background: Dark gray (gray-800)
                 - Border: Light gray with shadow
                 - Minimum height: 384px (min-h-96)
                 - Responsive: Full width on mobile, 50% width on desktop (md:w-1/2)
                 - id="postsPerDay": Chart container identifier
                 - canvas ref="chartRef2": Contains the Chart.js bar chart visualization
            -->
            <div id="postsPerDay" class="bg-gray-800 border border-gray-300 rounded-2xl min-h-96 flex flex-col justify-between shadow-lg p-6 w-full md:w-1/2 space-y-4">
                <span class="text-gray-50 text-xl lg:text-2xl font-bold">Accepted/Rejected Applications</span>
                <canvas ref="chartRef2" class=" w-full h-96"></canvas>
            </div>
        </div>

        <!-- USERS AND POSTS TABLE TABS SECTION
             Tabbed interface for switching between users table and posts table views
             Styling:
             - Dark background (gray-800) with border-top
             - Tab buttons: Blue background when active, gray hover state when inactive
             - Smooth transitions with duration-300
             - Conditional rendering: Shows UsersTable when currentTab === 'users', PostsTable when currentTab === 'posts'
        -->
        <section class="m-6 bg-gray-800 shadow-md rounded-lg text-white space-x-4 pt-2 border-t border-gray-500">
            <!-- USERS TAB BUTTON
                 When clicked: Sets currentTab to 'users' to display the users table
                 Styling: 
                 - Active (currentTab === 'users'): Blue background with shadow
                 - Inactive: Hover state with gray background
                 - Responsive padding and text sizing
            -->
            <button
                class="mx-4 my-2 text-xl font-semibold px-2 py-1 transition-all duration-300 rounded-md"
                @click="currentTab = 'users'"
                :class="{
            'bg-blue-500 text-white shadow-md ': currentTab === 'users',
            'hover:bg-gray-400  ': currentTab !== 'users'
        }">
                Users
            </button>

            <!-- POSTS TAB BUTTON
                 When clicked: Sets currentTab to 'posts' to display the posts table
                 Styling:
                 - Active (currentTab === 'posts'): Blue background with shadow
                 - Inactive: Hover state with gray background
                 - Same responsive styling as users button
            -->
            <button
                class="mx-4 text-xl font-semibold px-2 py-1 transition-all duration-300 rounded-md"
                @click="currentTab = 'posts'"
                :class="{
            'bg-blue-500 text-white shadow-md ': currentTab === 'posts',
            'hover:bg-gray-400': currentTab !== 'posts'
        }">
                Posts
            </button>

            <!-- USERS TABLE COMPONENT
                 Displays when currentTab === 'users'
                 Props:
                 - :Users="Users" - Array of user objects from props
                 - v-if directive: Conditionally renders only when users tab is active
                 Shows: Table with user information, delete buttons, and formatted dates
            -->
            <UsersTable :Users="Users" v-if="currentTab === 'users'"/>

            <!-- POSTS TABLE COMPONENT
                 Displays when currentTab === 'posts'
                 Props:
                 - :Posts="Posts" - Array of post objects from props
                 - v-if directive: Conditionally renders only when posts tab is active
                 Shows: Table with job posting information, delete buttons, and details links
            -->
            <PostsTable :Posts="Posts" v-if="currentTab === 'posts'"/>
        </section>

    </AuthenticatedLayout>
</template>
