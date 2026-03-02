<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating a new admin user...');

        $name = $this->ask('Full Name');
        $email = $this->ask('Email Address');
        $password = $this->secret('Password');

        if (empty($name) || empty($email) || empty($password)) {
            $this->error('All fields are required.');
            return 1;
        }

        if ($this->confirm('Do you wish to continue?')) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
            ]);

            $this->info('Admin user created successfully!');
            $this->line("  <fg=yellow>Name:</> {$user->name}");
            $this->line("  <fg=yellow>Email:</> {$user->email}");

            return 0;
        }

        $this->info('User creation cancelled.');
        return 1;
    }
}
