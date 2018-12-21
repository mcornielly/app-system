<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
    	'name', 'description', 'condition'
    ];

    public $timestamps = false;


    public function roles(){
    	return $this->hasMany('App\User');
    }
}
