<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Define roles
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrator with full access',
                'guard_name' => 'web',
            ],
            [
                'name' => 'user',
                'description' => 'Regular user with limited access',
                'guard_name' => 'web',
            ],
        ];

        // Create or update roles
        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name'], 'guard_name' => $roleData['guard_name']],
                ['description' => $roleData['description']]
            );
        }

        // Create sales-related permissions
        $permissions = [
            'view sales',
            'create sales',
            'edit sales',
            'delete sales',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Assign permissions to roles
        $adminRole = Role::findByName('admin', 'web');
        $userRole = Role::findByName('user', 'web');

        $adminRole->syncPermissions($permissions);
        $userRole->syncPermissions(['view sales', 'create sales', 'edit sales', 'delete sales']); // Added 'edit sales', 'delete sales' for user

        // Create additional random roles
        \Database\Factories\RoleFactory::new()->count(3)->create();
    }
}