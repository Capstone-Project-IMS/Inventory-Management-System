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

    /**
        This product config has many cart items
       * @see CartItem::Method
    */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_details_id');
    }

    // Get the configuration of the product
    public function getConfigurationAttribute()
    {
        $color = $this->color ?? "";
        $size = $this->size ?? "";
        return $this->product->name . " " . $color . " " . $size;
    }

    // Get the total quantity in storage
    public function getStorageAttribute()
    {
        return $this->productStorages->sum('quantity');
    }

    public function getFloorCountAttribute()
    {
        return $this->floorLocation->current_capacity;
    }

    // Check if product is available online
    public function isAvailableOnline()
    {
        return $this->storage > 0;
    }

    // decrease the quantity in storage after a purchase or refilling the floor
    public function decreaseStorage($quantity)
    {
        $this->productStorages->each(function ($storage) use ($quantity) {
            if($storage->quantity >= $quantity){
                $storage->decrement('quantity', $quantity);
                $quantity = 0;
                return true;
            }
            else{
                return false;
            }
        });

    }

    // Load all relations
    public function loadAllRelations()
    {
        return $this->load('product', 'floorLocation', 'productStorages', 'priority', 'logs', 'purchaseOrderDetails', 'salesOrderDetails', 'floorLocation.location', 'productStorages.storageLocation', 'productStorages.storageLocation.location');
    }

    // Check if amount is available
    public function isAvailable($requestQuantity)
    {
        return $this->storage >= $requestQuantity;
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
