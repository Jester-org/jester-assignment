<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\PermissionFactory;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        PermissionFactory::new()->count(5)->create();
    }
}
