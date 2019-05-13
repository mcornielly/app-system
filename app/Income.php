<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [ 
    	'provider_id', 'user_id', 'type_voucher', 'serie_voucher', 'num_voucher', 'datehour', 'tax', 'total', 'state'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function provider()
    {
    	return $this->belongsTo('App\Provider');
    }
}
