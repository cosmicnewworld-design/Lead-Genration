<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Basic',
            'slug' => 'basic',
            'stripe_plan' => 'price_1P3b79RpJ1nB5YJ2rA3fI5aQ',
            'price' => 10,
            'description' => 'For small teams just getting started.'
        ]);

        Plan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'stripe_plan' => 'price_1P3b9ARpJ1nB5YJ26aLqApcA',
            'price' => 25,
            'description' => 'For growing businesses that need more power.'
        ]);
    }
}
