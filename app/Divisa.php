<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Divisa extends Model
{
    protected $fillable = [
    	'price','user_id'
    ];

    public function user(){
    	return $this->hasOne('App\User');
    }
}
