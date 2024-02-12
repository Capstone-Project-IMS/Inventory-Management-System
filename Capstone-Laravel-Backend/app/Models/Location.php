<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    /**
       This location has many product details
       One to Many
       * @see ProductDetail::location()
    */
    public function productDetails(){
        return $this->hasMany(ProductDetail::class);
    }

    protected $fillable = [
        'name',
        'type',
        'aisle',
        'row',
        'position',
        'max_capacity',
        'current_capacity',
    ];
        
}
