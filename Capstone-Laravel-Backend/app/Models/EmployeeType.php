<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
   Comment
   * @see \Database\Factories\EmployeeTypeFactory
   * @see \Database\Seeders\EmployeeTypeSeeder
*/
class EmployeeType extends Model
{
    use HasFactory;
    protected $table = 'employee_type';

    /**
       Employees that belong to the employee type.
       One employeeType to many employees
       * @see Employee::employeeType()
    */
    public function employees(){
        return $this->hasMany(Employee::class);
    }

    protected $fillable = [
        'role',
    ];
}
