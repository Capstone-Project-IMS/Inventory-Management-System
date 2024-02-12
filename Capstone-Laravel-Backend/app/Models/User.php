<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
/**
 * References:
 * @see UserType
 * @see Employee
 * @see Vendor
 * @see Customer
 * @see Log
*/

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
        This user belongs to one user type
        Many to One 
        @see UserType::users()
    */
    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    /**
       This user has one employee instance
        One to One 
       * @see Employee::user()
    */
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    /**
        This user has one vendor instance
        One to One
        * @see Vendor::user()
    */
    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    /**
        This user has one customer instance
        One to One
       * @see Customer::user()
    */
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    /**
        This user has many logs
        One to Many
        * @see Log::user()
    */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_type',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
