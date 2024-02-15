<?php

namespace Database\Seeders;

use App\Models\ProductDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\FloorLocation;
use App\Models\StorageLocation;
use App\Models\Product;
use App\Models\ProductStorage;

class ProductDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // colors and sizes for product details
        $colors = ["black", "white", "yeallow", "red", "blue"];
        $sizes = ["500GB" => "small", "1TB" => "medium", "2TB" => "large"];

        $priceCaps = ["food" => 50, 'tech' => 2000, "clothing" => 200, "sport" => 200, "toy" => 100, "seasonal" => 100, "OTC" => 50, "home" => 200, "office" => 50, "cleaning" => 50, "bath" => 75, "bedding" => 100];


        // get all FloorLocation and StorageLocation rows afrer seeding locations
        $floorLocations = FloorLocation::all();
        $storageLocations = StorageLocation::all();

        // create product details for each product
        Product::all()->each(function ($product) use ($sizes, $colors, $priceCaps) {
            // General price for this product, each will only be 10% difference from this price
            $generalPrice = floor(rand(1, $priceCaps[$product->category])) + 0.99;
            // if category is not food create a combination of sizes and colors
            if ($product->category != 'food') {
                foreach ($sizes as $key => $size) {
                    foreach ($colors as $color) {
                        // if category is tech use GB/TB as size else use small/medium/large
                        $sizeValue = $product->category == 'tech' ? $key : $size;
                        // price is random +- 10% of the general price for each detail to keep each product detail price close to the general price
                        $price = floor($generalPrice * (mt_rand(900, 1100) / 1000)) + 0.99;
                        ProductDetail::factory()->create([
                            'product_id' => $product->id,
                            'size' => $sizeValue,
                            // price is random between 1.99 and the priceCap.99 for the category
                            'price' => $price,
                            'color' => $color,
                        ]);
                    }
                }
            }
            // if category is food
            else {
                $price = floor($generalPrice * (mt_rand(900, 1100) / 1000)) + 0.99;
                ProductDetail::factory()->create([
                    'product_id' => $product->id,
                    'size' => null,
                    'price' => $price,
                    'color' => null,
                ]);
            }
        });

        // After creating product details they go in the floor until full then the rest in storage
        ProductDetail::all()->each(function ($productDetail) use ($floorLocations, $storageLocations) {
            // find next empty "shelf position"
            $floorLocation = $floorLocations->where('current_capacity', 0)->first();

            // put as many as possible in the empty shelf as its max capacity allows
            $remainingQuantity = $productDetail->quantity - $floorLocation->max_capacity;

            // Quantity is more than floor location's max capacity
            if ($remainingQuantity > 0) {
                // Make the floor location current capacity full
                $floorLocation->current_capacity = $floorLocation->max_capacity;

                // Find a storage location with the least amount of items in it in the same location as the floor location
                $storageLocation = $storageLocations->where('location_id', $floorLocation->location_id)->sortBy('current_capacity')->first();
                // create a new "bin item" for this "bin" (storage_location) with the remaining quantity
                // product_details still holds floor and storage location quantity
                // will have to update product_details quantity when either location quantity changes
                ProductStorage::create([
                    'storage_location_id' => $storageLocation->id,
                    'product_details_id' => $productDetail->id,
                    'quantity' => $remainingQuantity,
                ]);
                // Updates how many total items in the "bin"
                $storageLocation->current_capacity += $remainingQuantity;
                $storageLocation->save();
            }
            // Quantity fits in the floor location
            else {
                // put all the items in this "shelf" position
                $floorLocation->current_capacity = $productDetail->quantity;
            }

            // add the product to the "shelf" and save
            $floorLocation->product_details_id = $productDetail->id;
            $floorLocation->save();
            $productDetail->save();
        });
    }
}
