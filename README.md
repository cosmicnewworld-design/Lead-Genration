# Laravel Marketing & Sales Automation Platform

This is a full-stack web application built with the Laravel framework. It's designed to be a powerful, yet easy-to-use platform for managing marketing campaigns, leads, and sales pipelines.

## Getting Started

### 1. Installation

*   **Dependencies:** The project uses Composer for PHP dependencies and NPM for frontend assets. Make sure you have both installed.
*   **Environment:**
    1.  Copy the `.env.example` file to a new file named `.env`.
    2.  Run `php artisan key:generate` to generate a unique application key.
*   **Database:**
    1.  The project is configured to use SQLite by default. A `database/database.sqlite` file will be automatically created.
    2.  Run `php artisan migrate` to create all the necessary database tables.
*   **Serve:**
    1.  Run `npm install` to install frontend dependencies.
    2.  Run `npm run dev` to start the Vite development server.
    3.  In a separate terminal, run `php artisan serve` to start the Laravel development server.

### 2. Implemented Features

#### Campaigns Management

*   You can create, view, edit, and delete marketing campaigns.
*   Navigate to the "Campaigns" section from the main dashboard to manage your campaigns.

### 3. How to Add a New Feature (Example: "Tasks")

1.  **Create a Model & Migration:**
    ```bash
    php artisan make:model Task -m
    ```
2.  **Define the Schema:** In the newly created migration file (in `database/migrations`), define the columns for your `tasks` table (e.g., `title`, `description`, `due_date`).
3.  **Run the Migration:**
    ```bash
    php artisan migrate
    ```
4.  **Create a Controller:**
    ```bash
    php artisan make:controller TaskController --resource
    ```
5.  **Define Routes:** In `routes/web.php`, add a new resource route:
    ```php
    Route::resource('tasks', TaskController::class);
    ```
6.  **Create Views:** Create the necessary Blade views (e.g., `index.blade.php`, `create.blade.php`, `edit.blade.php`) in the `resources/views/tasks` directory.
7.  **Add Navigation Link:** Add a link to the `navigation.blade.php` file to access the new "Tasks" section.

## Troubleshooting

*   **"Command Hangs" Issue:** If `php artisan` commands are not responding, it's likely a database connection issue. Double-check your `.env` file for the correct database credentials.
*   **404 Errors:** If you're getting 404 errors, make sure the routes are defined correctly in `routes/web.php` and that the corresponding controller methods and views exist.
