<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorContact extends Model
{
    use HasFactory;

    /**
     * This vendor contact belongs to one vendor
     * One to Many
     * @see Vendor::contacts()
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * This vendor contact belongs to one user
     * One to One
     * @see User::vendorContact()
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'vendor_id',
        'user_id',
        'is_primary',
    ];
}
