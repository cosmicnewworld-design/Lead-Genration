<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register Firebase services into the container.
     */
    public function register(): void
    {
        $this->app->singleton('firebase', function ($app) {
            $credentialsPath = env('FIREBASE_CREDENTIALS_PATH', storage_path('firebase-key.json'));

            if (!file_exists($credentialsPath)) {
                throw new \Exception("Firebase credentials not found at: $credentialsPath");
            }

            $factory = new \Kreait\Firebase\Factory();
            return $factory->withServiceAccount($credentialsPath)->create();
        });

        // Register Firestore specifically
        $this->app->singleton('firestore', function ($app) {
            return app('firebase')->firestore();
        });

        // Register Realtime Database
        $this->app->singleton('firebase.database', function ($app) {
            return app('firebase')->database();
        });

        // Register Authentication
        $this->app->singleton('firebase.auth', function ($app) {
            return app('firebase')->auth();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
