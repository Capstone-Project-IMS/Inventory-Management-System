<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    /**
       This action has many logs
       One to Many
       * @see Log::action()
    */
    public function logs(){
        return $this->hasMany(Log::class);
    }

    // Gets the action by the name passed to it
    public static function getByName($name){
        // Use self because it is a static method and refers to the class itself
        return self::where('action', $name)->first();
    }
    protected $fillable = [
        'action'
    ];
}
