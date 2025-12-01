README.md — ProPunes
1. Project Overview

ProPunes is a full-stack job platform that connects employers with job seekers.
Employees can browse, filter, save, and apply to job postings (after uploading a CV), while employers can create and manage job posts (after completing their company profile). An Admin Dashboard provides insights about users, posts, and applications using charts and an interactive Leaflet map.

This README acts as a user manual, describing how to install, configure, and use the application.

2. Features
Employee Features

    Register/login

    Upload a profile picture

    Upload a CV (required before applying)

    Browse, search, and filter job posts

    Save job posts

    Apply to jobs

Employer Features

    Complete company profile

    Upload company image

    Create, edit, and delete job posts

    View applications

Admin Features

    Dashboard with system statistics

    View/delete users

    View/delete job posts

    Leaflet map showing post locations

    Chart-based insights about platform activity

3. Technology Stack

    Laravel (Backend — MVC architecture)

    Vue.js (Frontend)

    Tailwind CSS (Styling)

    MySQL (Database)

    Leaflet.js (Map)

    Chart.js (Data visualizations)

    MVC Architecture

    ProPunes follows Laravel’s Model–View–Controller pattern:

    Models handle data access and relationships

    Controllers process logic and return API responses

    Views are Vue components rendered dynamically

4. Prerequisites

    Before installing ProPunes, ensure you have:

    PHP 8+

    Composer

    Node.js + NPM

    MySQL

    Laravel CLI

    XAMPP/Laragon (or any local server environment)

5. Installation & Setup
Step 1 — Clone the Repository
git clone <your-repo-url>
cd propunes

6. Backend Setup (Laravel)
Step 2 — Install Backend Dependencies
composer install

Step 3 — Create and Configure the .env File
cp .env.example .env


Then update:

    Database name

    Database username

    Database password

Step 4 — Generate Application Key
php artisan key:generate

7. Database Setup
    Step 5 — Run Migrations & Seeders

    This will create tables and insert demo data (including an admin account):

    php artisan migrate --seed

8. Frontend Setup (Vue + Tailwind)
    Step 6 — Install Frontend Dependencies
    npm install

    Step 7 — Start the Development Server
    npm run dev
This compiles your Vue components and Tailwind styles.

9. Starting the Application
    Step 8 — Start Laravel Server
    php artisan serve


    The application is now available at:

    ➡️ http://localhost:8000

10. Additional Setup
Leaflet Installation

    This is required for the admin map:

    npm install leaflet

    PDF Handling (for CV previewing)

    If your build requires PDF.js:

    npm install pdfjs-dist

    Storage Linking (for profile pictures & CV storage)
    php artisan storage:link