<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\RoleFactory;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        RoleFactory::new()->count(5)->create();
    }
}
