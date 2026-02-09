# Laravel Lead Generation & Management

This is a comprehensive lead generation and management application built with the Laravel framework. It provides a robust system for capturing, tracking, and managing leads, designed for efficiency and scalability.

## ✨ Implemented Features

-   **Lead Management:** Full CRUD (Create, Read, Update, Delete) functionality for leads.
-   **Secure Authentication:** A complete authentication system with middleware-protected routes for both users and administrators.
-   **Admin Dashboard:** A dedicated dashboard for administrators to view and manage all users and leads.
-   **Status Tracking:** Track and update the status of each lead (e.g., New, Contacted, Replied, Junk).
-   **Data Enrichment Jobs:** Includes backend jobs for verifying lead emails and finding LinkedIn profiles.
-   **Email Outreach:** A foundational system for sending outreach emails is in place.
-   **Modern UI/UX:** A responsive and intuitive user interface built with Blade and Tailwind CSS.

## 📅 Planned Features

The following features are planned for future development:

-   **Full Outreach Automation:** A complete system for automating multi-step outreach campaigns.
-   **WhatsApp Integration:** Add WhatsApp as a channel for lead outreach.
-   **Advanced Reporting:** A dashboard with detailed analytics on lead conversion and campaign performance.

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

Install PHP and JavaScript dependencies:

```bash
composer install
npm install
```

#### 3. Set Up Environment

Create a copy of the example environment file and generate your unique application key:

```bash
cp .env.example .env
php artisan key:generate
```

*Note: You may need to configure your `.env` file with database credentials and any third-party API keys.*

## 🗄️ Database Migration

#### 1. Create the Database File

This project uses SQLite by default. If you are using it, create the database file:

```bash
touch database/database.sqlite
```

#### 2. Run Migrations

Run the database migrations to create the necessary tables:

```bash
php artisan migrate
```

## 🏃‍♀️ How to Run Locally

To run the application, you need to start both the PHP server and the Vite development server in separate terminals.

#### 1. Start the Laravel Server

```bash
php artisan serve
```

#### 2. Start the Vite Server

```bash
npm run dev
```

The application will be available at `http://localhost:8000`.
