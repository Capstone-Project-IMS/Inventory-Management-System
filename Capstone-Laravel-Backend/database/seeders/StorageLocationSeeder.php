<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StorageLocation;
use App\Models\Location;
use App\Models\ProductStorage;

class StorageLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 "bins" for each location
        $locations = Location::all();

        foreach ($locations as $location) {
            for ($i = 0; $i < 10; $i++) {
                $storageLocation = StorageLocation::create([
                    'location_id' => $location->id,
                    'current_capacity' => 0,
                ]);
            }
        }
    }
}
