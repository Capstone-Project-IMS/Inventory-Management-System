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
    protected $fillable = [
        'action'
    ];
}
