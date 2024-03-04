<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
       This product belongs to one vendor
       Many to One
       * @see Vendor::products()
    */
    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }

    /**
       This product has many details
       One to Many
       * @see ProductDetail::product()
    */
    public function productDetails(){
        return $this->hasMany(ProductDetail::class);
    }

    // gets the total count of product details
    public function getTotalCountAttribute(){
        return $this->productDetails->sum('quantity');
    }

    // load all relations
    public function loadAllRelations(){
        return $this->load('vendor', 'productDetails', 'productDetails.productStorages', 'productDetails.floorLocation', 'productDetails.priority', 'productDetails.logs', 'productDetails.purchaseOrderDetails', 'productDetails.salesOrderDetails');
    }

    protected $fillable = [
        'vendor_id',
        'name',
        'description',
        'category',
        'display_image',
    ];
}
