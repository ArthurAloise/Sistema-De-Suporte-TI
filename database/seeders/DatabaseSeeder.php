<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            SetorSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            TypeSeeder::class,
            ReportsDemoSeeder::class
        ]);
    }
}
