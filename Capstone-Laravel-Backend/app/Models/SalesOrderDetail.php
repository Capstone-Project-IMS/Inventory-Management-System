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

    // Fulfill order
    public function fulfill($quantity)
    {
        $this->status = 'fulfilled';
        $this->save();

        if ($this->salesOrder->tryShipping()) {
            return true;
        } else {
            return false;
        }
    }


    protected $fillable = [
        'sales_order_id',
        'product_details_id',
        'quantity',
        'price',
    ];
}
