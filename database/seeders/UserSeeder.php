<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure the roles exist
        $roles = ['superadmin', 'admin'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Create Super Admin user
        $superadmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'username' => 'jester',
            'is_admin' => true,
            'password' => Hash::make('Jester'),
        ]);

        // Assign roles to user
        $superadmin->assignRole($roles);

        // Create additional users
        User::factory()->count(4)->create();
    }
}
