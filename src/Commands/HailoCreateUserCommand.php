<?php

namespace Pardalsalcap\Hailo\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Pardalsalcap\Hailo\Models\User;
use Spatie\Permission\Models\Role;

class HailoCreateUserCommand extends Command
{
    private string $email;

    private string $user_name;

    private string $password;

    public $signature = 'hailo:create-user';

    public $description = 'Creates a new user';

    public function handle(): int
    {
        $this->createRole();

        if ($this->confirm('Do you wish to create an admin user?')) {
            $this->getUserDetails();
            $user = User::where('email', $this->email)->first();
            $this->createAdminUser($user);
        }

        return self::SUCCESS;
    }

    private function createRole(): void
    {
        Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $this->info('Role super-admin created');
    }

    private function getUserDetails(): void
    {
        $this->user_name = $this->ask('Name:');
        $this->email = $this->ask('Email:');
        $this->password = $this->secret('Password');
    }

    private function createAdminUser(?User $user = null): void
    {
        if (! $user) {
            $user = User::create([
                'name' => $this->user_name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $this->info('Admin user created');
            $this->assignAdminRole($user);
        } else {
            $this->warn('Cancelled: Admin user already exists');
        }
    }

    private function assignAdminRole(User $user): void
    {
        if (! $user->hasRole('super-admin')) {
            $user->assignRole('super-admin');
            $this->info('Admin role granted');
        } else {
            $this->warn('Admin role already granted');
        }
    }
}
