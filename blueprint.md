# Project Blueprint

## Overview

This project is a full-stack Laravel application. It appears to be a multi-tenant CRM or marketing automation platform.

## Style and Design

*No frontend styles have been implemented yet.*

## Features

### Database Schema

*   **Multi-tenancy:** The database is designed for multi-tenancy, with `tenants` and `tenant_id` columns on most tables.
*   **Users and Teams:** The application has a standard `users` table, along with `teams` and `team_user` tables for collaborative work.
*   **Leads:** A `leads` table is the core of the CRM functionality.
*   **Campaigns:** The application includes `campaigns`, `campaign_steps`, `campaign_runs`, and related tables for marketing automation.
*   **Billing:** The application has `subscriptions` and `subscription_items` tables, suggesting integration with a payment provider.

## Current Task: Fix Database Migration Issues

### Plan

1.  **Fix Database Connection:** The initial problem was a database connection error. This was resolved by:
    *   Setting the `DB_CONNECTION` to `sqlite` in the `.env` file.
    *   Hardcoding the `database` path in the `config/database.php` file to an absolute path.
    *   Creating an empty `database/database.sqlite` file.
2.  **Remove Redundant Migrations:** Several migrations were duplicated, causing "duplicate column" or "table already exists" errors. These were resolved by deleting the redundant migration files.
3.  **Fix Migration Order:** An `add_indexes_to_foreign_keys` migration was running before the tables were created. This was resolved by renaming the migration to have a later timestamp.
4.  **Create Missing Table:** The `campaigns` table was missing a migration. This was resolved by creating a new migration for the `campaigns` table.

### Implemented Steps

*   Set `DB_CONNECTION=sqlite` in `.env`
*   Set `DB_DATABASE` to `/workspace/database/database.sqlite` in `.env`.
*   Modified `config/database.php` to hardcode the database path.
*   Created `database/database.sqlite`.
*   Deleted `database/migrations/2026_02_13_163721_add_tenant_id_to_users_table.php`.
*   Deleted `database/migrations/2026_02_13_163755_add_tenant_id_to_leads_table.php`.
*   Deleted `database/migrations/2026_02_13_184143_create_campaign_steps_table.php`.
*   Renamed `database/migrations/2026_02_13_184643_add_indexes_to_foreign_keys.php` to `database/migrations/2027_01_01_000000_add_indexes_to_foreign_keys.php`.
*   Created `database/migrations/2026_02_13_191732_create_campaigns_table.php` with the correct schema.
*   Ran `php artisan migrate:fresh` successfully.
