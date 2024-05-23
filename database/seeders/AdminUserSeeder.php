<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');
        $adminPhone = env('ADMIN_PHONE');
        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => $adminEmail,
                'phone' => $adminPhone,
                'role_id' => Role::getIdRoleByTitle('Админ'),
                'password' => $adminPassword,
                'email_verified_at' => now(),
                // Add any additional fields for the admin user if needed
            ]);
        }
    }
}
