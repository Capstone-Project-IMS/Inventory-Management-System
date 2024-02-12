<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;
    protected $table = 'user_type';
    

    /**
        The users that belong to the user type.
        One userType to many users
        * @see User::userType()
    */
    public function users(){
        
        return $this->hasMany(User::class);
    }

    protected $fillable = [
        'role'
    ];

}
