<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
    	'id', 'contact_name', 'contact_phone'
    ];

    // public $timestamps = false;

    public function client()
    {
    	return $this->belongsTo('App\Client');
    }
}
