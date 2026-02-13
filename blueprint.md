# Blueprint: Lead Generation SaaS Platform

## 1. Project Overview & Vision

This project is a powerful, multi-tenant SaaS platform built with Laravel. It is designed to help businesses manage leads, automate email campaigns, and gain actionable insights through a powerful analytics dashboard. The vision is to create a market-ready, scalable, and secure application that can be sold to multiple customers on a subscription basis.

## 2. Implemented Styles, Designs, and Features

This section documents the current state of the application.

### 2.1. Core Architecture

-   **Framework:** Laravel
-   **Database:** PostgreSQL (connected via Supabase connection pooler)
-   **Frontend:** Blade templates with Tailwind CSS and Vite.
-   **Authentication:** Standard Laravel authentication scaffolding.
-   **Environment:** Configured for development within Firebase Studio (formerly Project IDX).

### 2.2. Multi-Tenancy (Initial Implementation)

-   **Package:** `stancl/tenancyforlaravel` is installed and configured.
-   **Tenant Identification:** Tenants are identified by domain (`DomainTenantFinder`).
-   **Tenant & Landlord Models:**
    -   `App\Models\Tenant`: Represents a customer account.
    -   `App\Models\User`: Belongs to a tenant.
    -   `App\Models\Lead`: Belongs to a tenant.
-   **Database Structure:**
    -   **Landlord Database:** Contains the `tenants` table.
    -   **Tenant Databases:** Migrations are set up to run on tenant databases.
-   **Routing:** Web and API routes are configured to be tenant-aware.

## 3. Current Task: Full SaaS Feature Implementation

This section outlines the plan to build out the complete SaaS functionality as requested.

### Plan & Actionable Steps

**Phase 1: Solidify Multi-Tenancy & User Onboarding (Current Focus)**

1.  **Tenant Registration:** Create a registration flow where a new user can create a new tenant account (e.g., `company.yourapp.com`).
2.  **User Scoping:** Ensure that all data (leads, users, etc.) is strictly scoped to the current tenant. A user from Tenant A should never see data from Tenant B.
3.  **Central Domain:** Implement logic for the central or "landlord" domain (e.g., `app.yourapp.com`) where users can log in and manage their account.

**Phase 2: Stripe Integration & Subscription Management**

1.  **Install Laravel Cashier:** Integrate `laravel/cashier` for Stripe billing.
2.  **Define Subscription Plans:** Create models and migrations for subscription plans (e.g., `plans` table with name, price, features).
3.  **Implement Subscription Flow:**
    -   Create a billing portal where users can select a plan.
    -   Handle Stripe webhooks to manage subscription status (active, canceled, etc.).
    -   Create middleware to restrict access to features based on the user's subscription plan.

**Phase 3: Email Automation System**

1.  **Campaign & Sequence Models:** Create the database structure for email campaigns and sequences (e.g., `campaigns`, `sequences`, `sequence_emails`).
2.  **Email Editor:** Build a user interface for creating and editing email templates, including support for dynamic placeholders (e.g., `{{lead.name}}`).
3.  **Queue & Schedule Jobs:** Use Laravel's queue system to send emails in the background.
    -   Create Artisan commands to schedule and trigger campaigns.
    -   Implement logic to track email opens and clicks (e.g., using a tracking pixel).

**Phase 4: Dashboard & Analytics**

1.  **Data Aggregation:** Create queries and services to aggregate data for analytics (e.g., leads per day, conversion rate, email open rate).
2.  **Dashboard UI:** Design and build the dashboard interface using a charting library (e.g., Chart.js).
3.  **Report Generation:** Implement functionality to generate and export reports (e.g., as CSV or PDF).
