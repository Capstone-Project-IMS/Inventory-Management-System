<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageLocation extends Model
{
    use HasFactory;

    /**
     * This storage location belongs to one location
     * Many to One
     * @see Location::storageLocations()
     */
    public function location(){
        return $this->belongsTo(Location::class);
    }

    /**
     * This storage location has many product storages
     * One to Many
     * @see ProductStorage::storageLocation()
     */
    public function productStorages()
    {
        return $this->hasMany(ProductStorage::class);
    }
}
