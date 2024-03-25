<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FloorLocation extends Model
{
    use HasFactory;

    /**
     * This floor location belongs to one location
     * Many to One
     * @see Location::floorLocations()
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * This floor location has One product detail
     * One to One
     * @see ProductDetail::floorLocation()
     */
    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class);
    }
}
