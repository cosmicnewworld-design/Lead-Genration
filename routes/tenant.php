<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/test-tenant-route', function () {
    return 'This is a tenant route';
});
