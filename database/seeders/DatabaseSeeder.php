<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            GovernorateSeeder::class,
            AmenitySeeder::class,
            MaintenanceServiceSeeder::class,
            ContentSeeder::class,
            DemoContentSeeder::class,
            UpdateUnitDescriptionsSeeder::class,
        ]);
    }
}
