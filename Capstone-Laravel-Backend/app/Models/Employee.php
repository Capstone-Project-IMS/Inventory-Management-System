<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * References:
 * @see User
 * @see EmployeeType
 * 
 */
class Employee extends Model
{
    use HasFactory;

    /**
       This employee belongs to one user instance
       One employee to one user
       * @see User::employee()
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
        This employee belongs to one employee type
        Many employees to one employeeType
       * @see EmployeeType::employees()
    */
    public function employeeType()
    {
        return $this->belongsTo(EmployeeType::class);
    }

    /**
       This employee has many purchase orders
       One to many
       * @see PurchaseOrder::employee()
    */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
       This employee has many sales orders
       One to Many
       * @see SalesOrder::employee()
    */
    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }
    protected $fillable = [
        'user_id',
        'employee_type_id',
        'role',
    ];
}
