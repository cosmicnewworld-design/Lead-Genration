# Laravel Lead Generation & Management

This is a comprehensive lead generation and management application built with the Laravel framework. It provides a robust system for capturing, enriching, and managing leads, designed for efficiency and scalability.

## ✨ Features

- **Lead Management:** Full CRUD functionality for creating, reading, updating, and deleting leads.
- **Data Enrichment:** Automatically enrich lead data using third-party APIs (email, LinkedIn, social profiles).
- **Outreach Automation:** Automate outreach campaigns via email and WhatsApp.
- **Status Tracking:** Track the status of each lead with clear, color-coded labels (e.g., New, Contacted, Replied, Junk).
- **Admin Dashboard:** A dedicated dashboard for administrators to view and manage all users and leads.
- **Modern UI/UX:** A responsive and intuitive user interface built with Blade and Tailwind CSS.
- **Secure Authentication:** A complete authentication system with middleware-protected routes for both users and administrators.

## 🚀 Tech Stack

- **Backend:** [Laravel](https://laravel.com/) (^12.0), [PHP](https://www.php.net/) (^8.2)
- **Frontend:** [Tailwind CSS](https://tailwindcss.com/), [Blade](https://laravel.com/docs/12.x/blade), [Vite](https://vitejs.dev/)
- **Database:** [SQLite](https://www.sqlite.org/index.html) (default), [MySQL](https://www.mysql.com/), or [PostgreSQL](https://www.postgresql.org/)
- **Development Environment:** [Node.js](https://nodejs.org/) 20

## ⚙️ Installation

Follow these steps to set up the project locally.

**1. Clone the Repository**
```bash
git clone https://github.com/cosmicnewworld-design/Lead-Genration.git
cd Lead-Genration
```

**2. Install Dependencies**

Install PHP and JavaScript dependencies:
```bash
composer install
npm install
```

**3. Set Up Environment**

Create a copy of the example environment file and generate your unique application key:
```bash
cp .env.example .env
php artisan key:generate
```

##  migrating the database

**1. Create the Database File**

This project uses SQLite by default. Create the database file:
```bash
touch database/database.sqlite
```

**2. Run Migrations**

Run the database migrations to create the necessary tables:
```bash
php artisan migrate
```

## 🏃‍♀️ How to Run Locally

To run the application, you need to start both the PHP server and the Vite development server in separate terminals.

**1. Start the Laravel Server**
```bash
php artisan serve
```

**2. Start the Vite Server**
```bash
npm run dev
```

The application will be available at `http://localhost:8000`.

