<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriority extends Model
{
    use HasFactory;

    /**
       This priority instance belongs to one product configuration
       One to One
       * @see ProductDetail::priority()
    */
    public function productDetails(){
        return $this->belongsTo(ProductDetail::class);
    }

    protected $fillable = [
        'product_details_id',
        'when_to_order',
        'order_amount',
    ];
}
