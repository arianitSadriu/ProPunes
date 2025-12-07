#  ProPunes - Job Board Platform

A modern, full-stack job board platform built with **Laravel**, **Vue.js**, and **Tailwind CSS**. Connects employers with job seekers through an intuitive interface with advanced filtering, real-time statistics, and interactive visualizations.

---

##  Overview

ProPunes is a comprehensive job matching platform where:

- **Employees** can discover opportunities, upload CVs, and apply to jobs
- **Employers** can post positions, manage applications, and track candidates
- **Administrators** monitor platform metrics with an interactive dashboard featuring charts and maps

---

##  Features

###  Employee Features
-  Register and login securely
-  Upload and manage profile picture
-  Upload CV (required for job applications)
-  Browse and search job posts with advanced filtering
-  Save favorite job posts for later
-  Apply to jobs with CV submission

###  Employer Features
-  Complete company profile setup
-  Upload and manage company logo/image
-  Create, edit, and delete job postings
-  View and manage job applications
-  Track application statistics

###  Admin Features
-  Comprehensive dashboard with system statistics
-  Manage users (view and delete)
-  Manage job posts (view and delete)
-  Interactive Leaflet map showing job locations by city
-  Chart.js visualizations for platform insights
-  Application acceptance/rejection analytics

---

##  Technology Stack

| Layer | Technology | Purpose |
|-------|-----------|---------|
| **Backend** | Laravel 8+ | MVC architecture, API endpoints, business logic |
| **Frontend** | Vue.js 3 | Interactive UI components, state management |
| **Styling** | Tailwind CSS | Utility-first CSS framework for responsive design |
| **Database** | MySQL 8+ | Data persistence and relationships |
| **Mapping** | Leaflet.js | Interactive maps with user location markers |
| **Charts** | Chart.js | Data visualizations (doughnut, bar charts) |
| **Build Tool** | Vite | Lightning-fast frontend bundling |

### Architecture Pattern
ProPunes follows **ModelViewController (MVC)** pattern:
- **Models**  Handle database access and define relationships
- **Controllers**  Process business logic and return responses
- **Views**  Vue.js components for dynamic rendering with Inertia.js

---

##  Prerequisites

Ensure you have the following installed on your system:

- **PHP** 8.0 or higher
- **Composer** (PHP dependency manager)
- **Node.js** 14+ with npm/yarn
- **MySQL** 8.0+ (or MariaDB)
- **Laravel CLI** (optional but recommended)
- **XAMPP/Laragon** (or any local development server)

---

##  Installation & Setup

### Step 1: Clone the Repository

\\\ash
git clone <your-repo-url>
cd propunes
\\\

---

##  Backend Setup (Laravel)

### Step 2: Install Backend Dependencies

\\\ash
composer install
\\\

### Step 3: Configure Environment Variables

Copy the example environment file and update database credentials:

\\\ash
cp .env.example .env
\\\

**Update the following in \.env\:**
\\\env
DB_DATABASE=propunes
DB_USERNAME=root
DB_PASSWORD=your_password
\\\

### Step 4: Generate Application Key

\\\ash
php artisan key:generate
\\\

---

##  Database Setup

### Step 5: Run Migrations and Seeders

This will create all database tables and populate demo data (includes admin account):

\\\ash
php artisan migrate --seed
\\\

**Admin Login Credentials:**
- Email: \dmin@example.com\
- Password: \password\

---

##  Frontend Setup (Vue.js + Tailwind)

### Step 6: Install Frontend Dependencies

\\\ash
npm install
\\\

### Step 7: Development - Compile Frontend Assets

For local development with hot reload:

\\\ash
npm run dev
\\\

**This command:**
-  Compiles Vue.js components
-  Processes Tailwind CSS styles
-  Enables hot module replacement (HMR)
-  Watches for file changes automatically

---

##  Building for Production

### Step 8: Build Frontend Assets

For production deployment, create optimized bundles:

\\\ash
npm run build
\\\

**This command:**
-  Minifies JavaScript and CSS
-  Optimizes bundle size for faster loading
-  Generates source maps for debugging
-  Creates production-ready assets in \public/build/\

**After running \
pm run build\:**
1. Ensure \.env\ is set to \APP_ENV=production\
2. Run \php artisan config:cache\ to cache configuration
3. Deploy the \public/build/\ directory to your server

---

##  Starting the Application

### Step 9: Start Laravel Development Server

\\\ash
php artisan serve
\\\

The application will be available at: **\http://localhost:8000\**

**To run on a different port:**
\\\ash
php artisan serve --port=8080
\\\

**To access from other machines on your network:**
\\\ash
php artisan serve --host=0.0.0.0 --port=8000
\\\

---

##  Project Structure

\\\
ProPunes/
 app/
    Models/              # Database models with relationships
    Http/
       Controllers/     # Business logic handlers
       Requests/        # Form validation rules
       Middleware/      # HTTP middleware
    Jobs/                # Queued jobs (email notifications)
 routes/
    web.php              # Web application routes
    api.php              # API endpoints
 resources/
    js/                  # Vue.js components and pages
    css/                 # Tailwind CSS files
    views/               # Blade templates
 database/
    migrations/          # Database schema files
    seeders/             # Sample data seeding
    factories/           # Model factories for testing
 public/
    build/               # Compiled assets (generated by npm run build)
    storage/             # User uploads (CVs, images)
 config/                  # Application configuration files
\\\

---

##  Additional Configuration

### Storage Linking (for User Uploads)

Create a symbolic link to store user uploads (CVs, profile pictures):

\\\ash
php artisan storage:link
\\\

This creates a shortcut from \storage/app/public\ to \public/storage\, allowing users to download and view uploaded files.

### Install Leaflet.js (Map Library)

Required for the admin dashboard map feature:

\\\ash
npm install leaflet
\\\

### Install PDF.js (CV Preview)

For rendering PDF files in the browser:

\\\ash
npm install pdfjs-dist
\\\

---

##  Environment Variables Reference

Key \.env\ variables for ProPunes:

\\\env
# Application Settings
APP_NAME=ProPunes
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Connection
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=propunes
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@propunes.local
\\\

---

##  Troubleshooting

### Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| \
pm run dev\ command not found | Run \
pm install\ first to install dependencies |
| Database connection error | Verify MySQL is running and \.env\ credentials are correct |
| Storage link error | Run \php artisan storage:link\ (check directory permissions) |
| Vue components not rendering | Ensure \
pm run dev\ is running in a separate terminal |
| Build fails with memory error | Run: \NODE_OPTIONS=\"--max-old-space-size=2048\" npm run build\ |
| Port 8000 already in use | Use \php artisan serve --port=8080\ for a different port |
| CORS errors with API | Check \config/cors.php\ and whitelist your domain |
| 404 errors on routes | Clear cache: \php artisan route:cache\ |

---

##  Useful Commands

| Command | Purpose |
|---------|---------|
| \composer install\ | Install PHP dependencies |
| \
pm install\ | Install Node.js dependencies |
| \
pm run dev\ | Start development server with hot reload |
| \
pm run build\ | Build optimized production assets |
| \php artisan serve\ | Start Laravel development server |
| \php artisan migrate\ | Run pending database migrations |
| \php artisan migrate:rollback\ | Undo the last batch of migrations |
| \php artisan tinker\ | Interactive PHP shell for debugging |
| \php artisan storage:link\ | Create symbolic link for file storage |
| \php artisan config:cache\ | Cache configuration files |
| \php artisan route:cache\ | Cache route definitions |
| \php artisan cache:clear\ | Clear application cache |

---

##  License

This project is open source. Please refer to the LICENSE file for more details.

---

##  Contributing

Contributions are welcome! Please feel free to submit a pull request with improvements.

---

##  Support

For issues or questions, please open an issue on the repository.

---

**Happy coding! **
