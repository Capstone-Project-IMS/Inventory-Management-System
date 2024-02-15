<?php

namespace Database\Seeders;

use App\Models\ProductDetail;
use App\Models\PurchaseOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseOrderDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchaseOrders = PurchaseOrder::all();
        $productDetails = ProductDetail::all();

        $faker = \Faker\Factory::create();

        // loop through purchase orders and add 1-10 product details
        foreach ($purchaseOrders as $purchaseOrder) {
            // purchase order total
            $total = 0;
            // random number of products for this order
            $productCount = $faker->numberBetween(1, 10);
            // for that number of products, get a random product detail and add it to the purchase order
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
                // create the purchase order detail for this purchase order
                $purchaseOrder->purchaseOrderDetails()->create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_details_id' => $productDetail->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }
            // save the total for this purchase order
            $purchaseOrder->total = $total;
            $purchaseOrder->save();
        }
    }
}
