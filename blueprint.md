# Project Blueprint

## Overview

This project is a multi-tenant Laravel application with a robust subscription and billing system powered by Stripe. It allows new tenants to register, manage their subscription plans, and handle billing through a dedicated portal. The application is designed to be scalable and secure, with role-based access control for different user types.

## Implemented Features

### Subscription Management

*   **Subscription Plans:** The application supports multiple subscription plans (e.g., "Basic", "Pro") with different pricing and features. These plans are stored in the `plans` table and can be managed through a seeder.
*   **Dynamic Billing Page:** A dedicated billing page (`/billing`) that dynamically displays either the user's current subscription status and management options (cancel, resume, billing portal) or a pricing table with available plans if the user is not subscribed.
*   **Stripe Integration:** The application is integrated with Stripe for processing payments and managing subscriptions.
*   **Checkout:** Users can subscribe to a a plan by clicking a "Subscribe" button, which redirects them to a Stripe Checkout page.
*   **Subscription Controller:** A consolidated `SubscriptionController` handles all subscription-related actions, including displaying the billing page, initiating checkout, and managing subscription status (cancel, resume, portal).
*   **Webhook Handling:** A webhook endpoint (`/stripe/webhook`) is configured to receive and handle events from Stripe, such as successful payments or subscription updates.

### Multi-Tenancy

*   **Tenant Registration:** New tenants can register through a dedicated registration form (`/register`).
*   **Tenant-aware Models:** The application uses tenant-aware models to ensure data isolation between tenants.

### User Authentication and Authorization

*   **User Authentication:** Users can log in and out of the application.
*   **Role-Based Access Control (RBAC):** The application has a role-based access control system to restrict access to certain features based on user roles (e.g., admin, manager, sales rep).

### Automation Rules

*   **Database Schema:** An `automation_rules` table has been created to store the rules.
*   **Model and Controller:** An `AutomationRule` model and a resourceful `AutomationRuleController` have been created to manage the rules.
*   **CRUD Operations:** Users can create, read, update, and delete automation rules.
*   **Routing:** A resource route for `automation-rules` has been added to `routes/web.php`.
*   **User Interface:** A full-featured user interface has been created at `/automation-rules` to list, create, and edit automation rules. A navigation link has been added to the main navigation.

## Current Request: Create Automation Rules

### Plan

1.  **[x] Create Migration and Model:**
    *   Create a migration file for the `automation_rules` table with all necessary fields.
    *   Run the migration.
    *   Generate the `AutomationRule` model.
2.  **[x] Create Controller and Routes:**
    *   Generate a resourceful `AutomationRuleController`.
    *   Add a resource route for `automation-rules` in `routes/web.php`.
3.  **[x] Implement List View (`index`):**
    *   Create an `index.blade.php` view to list the rules.
    *   Implement the `index` method in the controller to fetch and pass the rules to the view.
    *   Add a navigation link to the main layout.
    *   Display a message if no rules are found.
4.  **[x] Implement Create Functionality (`create` & `store`):**
    *   Create a `create.blade.php` view with a form for new rules.
    *   Implement the `create` method in the controller to display the form.
    *   Implement the `store` method to validate the request, create the new rule, and redirect with a success message.
    *   Display the success message on the `index` page.
