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
            'name' => 'Basic',
            'slug' => 'basic',
            'stripe_plan_id' => 'price_basic_placeholder',
            'price' => 9.99,
            'description' => 'Includes basic CRM and marketing features, up to 100 leads, and 1 campaign.'
        ]);

        Plan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'stripe_plan_id' => 'price_pro_placeholder',
            'price' => 29.99,
            'description' => 'Includes all Basic features, plus up to 1,000 leads, 10 campaigns, and basic reporting.'
        ]);

        Plan::create([
            'name' => 'Business',
            'slug' => 'business',
            'stripe_plan_id' => 'price_business_placeholder',
            'price' => 59.99,
            'description' => 'Includes all Pro features, plus unlimited leads, unlimited campaigns, advanced reporting, and API access.'
        ]);
    }
}
