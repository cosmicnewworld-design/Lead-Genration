### `blueprint.md`

#### Overview

This document outlines the plan to add multi-tenancy and a billing system to a Laravel application. The goal is to isolate data for different tenants and charge them for usage based on subscription plans.

#### Implemented Features

*   **Multi-Tenancy Architecture:**
    *   Created a `tenants` table and `Tenant` model.
    *   Associated `users`, `leads`, and `businesses` with tenants via a `tenant_id`.
    *   Implemented a global `TenantScope` to automatically filter data.
    *   Used an `IdentifyTenant` middleware to set the tenant context for each request.

#### Current Plan: Implement Tenant-Centric Billing

This plan outlines the steps to integrate a subscription-based billing system using Laravel Cashier (Stripe).

1.  **Packages & Configuration:**
    *   Install `laravel/cashier`.
    *   Configure `.env` with Stripe API keys (`STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`).

2.  **Database & Models:**
    *   Run a migration to add Cashier's columns to the `users` table.
    *   Add the `Billable` trait to the `User` model.
    *   Create a `plans` table and `Plan` model to store plan details and limits (`max_leads`, `max_campaigns`, `max_team_members`).

3.  **Subscription Logic:**
    *   Create a `SubscriptionController` to handle plan selection and subscription creation.
    *   Seed the database with initial plans (e.g., Basic, Pro).

4.  **Usage Limits:**
    *   Create a middleware (`EnforceUsageLimits`) to check if a tenant has exceeded their plan's limits.
    *   Apply this middleware to the relevant routes (e.g., creating leads, campaigns).

5.  **Billing Dashboard:**
    *   Create Blade views for tenants to manage their subscription, view billing history, and change plans.

6.  **Stripe Webhooks:**
    *   Configure a route and controller to handle webhooks from Stripe, ensuring subscription data is always in sync.
