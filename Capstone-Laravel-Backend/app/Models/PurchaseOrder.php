<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;


    /**
       This purchase order has many details
       One to Many
       * @see PurchaseOrderDetail::purchaseOrder()
    */
    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }
    /**
       This purchase order belongs to one vendor
       Many to one
       * @see Vendor::purchaseOrders()
    */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
       This purchase order belongs to one employee
       Many to One
       * @see Employee::purchaseOrders()
    */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
       This purchase order has many logs, mix ekployee_id, sales_order_id, product_detail_id for scanning an item to fulfill the order 
       * @see Log::purchaseOrder()
    */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }
    protected $fillable = [
        'vendor_id',
        'employee_id',
        'total_price',
        'order_date',
    ];

}
