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
use App\Models\UserType;

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
     * This user has one VendorContact instance
     * One to One
     * @see VendorContact::user()
     */
    public function vendorContact()
    {
        return $this->hasOne(VendorContact::class);
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

    // get generic user type
    public function getTypeAttribute()
    {
        return $this->userType->role;

    }
    // get specific user type
    public function getRoleAttribute()
    {
        if ($this->employee) {
            return $this->employee->employeeType->role;
        } else {
            return $this->userType->role;
        }
    }

    // Checks if user has a specific role or if the user has any of the roles in the array
    public function hasRole($roles) // $roles can be an array of strings or a string
    {
        // checks if the roles parameter is an array
        if (is_array($roles)) {
            // uses getRoleAttribute to check if the user has any of the roles in the array
            return in_array($this->role, $roles) || in_array($this->type, $roles);
        }
        // if the roles parameter is not an arra it checks if the user has the role
        return $this->role == $roles || $this->type == $roles;
    }

    public function isEmployee()
    {
        $employeeRoles = EmployeeType::all()->pluck('role')->toArray();
        return in_array($this->role, $employeeRoles) || $this->role == 'admin';
    }

    // find user by email
    public static function findByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    /**
       Send email verification notification overrides the default notification to send our own
       * @see \App\Notifications\VerifyEmail
    */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
         Send password reset notification overrides the default notification to send our own
       * @see \App\Notifications\ResetPassword
    
    */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    // load admin relations
    public function loadAdminRelations()
    {
        return $this->load('userType', 'logs', 'employee', 'employee.purchaseOrders', 'employee.salesOrders');
    }
    // Load Customer Relations
    public function loadCustomerRelations()
    {
        return $this->load('userType', 'customer', 'customer.salesOrders', 'logs');
    }

    // Load Employee Relations
    public function loadEmployeeRelations()
    {
        return $this->load('userType', 'employee', 'employee.purchaseOrders', 'employee.salesOrders', 'employee.employeeType', 'logs');
    }

    // Load Vendor Relations
    public function loadVendorRelations()
    {
        return $this->load('vendorContact', 'vendorContact.vendor', 'logs');
    }


    // Load all relations
    public function loadAllRelations()
    {

        if ($this->hasRole('admin')) {
            return $this->loadAdminRelations();
        } elseif ($this->hasRole('customer')) {
            return $this->loadCustomerRelations();
        } elseif ($this->hasRole('employee')) {
            return $this->loadEmployeeRelations();
        } elseif ($this->hasRole('vendor')) {
            return $this->loadVendorRelations();
        }
    }

    // search for users
    public function searchUsers($searchVal)
    {

        $query = self::query();
        print_r($this->role);

        // If the user is an admin or a manager they can search all users
        if ($this->hasRole(['admin', 'management'])) {
            // if the search value is a number
            if (is_numeric($searchVal)) {
                $searchVal = (int) $searchVal;
                $query->where('id', $searchVal)
                    ->orWhereHas('customer', function ($query) use ($searchVal) {
                        $query->where('id', $searchVal);
                    })
                    ->orWhereHas('employee', function ($query) use ($searchVal) {
                        $query->where('id', $searchVal);
                    });
            }
            // if the search value is not a number
            else {
                // if first name like the search value
                $query->where('first_name', 'like', '%' . $searchVal . '%')
                    // if last name like the search value
                    ->orWhere('last_name', 'like', '%' . $searchVal . '%')
                    // if first name and last name combined like the search value
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $searchVal . '%'])
                    // if email like the search value
                    ->orWhere('email', 'like', '%' . $searchVal . '%');
            }

        } elseif ($this->hasRole(['general', 'fulfillment', 'receiving'])) {
            // Other employees can only search other employees
            if (is_numeric($searchVal)) {
                $searchVal = (int) $searchVal;
                $query->where('user_type_id', UserType::where('role', 'employee')->first()->id)
                    ->where(function ($query) use ($searchVal) {
                        $query->where('id', $searchVal)
                            ->orWhereHas('employee', function ($query) use ($searchVal) {
                                $query->where('id', $searchVal);
                            });

                    });
            }
            // if the search value is not a number
            else {
                $query->where('user_type_id', UserType::where('role', 'employee')->first()->id)
                    ->where(function ($query) use ($searchVal) {
                        $query->where('first_name', 'like', '%' . $searchVal . '%')
                            ->orWhere('last_name', 'like', '%' . $searchVal . '%')
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $searchVal . '%'])
                            ->orWhere('email', 'like', '%' . $searchVal . '%');
                    });
            }
        }

        return $query->get();
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
