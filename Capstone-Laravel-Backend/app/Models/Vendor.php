<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * References:
 * @see User
 */
class Vendor extends Model
{
    use HasFactory;

    /**
       This vendor has many contacts
       One to Many
       * @see VendorContact::vendor()
    */
    public function contacts()
    {
        return $this->hasMany(VendorContact::class);
    }

    /**
       This vendor has many products
       One to Many
       * @see Product::vendor()
    */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
       This vendor has many purchase orders
       One to Many
       * @see PurchaseOrder::vendor()
    */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
    protected $fillable = [
        'name',
        'is_approved',
    ];
}
