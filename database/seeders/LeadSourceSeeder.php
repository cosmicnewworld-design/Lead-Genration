<?php

namespace Database\Seeders;

use App\Models\LeadSource;
use App\Models\LeadSourceCategory;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LeadSourceSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();
        if (!$tenant) {
            return;
        }

        $categories = [
            ['name' => 'Inbound', 'color' => '#22C55E', 'sort_order' => 10],
            ['name' => 'Outbound', 'color' => '#3B82F6', 'sort_order' => 20],
            ['name' => 'Paid', 'color' => '#F59E0B', 'sort_order' => 30],
            ['name' => 'Integrations', 'color' => '#8B5CF6', 'sort_order' => 40],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat['name']] = LeadSourceCategory::updateOrCreate(
                ['tenant_id' => $tenant->id, 'slug' => Str::slug($cat['name'])],
                [
                    'tenant_id' => $tenant->id,
                    'name' => $cat['name'],
                    'slug' => Str::slug($cat['name']),
                    'color' => $cat['color'],
                    'sort_order' => $cat['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        $sources = [
            ['name' => 'Website Form', 'type' => 'manual', 'category' => 'Inbound', 'sort_order' => 10],
            ['name' => 'Chat / WhatsApp', 'type' => 'manual', 'category' => 'Inbound', 'sort_order' => 20],
            ['name' => 'CSV Import', 'type' => 'import', 'category' => 'Inbound', 'sort_order' => 30],
            ['name' => 'Cold Email', 'type' => 'manual', 'category' => 'Outbound', 'sort_order' => 10],
            ['name' => 'LinkedIn', 'type' => 'manual', 'category' => 'Outbound', 'sort_order' => 20],
            ['name' => 'Google Ads', 'type' => 'ads', 'category' => 'Paid', 'sort_order' => 10],
            ['name' => 'Facebook Ads', 'type' => 'ads', 'category' => 'Paid', 'sort_order' => 20],
            ['name' => 'Webhook', 'type' => 'webhook', 'category' => 'Integrations', 'sort_order' => 10],
            ['name' => 'API', 'type' => 'api', 'category' => 'Integrations', 'sort_order' => 20],
            ['name' => 'Scraper', 'type' => 'scraper', 'category' => 'Integrations', 'sort_order' => 30],
        ];

        foreach ($sources as $src) {
            $category = $categoryModels[$src['category']] ?? null;

            LeadSource::updateOrCreate(
                ['tenant_id' => $tenant->id, 'slug' => Str::slug($src['name'])],
                [
                    'tenant_id' => $tenant->id,
                    'lead_source_category_id' => $category?->id,
                    'name' => $src['name'],
                    'slug' => Str::slug($src['name']),
                    'type' => $src['type'],
                    'is_active' => true,
                    'sort_order' => $src['sort_order'],
                ]
            );
        }
    }
}

