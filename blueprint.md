# SaaS Lead Generation Platform: Development Roadmap

## CURRENT TASK: Phase 1 - Subscription & Billing System

We are currently implementing the subscription and billing system using Stripe and Laravel Cashier.

**Steps:**
1.  [ ] Install `laravel/cashier`.
2.  [ ] Run migrations for Cashier.
3.  [ ] Add Stripe API keys to the environment file.
4.  [ ] Update the `User` model to make it "billable".
5.  [ ] Create a `plans` table and seeder.
6.  [ ] Build the UI for billing and plan selection.
7.  [ ] Create `SubscriptionController` to manage checkout.
8.  [ ] Create `WebhookController` to handle Stripe events.

---
## Overview

This document outlines the phased development roadmap to evolve the existing Laravel-based multi-tenant application into a comprehensive, production-ready SaaS Lead Generation Platform, competitive with services like Apollo.io, Instantly, and Hunter.io.

---

## Phase 1: Foundation & Core SaaS Functionality

This phase focuses on strengthening the application's architecture, establishing robust multi-tenancy, implementing user roles, and integrating a billing system.

### 1. Proper SaaS Architecture Planning

- **Features:**
    - Single database for tenancy management (landlord), separate databases for each tenant.
    - Centralized identity management.
    - Service-oriented architecture within the monolith.
- **Backend Logic:**
    - `app/Providers/TenantServiceProvider.php`: To handle tenant identification and database connection switching.
    - `app/Services/TenantService.php`: For tenant creation, suspension, and deletion logic.
    - Refactor existing code to use tenant-aware scopes and services.
- **Complexity:** Medium

### 2. Database Structure Improvements

- **Features:**
    - Switch from SQLite to MySQL/Postgres for production scalability.
    - Introduce a `landlord` database for global models (tenants, plans, users).
    - Optimize tenant databases with proper indexing.
- **Database Tables:**
    - **Landlord DB:** `tenants`, `users`, `plans`, `subscriptions`.
    - **Tenant DBs:** `leads`, `campaigns`, `teams`, `roles`, etc. (prefixed with tenant ID or in separate DBs).
- **Backend Logic:**
    - Update `config/database.php` to dynamically set connections.
    - Implement migration commands that can run on all tenant databases. `php artisan tenants:migrate`.
- **Complexity:** Medium

### 3. Multi-Tenant Isolation Optimization

- **Features:**
    - Subdomain-based tenant identification (`tenant1.yourapp.com`).
    - Guaranteed data separation between tenants.
- **Backend Logic:**
    - Middleware (`IdentifyTenant`) to resolve the tenant based on the domain.
    - `app/Tenant` model and repository for managing tenant data.
    - Use `stancl/tenancy` for Laravel package for a robust implementation.
- **Risks & Solutions:**
    - **Risk:** Data leakage between tenants.
    - **Solution:** Rigorous testing and use of automated tenancy packages that handle scoping automatically.
- **Complexity:** High

### 4. Roles & Permissions System

- **Features:**
    - Owner, Admin, and Member roles within a tenant's team.
    - Permission granularity (e.g., can/cannot export leads, can/cannot launch campaigns).
- **Database Tables:**
    - `roles`: (id, name, tenant_id)
    - `permissions`: (id, name)
    - `role_permission`: (role_id, permission_id)
    - `user_role`: (user_id, role_id)
- **Third-Party Integrations:** `spatie/laravel-permission` adapted for multi-tenancy.
- **UI Modules:**
    - Settings -> Team Management page.
    - Invite user modal.
    - Role selection dropdown.
- **Complexity:** Medium

### 5. Subscription & Billing System (Stripe)

- **Features:**
    - Multiple subscription tiers (e.g., Free, Starter, Pro).
    - Usage-based limits (leads, emails, etc.).
    - Secure checkout and payment processing.
    - Subscription management (upgrade, downgrade, cancel).
- **Database Tables:**
    - `plans`: (id, name, stripe_plan_id, price, features)
    - `subscriptions`: (id, tenant_id, plan_id, stripe_id, status, trial_ends_at)
- **Backend Logic:**
    - `SubscriptionController` for handling checkout and portal links.
    - `WebhookController` for handling events from Stripe (payment succeeded, subscription updated).
    - `SubscriptionService` to manage subscription logic.
- **Third-Party Integrations:** `laravel/cashier` (Stripe).
- **Complexity:** High

### 20. Admin Super Panel

- **Features:**
    - Global overview of all tenants.
    - Manage tenants (suspend, delete, impersonate).
    - View system-wide analytics and health.
    - Manage plans and announcements.
- **Backend Logic:**
    - Separate `Admin` guard and middleware.
    - `Admin/DashboardController`, `Admin/TenantController`.
- **UI Modules:**
    - A separate admin area (`/admin`) with its own layout.
    - Data tables for tenants, users, and subscriptions.
- **Third-Party Integrations:** Consider a package like `Filament` or `Laravel Nova` for rapid development.
- **Complexity:** Medium

---

## Phase 2: Advanced Lead & Campaign Management

Focuses on building the core lead generation and outreach engine.

### 6. Advanced Lead Management

- **Features:**
    - Import leads via CSV.
    - Create custom fields for leads.
    - Advanced filtering and segmentation.
    - Lead status and pipeline stages.
- **Database Tables:**
    - `leads`: Add `status`, `pipeline_stage_id`, `score`, `custom_fields` (JSON).
    - `pipeline_stages`: (id, name, order, tenant_id)
    - `lead_tag`: (lead_id, tag_id)
    - `tags`: (id, name, tenant_id)
- **Queues/Jobs:** `ImportLeadsJob` for processing large CSV uploads.
- **UI Modules:**
    - Revamped Leads index page with advanced filters.
    - Lead detail page with activity timeline.
- **Complexity:** Medium

### 7. Campaign Automation System

- **Features:**
    - Multi-step campaigns (e.g., Email -> Delay -> LinkedIn Connection Request).
    - A/B testing for email copy.
    - Define schedule (days of the week, time windows).
- **Database Tables:**
    - `campaigns`: (id, name, tenant_id, status)
    - `campaign_steps`: (id, campaign_id, type, delay_in_minutes, settings (JSON))
    - `campaign_leads`: (campaign_id, lead_id, status, current_step_id)
- **Queues/Jobs:**
    - `ProcessCampaignStepJob`: The main job to execute a step for a lead.
    - `CampaignSchedulerJob`: Runs every minute to dispatch `ProcessCampaignStepJob` for due leads.
- **UI Modules:**
    - Visual campaign builder.
    - Campaign analytics dashboard.
- **Complexity:** High

### 8. Email Sending Engine

- **Features:**
    - Connect multiple email accounts (Gmail, Outlook) per tenant.
    - Send emails through user's own accounts.
    - Throttling to respect provider limits.
- **Database Tables:**
    - `connected_emails`: (id, tenant_id, user_id, email, provider, access_token, refresh_token)
- **Queues/Jobs:** `SendEmailJob` to send emails in the background.
- **Backend Logic:**
    - Use Laravel's Mailer with a custom transport that uses the user's OAuth tokens.
    - Service to handle OAuth flow for connecting accounts.
- **Third-Party Integrations:** `socialiteproviders/google`, `socialiteproviders/microsoft-graph`.
- **Complexity:** High

### 9. Email Warmup System

- **Features:**
    - Automatically send and receive emails between accounts in the warmup pool.
    - Mark warmup emails as "read" and "important".
    - Gradually increase sending volume for new accounts.
- **Database Tables:** `warmup_pool`, `warmup_interactions`.
- **Queues/Jobs:** `ExecuteWarmupInteractionJob`.
- **Backend Logic:**
    - A central pool of all user-connected email accounts.
    - A "master" engine that pairs accounts and schedules interactions.
- **Risks & Solutions:**
    - **Risk:** High complexity in managing interactions and API calls.
    - **Solution:** Build a robust state machine and logging system.
- **Complexity:** High

### 16. Lead Scoring System

- **Features:**
    - Define rules to score leads (e.g., "+10 for opened email", "+5 for title = CEO").
    - Automatically score leads based on their properties and activities.
- **Database Tables:**
    - `scoring_rules`: (id, tenant_id, condition_field, condition_operator, condition_value, points)
- **Queues/Jobs:** `RecalculateLeadScoresJob` to run when rules change or activities occur.
- **Backend Logic:** `LeadScoringService` that evaluates rules against a lead.
- **UI Modules:**
    - Page to create/manage scoring rules.
- **Complexity:** Medium

---

## Phase 3: Data Enrichment & Outreach Channels

Expands the platform's capabilities with more data sources and communication channels.

### 12. Web Scraping & Data Enrichment Module

- **Features:**
    - Scrape websites for contact information or business details.
    - Enrich leads with data from public sources.
- **Backend Logic:**
    - `ScraperService` that uses an HTTP client.
    - Consider a dedicated microservice for heavy scraping tasks.
- **Queues/Jobs:** `ScrapeWebsiteJob`, `EnrichLeadJob`.
- **Third-Party Integrations:** `guzzlehttp/guzzle`, `symfony/panther` (for JS-heavy sites).
- **Risks & Solutions:**
    - **Risk:** Getting blocked by target websites.
    - **Solution:** Use rotating proxies, CAPTCHA solving services, and user-agent randomization.
- **Complexity:** High

### 13. Email Finder + Email Verifier System

- **Features:**
    - Find email addresses based on name and company domain.
    - Verify the deliverability of an email address.
- **Database Tables:** `email_verification_cache`.
- **Backend Logic:**
    - `EmailFinderService` using pattern generation (e.g., `f.last@domain.com`).
    - `EmailVerifierService` checking MX records and using SMTP checks.
- **Third-Party Integrations:** Integrate with services like Hunter.io, ZeroBounce, or build a simplified version in-house.
- **Complexity:** High

### 14. AI Personalization Engine

- **Features:**
    - Generate personalized email opening lines based on a lead's LinkedIn profile or website "About Us" page.
- **Backend Logic:**
    - `AIPersonalizationService` that calls the OpenAI API.
    - Create structured prompts for consistent results.
- **Queues/Jobs:** `GenerateAIPersonalizationJob`.
- **Third-Party Integrations:** OpenAI PHP client.
- **Complexity:** Medium

### 10. LinkedIn Automation Module

- **Features:**
    - View profiles, send connection requests, send messages.
- **Backend Logic:**
    - **CRITICAL:** Do NOT use the user's local browser or a simple server-side script. This will get accounts banned.
    - **Architecture:** Use a cloud-based browser automation service or a dedicated virtual machine/proxy per user to make it appear as a legitimate session.
- **Queues/Jobs:** `ExecuteLinkedInActionJob`.
- **Risks & Solutions:**
    - **Risk:** High risk of LinkedIn account suspension.
    - **Solution:** Clearly communicate risks to users. Implement human-like delays, randomized actions, and strict velocity limits. Do not store user credentials.
- **Complexity:** Very High

### 11. WhatsApp Outreach Module

- **Features:**
    - Send templated WhatsApp messages.
- **Backend Logic:**
    - Requires using the official WhatsApp Business API.
- **Third-Party Integrations:** Twilio API for WhatsApp, or similar providers.
- **Risks & Solutions:**
    - **Risk:** High cost and strict template messaging rules from Meta.
    - **Solution:** Educate users on the rules and costs. Build a template approval UI.
- **Complexity:** High

---

## Phase 4: Analytics, Collaboration & Integration

Focuses on user-facing features for insights, teamwork, and connecting with other systems.

### 15. Analytics Dashboard

- **Features:**
    - Track campaign performance (open rate, reply rate, bounce rate).
    - Visualize lead funnel and scoring distribution.
- **Backend Logic:**
    - `AnalyticsRepository` to query and aggregate data efficiently.
    - Pre-calculate metrics daily/hourly for performance.
- **Queues/Jobs:** `GenerateDailyAnalyticsSnapshotJob`.
- **UI Modules:**
    - Dashboard page with various charts.
- **Third-Party Integrations:** `Chart.js` for rendering charts.
- **Complexity:** Medium

### 17. Team Collaboration System

- **Features:**
    - Assign leads to team members.
    - Internal notes and mentions on lead profiles.
    - Shared campaign templates.
- **Database Tables:**
    - `leads`: Add `assigned_to_user_id`.
    - `notes`: (id, content, lead_id, user_id)
- **Backend Logic:**
    - `TeamController`.
    - Use Policies for authorization (e.g., `LeadPolicy` to check if a user can view a lead).
- **UI Modules:**
    - Notes section on lead detail page.
    - User assignment dropdown.
- **Complexity:** Medium

### 18. Webhook & API Access for Users

- **Features:**
    - Allow tenants to receive webhook notifications for events (e.g., `lead.created`, `email.replied`).
    - Provide a REST API for tenants to manage their data programmatically.
- **Database Tables:** `webhooks` (tenant_id, target_url, events).
- **Backend Logic:**
    - `Api/V1/LeadController`, `Api/V1/CampaignController`, etc.
    - `DispatchWebhookJob` for sending webhook notifications asynchronously.
- **Third-Party Integrations:** `laravel/sanctum` for API authentication.
- **Complexity:** High

---

## Phase 5: Production Readiness, Scaling & Security

Ensures the platform is robust, secure, and ready for a large user base.

### 19. GDPR + Compliance Features

- **Features:**
    - Allow users to export or delete their data.
    - Cookie consent banner.
    - Mechanisms for handling data subject requests.
- **Backend Logic:**
    - Implement jobs to anonymize or export user data.
- **Complexity:** Medium

### 21. Production Deployment Architecture

- **Features:**
    - Use a scalable cloud provider (e.g., AWS, DigitalOcean).
    - Load balancer to distribute traffic.
    - Separate servers for web, database, and cache (Redis).
    - Automated deployment pipeline (CI/CD).
- **Third-Party Integrations:** AWS (EC2, RDS, ElastiCache), DigitalOcean (Droplets, Managed Databases), Laravel Forge, GitHub Actions.
- **Complexity:** High

### 22. Security Hardening

- **Features:**
    - Implement Content Security Policy (CSP).
    - Protect against common web vulnerabilities (XSS, CSRF, SQLi).
    - Rate limiting on APIs and sensitive endpoints.
    - Regular security audits.
- **Backend Logic:**
    - Use Laravel's built-in security features.
    - Implement rate limiting middleware.
- **Complexity:** Medium

### 23. Background Jobs Optimization

- **Features:**
    - Prioritize queues (e.g., `high`, `default`, `low`).
    - Use Laravel Horizon for monitoring queue workloads.
    - Implement robust error handling and retry mechanisms.
- **Backend Logic:**
    - Assign different jobs to different queues.
    - Configure Horizon for supervisor processes.
- **Complexity:** Medium

### 24. Scaling Strategy (10k+ users)

- **Features:**
    - Horizontal scaling of web servers.
    - Database read replicas to offload query pressure.
    - Use a CDN for static assets.
- **Backend Logic:**
    - Ensure the application is stateless to allow for easy horizontal scaling.
- **Complexity:** High

### 25. Microservices Recommendation

- **When to consider:**
    - When a specific component becomes a performance bottleneck and is computationally intensive (e.g., `Email Warmup Engine`, `Web Scraping`).
    - When a part of the system requires a different tech stack for optimal performance.
- **Potential Microservices:**
    - **Scraping Service:** (Node.js/Puppeteer) - Better for handling heavy JavaScript websites.
    - **Warmup Service:** (Go/Elixir) - Better for managing thousands of concurrent connections and state.
- **Communication:** Use a message broker like RabbitMQ or a simple REST API for communication between the main Laravel app and the microservices.
- **Complexity:** Very High
