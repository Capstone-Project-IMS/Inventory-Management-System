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
        // Call user types first
        $this->call(
            [
                UserTypeSeeder::class,
                EmployeeTypeSeeder::class,
                LocationSeeder::class,
            ]
        );
        // After types Seed Users
        $this->call(UserSeeder::class);
        // After Users seed Products
        $this->call(ProductSeeder::class);
    }
}
