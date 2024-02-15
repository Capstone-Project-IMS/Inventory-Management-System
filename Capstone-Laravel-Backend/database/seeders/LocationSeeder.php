<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @see Location
     * @see \Database\Factories\LocationFactory
     */
    public function run(): void
    {
        Location::factory()->count(3)->create();
    }
}
