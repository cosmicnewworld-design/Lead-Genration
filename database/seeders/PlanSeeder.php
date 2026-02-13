<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create([
            'name' => 'Free Tier',
            'slug' => 'free-tier',
            'stripe_price_id' => 'price_free_tier_placeholder',
            'price' => 0.00,
            'description' => 'Basic features for getting started.',
        ]);

        Plan::create([
            'name' => 'Pro Tier',
            'slug' => 'pro-tier',
            'stripe_price_id' => 'price_pro_tier_placeholder',
            'price' => 29.00,
            'description' => 'Advanced features for growing businesses.',
        ]);
    }
}
