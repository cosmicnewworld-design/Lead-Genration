# Lead Generation and Outreach Automation Tool

## Overview

This tool is designed to help users scrape leads from Google Maps, enrich them with contact information, and automate outreach via email and WhatsApp.

## Setup

1. Clone the repository.
2. Run `composer install`.
3. Run `npm install`.
4. Copy `.env.example` to `.env`.
5. Run `php artisan key:generate`.
6. Run `php artisan migrate`.
7. Run `npm run dev` and `php artisan serve` in separate terminals.

## Implemented Features

### Lead Scraping
- Scrape Google Maps for business information based on keywords.
- Store scraped leads in a local database.
- A simple UI to initiate the scraping process.

### Lead Enrichment
- Automatically enrich leads with additional information in the background.
- Find and store LinkedIn profiles, email addresses, and other social media links.
- Display enriched data, including social media profiles, in the leads table.

## Features

### Data Enrichment
- Enrich leads with email addresses, LinkedIn profiles, and social media links (Facebook, Twitter, Instagram, etc.).

### Outreach Automation
- Send templated emails to leads.
- Send WhatsApp messages to leads.

### Lead Management
- A central dashboard to view and manage all leads.
- Filter and sort leads based on their status.
- Track the status of each lead (New, Contacted, Replied, etc.).

## Project Structure

- **Backend:** Laravel
- **Frontend:** Blade with Tailwind CSS
- **Database:** SQLite

## Current Plan: Outreach Automation (Email)

1.  **Database Migration:** Add a `status` column to the `leads` table to track outreach status (e.g., 'new', 'contacted', 'replied').
2.  **Email Mailable:** Create a mailable class to define the email structure and content.
3.  **Email Sending Job:** Create a queued job to send emails in the background.
4.  **Email Composer UI:**
    - Create a new route and controller method for the email composer page.
    - Build a view with a form to write an email subject and body.
    - Include a list of leads with checkboxes to select recipients.
5.  **Controller Logic:** Implement the controller logic to dispatch the email sending job for the selected leads.
6.  **Update Lead Status:** Update the status of the leads to 'contacted' after the email job is dispatched.
7.  **UI Updates:** Display the lead status in the main leads table.

## Completed Plan

1.  **Models and Migrations:** Define the database schema for leads and businesses. (Done)
2.  **UI/UX:** Design and build the user interface for lead scraping, management, and outreach. (Partially Done)
3.  **Backend Logic:** Implement the functionality for scraping, enrichment, and outreach. (Partially Done)
    - Lead enrichment job created and running. (Done)
    - Social media links are being scraped and stored. (Done)
    - UI updated to display social media icons. (Done)
