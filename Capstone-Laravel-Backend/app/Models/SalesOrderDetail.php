<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetail extends Model
{
    use HasFactory;

    /**
       This order detail belongs to one sales order
       Many to One
       * @see SalesOrder::salesOrderDetail()
    */
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    /**
       This order detail belongs to one product configuration
       Many to One
       * @see ProductDetail::salesOrderDetails()
    */
    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class);
    }

    protected $fillable = [
        'sales_order_id',
        'product_detail_id',
        'quantity',
        'price',
    ];
}
