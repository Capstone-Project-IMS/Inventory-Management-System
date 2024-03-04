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
        return $this->belongsTo(Product::class);
    }

    // Storage and floor location
    /**
       This configuration has one floor location
       Many to One
       * @see FloorLocation::productDetail()
    */

    public function floorLocation()
    {
        return $this->hasOne(FloorLocation::class, 'product_details_id');
    }

    /**
        This configuration has many product storages
        One to Many
        * @see ProductStorage::productDetail()
    */
    public function productStorages()
    {
        return $this->hasMany(ProductStorage::class, 'product_details_id');
    }

    /**
       This product configuration has one priority
       One to One
       * @see ProductPriority::productDetails()
    */
    public function priority()
    {
        return $this->hasOne(ProductPriority::class, 'product_details_id');
    }

    /**
       This configuration has many purchase order details
       One to Many
       * @see PurchaseOrderDetail::productDetail()
    */
    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'product_details_id');
    }

    /**
       This configuration has many sales order details
       One to Many
       * @see SalesOrderDetail::productDetail()
    */
    public function salesOrderDetails()
    {
        return $this->hasMany(SalesOrderDetail::class, 'product_details_id');
    }

    /**
       This product config has many Logs
       One to Many
       * @see Log::productDetail()
    */
    public function logs()
    {
        return $this->hasMany(Log::class, 'product_details_id');
    }


    public function getConfigurationAttribute()
    {
        return $this->color . " " . $this->size;
    }

    // Load all relations
    public function loadAllRelations()
    {
        return $this->load('product', 'floorLocation', 'productStorages', 'priority', 'logs', 'purchaseOrderDetails', 'salesOrderDetails', 'floorLocation.location', 'productStorages.storageLocation', 'productStorages.storageLocation.location');
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
