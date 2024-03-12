<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStorage extends Model
{
    use HasFactory;

    /**
     * This product storage belongs to one product detail
     * Many to One
     * @see ProductDetail::productStorages()
    */
    public function productDetail(){
        return $this->belongsTo(ProductDetail::class);
    }

    /**
     * This product storage belongs to one storage location
     * Many to One
     * @see StorageLocation::productStorages()
    */
    public function storageLocation(){
        return $this->belongsTo(StorageLocation::class);
    }

    protected $fillable = [
        'product_details_id',
        'storage_location_id',
        'quantity',
    ];
}
