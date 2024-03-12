<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    /**
     * This cart item belongs to one product detail
     * Many to One
     * @see ProductDetail::cartItems()
     */
    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_details_id');
    }

    /**
     * This cart item belongs to one customer
     * Many to One
     * @see Customer::cartItems()
     */
    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    // Save cart item
    public function saveCartItem()
    {
        $this->status = 'saved';
    }

    // unsave cart item
    public function unsaveCartItem()
    {
        $this->status = 'in-cart';
    }

    // checks if item is in cart or saved
    public function isSaved()
    {
        return $this->status == 'saved';
    }

    // check if the quantity in the cart plus the new quantity is greater than the quantity in stock
    // Can only add to cart if the new quantity passed is less than or equal to the quantity in storage
    //* Cant purchase floor items online
    public function canAddToOnlineCart($newQuantity)
    {
        if ($this->productDetail) {
            dd($this->productDetail->storage . ", " . $newQuantity);
            return $newQuantity <= $this->productDetail->storage;
        }

        return false;
    }



    // update the cart item quantity
    public function updateQuantity($newQuantity)
    {
        $this->quantity = $newQuantity;
        $this->price = $this->productDetail->price * $newQuantity;
        $this->save();
    }

    // load all relations
    public function cartItemDetails()
    {
        $this->load('productDetail', 'productDetail.product');
    }


    protected $fillable =
        [
            'user_id',
            'product_details_id',
            'quantity',
            'price',
            'status'
        ];
}
