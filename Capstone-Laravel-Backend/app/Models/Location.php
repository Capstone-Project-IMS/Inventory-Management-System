<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    /**
       This location has many storage locations
       One to Many
       * @see StorageLocation::location()
    */
    public function storageLocations()
    {
        return $this->hasMany(StorageLocation::class);
    }

    /**
        This location has many floor locations
        One to Many
        * @see FloorLocation::location()
    */
    public function floorLocations()
    {
        return $this->hasMany(FloorLocation::class);
    }
    

    protected $fillable = [
        'name',
    ];
        
}
