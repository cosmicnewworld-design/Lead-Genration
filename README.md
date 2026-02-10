# Lead Generation SaaS

This project is a powerful, full-stack lead generation and outreach automation platform built with the Laravel framework. It is designed to help businesses, sales teams, and marketing professionals streamline their lead management, automate outreach, and accelerate growth.

## ✨ Implemented Features

-   **Multi-Tenancy Architecture:**
    -   Implemented a basic multi-tenant database schema with a `tenants` table.
    -   Associated `users` and `leads` with tenants to ensure data isolation.
    -   Seeded the database with sample tenants and users for demonstration.
    -   Updated the UI to display tenants and their corresponding users.
-   **Lead Management:** Full CRUD (Create, Read, Update, Delete) functionality for leads.
-   **Secure Authentication:** A complete authentication system with middleware-protected routes for both users and administrators.
-   **Admin Dashboard:** A dedicated dashboard for administrators to view and manage all users and leads.
-   **Status Tracking:** Track and update the status of each lead (e.g., New, Contacted, Replied, Junk).
-   **Data Enrichment Jobs:** Includes backend jobs for verifying lead emails and finding LinkedIn profiles.
-   **Email Outreach:** A foundational system for sending outreach emails is in place.
-   **Modern UI/UX:** A responsive and intuitive user interface built with Blade and Tailwind CSS.

## 📅 Roadmap to Advanced SaaS

The following is a strategic roadmap to upgrade this platform into an advanced, multi-tenant SaaS application similar to industry leaders like Apollo, Instantly, or HubSpot.

### **Phase 1: Foundational SaaS Architecture (Multi-Tenancy)**

-   **Goal:** Re-architect the application to securely serve multiple customers (tenants) from a single codebase.
-   **Tasks:**
    -   Implement a multi-tenant database schema using a `team_id` or `workspace_id` foreign key on all tenant-specific data (leads, campaigns, etc.).
    -   Create automatic tenant identification middleware based on the logged-in user.
    -   Refactor all queries and logic to be tenant-aware, ensuring data isolation.
    -   Implement team/workspace management (create workspace, invite members).

### **Phase 2: User Roles, Permissions & Subscription Management**

-   **Goal:** Introduce role-based access control (RBAC) and a complete subscription and billing system.
-   **Tasks:**
    -   Integrate `spatie/laravel-permission` to manage roles (e.g., Owner, Admin, Member) and permissions within each tenant workspace.
    -   Design and implement subscription plans (`plans`, `subscriptions`, `plan_features` tables).
    -   Integrate Laravel Cashier with Stripe for subscription billing and lifecycle management.
    -   Implement middleware to restrict feature access based on the tenant's subscription plan and usage limits.

### **Phase 3: Advanced Lead & Campaign Automation**

-   **Goal:** Build a powerful, multi-step drip campaign engine.
-   **Tasks:**
    -   Create database tables for `campaigns`, `sequences` (steps), and `sequence_emails`.
    -   Develop a `CampaignManager` service to add leads to campaigns and schedule the first email.
    -   Build a scheduled job (`SendCampaignEmailJob`) that processes the sequence steps, applies delays, and sends emails.
    -   Implement conditions (e.g., "stop if replied") to make sequences intelligent.

### **Phase 4: Analytics, Lead Scoring & Segmentation**

-   **Goal:** Provide actionable insights and intelligence.
-   **Tasks:**
    -   Implement email open and link click tracking using tracking pixels and redirect URLs.
    -   Create an analytics dashboard to display campaign metrics (open rate, reply rate, etc.).
    -   Develop a lead scoring system based on actions (e.g., `+10` for reply, `+1` for open).
    -   Implement lead segmentation based on properties and scores (e.g., "Hot Leads," "Engaged in USA").

### **Phase 5: CRM Pipeline & Multi-Channel Outreach**

-   **Goal:** Add CRM functionality and expand outreach beyond email.
-   **Tasks:**
    -   Build a visual, Kanban-style deal pipeline (`pipelines`, `pipeline_stages` tables).
    -   Integrate WhatsApp Business API for automated WhatsApp messaging.
    -   (Carefully) explore LinkedIn outreach automation, respecting their terms of service.

## 🚀 Tech Stack

-   **Backend:** [Laravel](https://laravel.com/), [PHP](https://www.php.net/)
-   **Frontend:** [Tailwind CSS](https://tailwindcss.com/), [Blade](https://laravel.com/docs/blade), [Vite](https://vitejs.dev/)
-   **Database:** [SQLite](https://www.sqlite.org/index.html) (default), compatible with [MySQL](https://www.mysql.com/) or [PostgreSQL](https://www.postgresql.org/)
-   **Development Environment:** [Node.js](https://nodejs.org/)

## ⚙️ Installation

Follow these steps to set up the project locally.

#### 1. Clone the Repository

```bash
git clone https://github.com/cosmicnewworld-design/Lead-Genration.git
cd Lead-Genration
```

#### 2. Install Dependencies

```bash
composer install
npm install
```

#### 3. Set Up Environment

Copy the `.env.example` file to `.env` and configure your database and other services.

```bash
cp .env.example .env
php artisan key:generate
```

#### 4. Run Database Migrations

```bash
php artisan migrate
```

#### 5. Run the Development Servers

```bash
# In one terminal, run the PHP server:
php artisan serve

# In another terminal, run the Vite asset bundler:
npm run dev
```

---
*This README is a living document that will be updated as the project evolves.*
