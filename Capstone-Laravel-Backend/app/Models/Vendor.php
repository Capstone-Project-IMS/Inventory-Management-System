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
        This vendor instance belongs to one user
        One to One
        * @see User::vendor()
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
        'user_id',
    ];
}
