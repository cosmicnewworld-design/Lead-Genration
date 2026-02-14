
# Project Blueprint

## Overview

This project is a multi-tenant lead generation and management platform built with Laravel. It allows users (tenants) to manage businesses, scrape leads, and conduct outreach campaigns. The application is designed to be secure, scalable, and user-friendly.

## Features & Design

### Current State:

*   **Authentication:** Standard Laravel authentication.
*   **Multi-tenancy:** Data is scoped by the user's tenant. Each user belongs to a tenant and can only access their own data.
*   **Lead Management:** Users can create, view, update, and delete leads. Leads are associated with businesses.
*   **Business Management:** Users can manage businesses, which act as containers for leads.
*   **Campaigns:** Users can create and manage outreach campaigns.
*   **AI-Powered Personalization:** The application uses an AI service to personalize outreach messages.
*   **Scoring Rules:** Users can define rules to score leads.
*   **Dashboard:** A simple dashboard displays basic statistics.
*   **Styling:** Basic Bootstrap styling.

### Architecture:

*   **Backend:** Laravel (PHP)
*   **Frontend:** Blade templates
*   **Database:** Not specified (assumed MySQL or similar)
*   **Key Pattern:** Service-Repository pattern is used to separate business logic from data access logic.

## Current Goal: UI/UX Revamp

The current goal is to significantly improve the user interface and user experience of the application. The current design is basic and functional but lacks modern aesthetics and user-friendliness.

### Plan:

1.  **Redesign the main application layout:**
    *   Replace the top navigation bar with a modern, responsive sidebar.
    *   Introduce a clean and visually appealing color scheme and typography.
    *   Use icons to improve navigation clarity.
2.  **Revamp the Dashboard:**
    *   Redesign the statistics display using modern cards with icons and better visual hierarchy.
    *   Create a more engaging and informative layout for the dashboard elements.
3.  **Restyle Core Pages:**
    *   Apply the new design consistently across all pages (Businesses, Leads, Campaigns, etc.).
    *   Improve the layout of index pages (tables), create/edit forms, and show pages.
4.  **Enhance Interactivity:**
    *   Add subtle animations and hover effects to improve user feedback.
    *   Ensure all components are fully responsive and work well on mobile devices.
