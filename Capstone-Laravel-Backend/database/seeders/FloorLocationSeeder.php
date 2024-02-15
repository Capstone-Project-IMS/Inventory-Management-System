<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FloorLocation;

class FloorLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @see FloorLocation
     * @see \Database\Factories\FloorLocationFactory
     */
    public function run(): void
    {
        // Get all location ids after seeding locations
        $locationIds = Location::all()->pluck('id')->toArray();
        // array of aisles A-C
        $aisles = range('A', 'C');
        // array of rows 1-10
        $rows = range(1, 10);
        // array of positions 1-10
        $positions = range(1, 10);

        // For each location create aisles A-C, rows 1-10, and positions 1-10
        foreach ($locationIds as $locationId) {
            foreach ($aisles as $aisle) {
                foreach ($rows as $row) {
                    foreach ($positions as $position) {
                        // create a random max capacity for each position
                        $maxCapacity = rand(5, 20);
                        FloorLocation::factory()->create([
                            'location_id' => $locationId,
                            'aisle' => $aisle,
                            'row' => $row,
                            'position' => $position,
                            'max_capacity' => $maxCapacity,
                            'current_capacity' => 0,
                        ]);
                    }
                }
            }
        }
    }
}
