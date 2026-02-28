<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create
        {--name= : Admin name}
        {--email= : Admin email}
        {--password= : Admin password}';

    protected $description = 'Create an admin user that can access the Filament panel';

    public function handle(): void
    {
        $name     = $this->option('name')     ?? $this->ask('Name');
        $email    = $this->option('email')    ?? $this->ask('Email');
        $password = $this->option('password') ?? $this->secret('Password');

        if (User::where('email', $email)->exists()) {
            $this->error("A user with email [{$email}] already exists.");
            return;
        }

        $user = User::create([
            'name'              => $name,
            'email'             => $email,
            'password'          => Hash::make($password),
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $role = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);

        DB::table('model_has_roles')->insertOrIgnore([
            'role_id'    => $role->id,
            'model_type' => User::class,
            'model_id'   => $user->id,
        ]);

        $this->info("Admin user [{$email}] created successfully.");
        $this->line('Login at: ' . url('/admin/login'));
    }
}
