<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'type_document', 'num_document', 'address', 'num_phone', 'email'];

    public function provider()
    {
    	return $this->hasOne('App\Provider');
    }
}
