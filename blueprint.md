### `blueprint.md`

#### Overview

This document outlines the plan to add multi-tenancy, a billing system, a public lead capture system, and email campaign automation to a Laravel application. The goal is to isolate data for different tenants, charge them for usage, allow them to capture leads, and enable automated email outreach campaigns.

#### Implemented Features

*   **Multi-Tenancy Architecture:**
    *   Created a `tenants` table and `Tenant` model.
    *   Associated `users`, `leads`, and `businesses` with tenants via a `tenant_id`.
    *   Implemented a global `TenantScope` to automatically filter data.
    *   Used an `IdentifyTenant` middleware to set the tenant context for each request.

*   **Public Lead Capture:**
    *   Created a public-facing lead capture form at `/capture/{tenant_slug}`.
    *   Added a `PublicLeadCaptureController` to handle form display and submission.
    *   Leads are automatically associated with the correct tenant based on the URL slug.
    *   Added a unique `slug` to the `tenants` table to identify tenants in the URL.

*   **Email Campaign Automation:**
    *   **Data Structure:**
        *   `Campaign` model: Represents an email campaign.
        *   `CampaignStep` model: Defines individual emails within a campaign, including subject, body, and delay.
        *   `CampaignRun` model: Tracks an active or completed campaign for a set of leads.
        *   Migrations created for `campaigns`, `campaign_steps`, `campaign_runs`, and the `campaign_run_lead` pivot table.
    *   **Core Logic:**
        *   `CampaignAutomationController`: Handles starting a campaign for selected leads. It creates a `CampaignRun` and dispatches jobs for the first step.
        *   `ProcessCampaignEmail` Job: A queued job responsible for sending individual campaign emails. This allows for delayed sending and prevents blocking of the UI.
    *   **Authorization:**
        *   `CampaignPolicy`: Ensures that only the tenant who owns a campaign can view, manage, or start it.
    *   **User Interface:**
        *   Resourceful `CampaignsController` for full CRUD management of campaigns.
        *   Resourceful `CampaignStepController` for full CRUD management of campaign steps, nested under campaigns.
        *   A `campaigns/show.blade.php` view that displays campaign details, its steps (with edit/delete functionality), and a form to select leads and start the campaign.
        *   Views for creating and editing campaign steps.

#### Current Plan

The campaign automation feature is now functionally complete. Users can create campaigns, add, edit, and delete steps, and start the campaign for their leads. The next step to improve this feature would be to enhance the user experience by adding a rich text editor for the email body in the campaign step forms. This will allow users to create more visually appealing and effective emails.
