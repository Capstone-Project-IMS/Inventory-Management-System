<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
        This customer instance beongs to one user
        One to One
        * @see User::customer()
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
       This customer has many sales orders
       One to Many
       * @see SalesOrder::customer()
    */
    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }



    protected $fillable = [
        'user_id',
    ];
}
