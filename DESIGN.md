*************************************************************
PROPRUNES: DESIGN DOCUMENT
*************************************************************

1. PROJECT OVERVIEW AND SCOPE

ProPunes is a full-stack job platform connecting employers and job seekers. The platform is built around **Role-Based Access Control (RBAC)** to provide distinct, tailored experiences for each user type.

KEY STAKEHOLDERS AND PRIMARY FUNCTIONS:
--------------------------------------
* EMPLOYEE (Job Seeker): Profile/CV management, job searching, saving jobs, and application submission.
* EMPLOYER: Company profile setup, Job Post CRUD (Create, Read, Update, Delete), and application review.
* ADMIN: System oversight, user/content moderation, and access to the analytical dashboard.


2. ARCHITECTURE AND TECHNOLOGY STACK

The application uses a **Hybrid Monolithic Architecture**, leveraging Laravel for logic and Vue.js for the UI.

2.1. BACKEND: LARAVEL MVC
-------------------------
* Framework: **Laravel** (PHP).
* Pattern: **Model-View-Controller (MVC)**.
    * Controllers process logic, enforce RBAC, and serve **JSON API responses**.
* Database: **MySQL** is the primary data persistence layer.

2.2. FRONTEND: VUE.JS SPA
-------------------------
* Framework: **Vue.js** (Single Page Application - SPA).
* Styling: **Tailwind CSS** for rapid, utility-first UI development.

2.3. KEY TECHNICAL DECISIONS
----------------------------
| AREA | TECHNOLOGY/DECISION | RATIONALE |
| :--- | :--- | :--- |
| Data Visualization | **Chart.js** | Used for statistical representation on the Admin Dashboard. |
| Mapping | **Leaflet.js** | Lightweight, efficient mapping library for visualizing job post locations. |
| CV Preview | **pdfjs-dist** | Enables **in-browser viewing of uploaded CVs** for the Employer role. |


3. DATA MODELS AND RELATIONSHIPS (ELOQUENT)

The application's structural integrity is defined by its core models and explicit Eloquent relationships.

3.1. USER MODEL (users table)
------------------------------
This is the central entity for authentication and authorization.

| FIELD | PURPOSE | DEPENDENCY |
| :--- | :--- | :--- |
| **role** | **Authorization Gate** (Employee, Employer, Admin). | All |
| city_id | Foreign Key, links User to a **City** model. | All |

CORE RELATIONSHIPS FROM USER:
-----------------------------
* **jobs()**: HasMany (Employer posts multiple jobs).
* **company()**: HasOne (Employer links to one company profile).
* **applications()**: HasMany (Employee submits multiple applications).
* **savedPosts()**: **BelongsToMany** (Employee bookmarks multiple jobs via pivot table).


3.2. POST MODEL (posts table)
-----------------------------
Represents a single job listing.

| FIELD | PURPOSE | RELATION TYPE |
| :--- | :--- | :--- |
| user_id | Links to the **Employer** creator of the post. | BelongsTo User |
| company_id | The specific **Company** hiring. | BelongsTo Company |
| location_id | The geographical location. | BelongsTo City |
| category_id | The job classification. | BelongsTo Category |

CORE RELATIONSHIPS FROM POST:
-----------------------------
* **user()**: BelongsTo (Retrieves the Employer details).
* **applications()**: HasMany (Retrieves all submitted Applications for this job).
* **savedByUsers()**: **BelongsToMany** (Retrieves all Employees who have saved this job).


4. OPERATIONAL FLOW AND STRATEGY

4.1. ACCESS CONTROL AND DATA INTEGRITY
--------------------------------------
* **Authorization**: Controlled by the **user.role** field to determine feature access.
* **Data Isolation**: Employer data access is secured by checking **post.user_id** to ensure ownership.
* **Security**: Laravel casting ensures **password** is automatically hashed.

4.2. FUTURE SCALING AND ROADMAP
-------------------------------
1.  **Search Optimization**: Integrate **Elasticsearch** for complex, high-volume full-text queries.
2.  **Real-Time Engagement**: Implement **WebSockets** (Laravel Echo) for instant notifications on applications.
3.  **API Migration**: Formalize API design with versioning and better tokenization to support future mobile applications.