<?php

namespace Database\Seeders;

use App\Models\ProductDetail;
use App\Models\SalesOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesOrderDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salesOrders = SalesOrder::all();
        $productDetails = ProductDetail::all();

        foreach ($salesOrders as $salesOrder) {
            // purchase order total
            $total = 0;
            // random number of products for this order
            $faker = \Faker\Factory::create();
            $productCount = $faker->numberBetween(1, 10);

            for ($i = 0; $i < $productCount; $i++) {
                // random is a collection method that returns a random item from the collection
                $productDetail = $productDetails->random();
                // if the quantity is greater than the product detail quantity, set the quantity to the product detail quantity
                $quantity = ($faker->numberBetween(1, 10) > $productDetail->quantity) ? $productDetail->quantity : $faker->numberBetween(1, 10);
                // price for individual product
                $unitPrice = $productDetail->price;
                // price for whole quantity of this item is the amount of products times the unit price
                $price = $quantity * $unitPrice;
                // add the price to the total for this order
                $total += $price;
                $salesOrder->salesOrderDetails()->create([
                    'sales_order_id' => $salesOrder->id,
                    'product_details_id' => $productDetail->id,
                    'quantity' => $quantity,
                    'price' => $price
                ]);
            }
            $salesOrder->total = $total;
            $salesOrder->save();
        }
    }
}
