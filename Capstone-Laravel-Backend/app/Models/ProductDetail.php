<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;

    /**
       This configuration belongs to one product
       Many to One
       * @see Product::productDetails()
    */
    public function product()
    {
        $this->belongsTo(Product::class);
    }

    /**
       This product configuration belongs to 1 location
       Many to One
       * @see Location::productDetails()
    */
    public function location()
    {
        $this->belongsTo(Location::class);
    }

    /**
       This product configuration has one priority
       One to One
       * @see ProductPriority::productDetails()
    */
    public function priority()
    {
        return $this->hasOne(ProductPriority::class);
    }

    /**
       This configuration has many purchase order details
       One to Many
       * @see PurchaseOrderDetail::productDetail()
    */
    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    /**
       This configuration has many sales order details
       One to Many
       * @see SalesOrderDetail::productDetail()
    */
    public function salesOrderDetails()
    {
        return $this->hasMany(SalesOrderDetail::class);
    }

    /**
       This product config has many Logs
       One to Many
       * @see Log::productDetail()
    */
    public function logs(){
        return $this->hasMany(Log::class);
    }
    protected $fillable = [
        'product_id',
        'location_id',
        'color',
        'size',
        'price',
        'quantity',
        'barcode',
    ];
}
