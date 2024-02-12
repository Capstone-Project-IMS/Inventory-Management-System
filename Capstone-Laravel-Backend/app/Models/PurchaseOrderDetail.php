<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    use HasFactory;
    /**
       This order detail belongs to one purrchase order
       Many to One
       * @see PurchaseOrder::purchaseOrderDetails()
    */
    public function purchaseOrder(){
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
       This order detail belongs to one product configuration
       Many to One
       * @see ProductDetail::purchaseOrderDetails()
    */
    public function productDetail(){
        return $this->belongsTo(ProductDetail::class);
    }

    protected $fillable = [
        'purchase_order_id',
        'product_detail_id',
        'quantity',
        'price',
    ];
}
