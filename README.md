# Lead Generation SaaS Platform

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A powerful, multi-tenant SaaS platform designed to help businesses manage leads, automate email campaigns, and gain actionable insights through a powerful analytics dashboard. Built with the Laravel framework.

## ✨ Key Features

This platform is being architected to provide a robust, scalable, and secure environment for managing sales and marketing workflows.

-   **🏢 Multi-Tenant Architecture:**
    -   Securely isolates data for each customer (tenant).
    -   Allows users to be part of multiple teams or workspaces.
    -   Customizable settings for each tenant.

-   **💳 Subscription & Billing System (Stripe Integration):**
    -   Seamless integration with Stripe for recurring subscription billing.
    -   Support for multiple plans (e.g., Free, Pro, Enterprise).
    -   Automated invoicing and payment processing.
    -   Usage-based billing for leads or emails.

-   **🤖 Email Automation System:**
    -   Create and manage powerful email outreach sequences.
    -   Personalize emails with dynamic variables.
    -   Track email opens, clicks, and replies.
    -   Schedule campaigns to run at optimal times.

-   **📊 Dashboard & Analytics:**
    -   A comprehensive dashboard to visualize key metrics (leads acquired, conversion rates, etc.).
    -   Detailed reports on campaign performance and team productivity.
    -   Filterable and exportable data for deeper analysis.

-   **🔐 Role-Based Access Control (RBAC):**
    -   Granular control over what users can see and do.
    -   Pre-defined roles like Admin, Manager, and Sales Agent.
    -   Customizable permissions for specific business needs.

## 💻 Tech Stack

-   **Backend:** Laravel, PHP
-   **Database:** PostgreSQL (via Supabase)
-   **Frontend:** Blade, Tailwind CSS, Vite
-   **Payments:** Stripe (via Laravel Cashier)

## 🚀 Roadmap to SaaS

This project is following a phased approach to deliver a market-ready SaaS application.

### **Phase 1: Foundational SaaS Architecture (In Progress)**

-   **Goal:** Re-architect the application to securely serve multiple customers (tenants).
-   **Status:** Multi-tenancy using `stancl/tenancyforlaravel` is implemented. Tenant-aware models and routing are in place.

### **Phase 2: User Roles, Permissions & Subscription Management**

-   **Goal:** Introduce role-based access control (RBAC) and a complete subscription and billing system.
-   **Tasks:**
    -   Integrate `spatie/laravel-permission` to manage roles and permissions.
    -   Design and implement subscription plans.
    -   Integrate Laravel Cashier with Stripe for billing.
    -   Implement middleware to restrict features based on subscription status.

### **Phase 3: Core SaaS Features (Email & Analytics)**

-   **Goal:** Build the primary features that deliver value to customers.
-   **Tasks:**
    -   Develop a full-featured email automation and campaign management system.
    -   Build a powerful and interactive analytics dashboard.

## 🔧 Getting Started (Local Development)

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/cosmicnewworld-design/Lead-Genration.git
    cd Lead-Genration
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    npm install
    ```

3.  **Setup environment:**
    -   Copy `.env.example` to `.env`.
    -   Update database credentials and other environment variables.
    -   Generate an application key: `php artisan key:generate`

4.  **Run database migrations:**
    ```bash
    php artisan migrate
    ```

5.  **Run the development servers:**
    ```bash
    # In terminal 1
    php artisan serve

    # In terminal 2
    npm run dev
    ```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
