<?php

// Create the databases
$db_path = __DIR__ . '/database/database.sqlite';
if (!file_exists($db_path)) {
    new SQLite3($db_path);
    echo "Created database at $db_path\n";
}

$landlord_db_path = __DIR__ . '/database/landlord.sqlite';
if (!file_exists($landlord_db_path)) {
    new SQLite3($landlord_db_path);
    echo "Created database at $landlord_db_path\n";
}

// Run the migrations
$artisan = __DIR__ . '/artisan';
shell_exec("php $artisan migrate --database=landlord --path=database/migrations/2026_02_14_153523_create_tenants_table.php --force");
shell_exec("php $artisan migrate --force");
