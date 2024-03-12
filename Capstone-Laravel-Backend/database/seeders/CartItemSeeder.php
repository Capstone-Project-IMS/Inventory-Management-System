<?php

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Customer;
use App\Models\ProductDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::all()->each(function ($customer) {
            for ($i = 0; $i < 5; $i++) {
                $product = ProductDetail::inRandomOrder()->first();
                $productDetailsId = $this->checkIfCartItemExists($customer, $product) ? $product->id : ProductDetail::inRandomOrder()->first()->id;

                $quantity = rand(1, 10);
                $productPrice = $product->price;
                CartItem::updateOrCreate(
                    ['customer_id' => $customer->id, 'product_details_id' => $productDetailsId],
                    ['quantity' => $quantity, 'price' => $quantity * $productPrice, 'status' => 'in-cart'],

                );
            }
        });
    }

    private function checkIfCartItemExists($customer, $productDetail)
    {
        return $customer->cartItems()->where('product_details_id', $productDetail->id)->first();
    }
}
