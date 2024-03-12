<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    /**
        This log belongs to One user
        Many to One
        * @see User::logs()
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
       This log belongs to one product configuration
       Many to One
       * @see ProductDetail::logs()
    */
    public function productDetail(){
        return $this->belongsTo(ProductDetail::class);
    }

    /**
       This log belongs to One action
       Many to One
       * @see Action::logs()
    */
    public function action(){
        return $this->belongsTo(Action::class);
    }

    /**
       This log belongs to one sales order
       * @see SalesOrder::logs()
    */
    public function salesOrder(){
        return $this->belongsTo(SalesOrder::class);
    }

    /**
       This log belongs to one purchase order
       * @see PurchaseOrder::logs()
    */
    public function purchaseOrder(){
        return $this->belongsTo(PurchaseOrder::class);
    }
    protected $fillable = [
        'user_id',
        'product_detail_id',
        'sales_order_id',
        'purchase_order_id',
        'action_id',
        'description',
    ];
}
