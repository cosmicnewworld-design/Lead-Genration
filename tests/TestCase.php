<?php

namespace Tests;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected static $ranMigrations = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (!static::$ranMigrations) {
            $this->artisan('migrate:fresh');
            $this->artisan('tenants:migrate --seed');
            static::$ranMigrations = true;
        }

        $this->tenant = Tenant::create([
            'id' => 'test',
        ]);

        $this->tenant->domains()->create([
            'domain' => 'test.localhost',
        ]);

        tenancy()->initialize($this->tenant);

        $this->user = User::factory()->create();
    }
}
