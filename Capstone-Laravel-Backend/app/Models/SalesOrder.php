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
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
       This sales order has many sales order details
       One to Many
       * @see SalesOrderDetail::salesOrder()
    */
    public function salesOrderDetails()
    {
        return $this->hasMany(SalesOrderDetail::class);
    }

    /**
       This sales order has many logs
       * @see Log::salesOrder()
    */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    // Ship order if all sales order details are fulfilled
    public function tryShipping()
    {
        // Check if all items in the order have been fulfilled
        if ($this->salesOrderDetails()->where('status', '<>', 'fulfilled')->count() == 0) {
            $this->status = 'shipped';
            $this->save();
            return true;
        }
        return false;
    }

    // load all relations
    public function loadAllRelations()
    {
        return $this->load('customer', 'customer.user', 'employee', 'employee.user', 'salesOrderDetails', 'salesOrderDetails.productDetail', 'salesOrderDetails.productDetail.product');
    }

    protected $fillable = [
        'customer_id',
        'employee_id',
        'total',
        'order_date',
        'type',
        'status'

    ];
}
