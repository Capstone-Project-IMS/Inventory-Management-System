<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    /**
       This sales order belongs to one customer
       Many to One
       * @see Customer::salesOrders()
    */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
       This sales order belongs to one Employee
       Many to One
       * @see Employee::salesOrders()
    */
    public function employee(){
        $this->belongsTo(Employee::class);
    }

    /**
       This sales order has many sales order details
       One to Many
       * @see SalesOrderDetail::salesOrder()
    */
    public function salesOrderDetail(){
        return $this->hasMany(SalesOrderDetail::class);
    }

    protected $fillable = [
        'customer_id',
        'employee_id',
        'total_price',
        'order_date',

    ];
}
