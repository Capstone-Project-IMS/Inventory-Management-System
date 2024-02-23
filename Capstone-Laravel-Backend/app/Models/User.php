<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

/**
 * References:
 * @see UserType
 * @see Employee
 * @see Vendor
 * @see Customer
 * @see Log
 */

class User extends Authenticatable implements MustVerifyEmail
{

    use HasApiTokens, HasFactory, Notifiable, MustVerifyEmailTrait, CanResetPasswordTrait;
    protected $table = "users";
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

    // get the user's full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // get specific user type
    public function getRoleAttribute()
    {
        if ($this->employee()) {
            return $this->employee->employeeType->role;
        } else {
            return $this->userType->role;
        }
    }

    // Checks if user has a specific role
    public function hasRole($role)
    {
        // uses getRoleAttribute to check if it equals the role passed
        return $this->role == $role;
    }
    // find user by email
    public static function findByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    /**
       Send email verification notification
       * @see \App\Notifications\VerifyEmail
    */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
       Comment
       * @see Class::Method
    */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_type_id',
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
