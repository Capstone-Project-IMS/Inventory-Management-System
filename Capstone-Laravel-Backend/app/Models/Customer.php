<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    /**
        This customer instance beongs to one user
        One to One
        * @see User::customer()
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
        This user has many cart items
        * @see CartItem::Method
    */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }


    /**
       This customer has many sales orders
       One to Many
       * @see SalesOrder::customer()
    */
    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }


    // Add Product Detail to Online Cart
    //* In the CartItemController::addToOnlineCart() method, get the specific product detail and add it with Auth::user()->customer->addToCart() since only customers can add to online cart
    public function addToOnlineCart(ProductDetail $productDetail, $quantity)
    {
        $cartItem = $this->cartItems()->where('product_details_id', $productDetail->id)->first();
        // If the cart item already exists
        if ($cartItem) {
            return $this->errorResponse('Product Already in Cart', 400);
        } else {
            if ($productDetail->isAvailable($quantity)) {
                $cartItem = new CartItem([
                    'customer_id' => $this->id,
                    'product_details_id' => $productDetail->id,
                    'quantity' => intval($quantity),
                    'price' => round($productDetail->price * $quantity, 2),
                    'status' => 'in-cart'
                ]);
                $this->cartItems()->save($cartItem);
            } else {
                return $this->errorResponse('Not Enough Product in Stock', 400);
            }
        }
        return $cartItem;
    }

    // Get saved cart items
    public function savedCartItems()
    {
        return $this->user->cartItems()->where('status', 'saved')->get();
    }

    // Get total price of cart items that are in cart
    public function getCartTotal()
    {
        $cartItems = $this->cartItems()->where('status', 'in-cart')->get();
        $total = 0;
        foreach ($cartItems as $cartItem) {
            $total += $cartItem->price;
        }
        return $total;
    }

    // Checks if item is in cart
    public function hasInCart(ProductDetail $productDetail)
    {
        return $this->cartItems()->where('product_details_id', $productDetail->id)->where('status', 'in-cart')->exists();
    }

    // Order Cart/Checkout
    public function orderOnline()
    {
        // If there isnt enough product in stock throw an exception which will reverse anythign done in this method
        return DB::transaction(function () {
            $cartItems = $this->cartItems()->where('status', 'in-cart')->get();
            $salesOrder = new SalesOrder([
                'customer_id' => $this->id,
                'total' => $this->getCartTotal(),
                'type' => 'online',
                'order_date' => now(),
                'status' => 'pending'
            ]);
            $this->salesOrders()->save($salesOrder);
            foreach ($cartItems as $cartItem) {
                $productDetail = $cartItem->productDetail;
                if (!$productDetail->isAvailable($cartItem->quantity)) {
                    throw new \Exception('Not Enough Product in Stock' . $productDetail->id . " " . $productDetail->storage);
                }
                $salesOrderDetail = new SalesOrderDetail([
                    'sales_order_id' => $salesOrder->id,
                    'product_details_id' => $cartItem->product_details_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price
                ]);
                $salesOrder->salesOrderDetails()->save($salesOrderDetail);
                $cartItem->delete();
            }
            return $salesOrder;
        });
    }

    // Purchase Online Cart
    public function checkout()
    {
        //
    }


    protected $fillable = [
        'user_id',
    ];
}
