<?php
namespace Database\Seeders;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::factory()->create([
            'name' => 'something',
            'description' => 'something',
            'guard_name' => 'web', // ✅ This line fixes the error
        ]);            
    }  
}

