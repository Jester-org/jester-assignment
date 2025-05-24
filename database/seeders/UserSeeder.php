<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles exist
        $roles = [
            [
                'name' => 'superadmin',
                'description' => 'Super Administrator with full access',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin',
                'description' => 'Administrator with sales management access',
                'guard_name' => 'web',
            ],
            [
                'name' => 'user',
                'description' => 'Regular user with limited access',
                'guard_name' => 'web',
            ],
        ];
        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name'], 'guard_name' => $roleData['guard_name']],
                ['description' => $roleData['description']]
            );
        }

        // Ensure sales-related permissions exist
        $permissions = [
            'view sales',
            'create sales',
            'edit sales',
            'delete sales',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $superadminRole = Role::findByName('superadmin', 'web');
        $adminRole = Role::findByName('admin', 'web');
        $userRole = Role::findByName('user', 'web');

        $superadminRole->syncPermissions($permissions);
        $adminRole->syncPermissions($permissions);
        $userRole->syncPermissions(['view sales', 'create sales']);

        // Create Super Admin user
        $superadmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'username' => 'jester',
            'is_admin' => true,
            'password' => Hash::make('Jester'),
        ]);
        $superadmin->assignRole(['superadmin', 'admin']);

        // Create Admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'is_admin' => true,
            'password' => Hash::make('admin'),
        ]);
        $admin->assignRole('admin');

        // Create additional users with 'user' role
        User::factory()->count(4)->create()->each(function ($user) {
            $user->assignRole('user');
        });
    }
}