<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
    	'client_id', 'user_id', 'type_voucher', 'serie_voucher', 'num_vaucher', 'date_time', 'tax', 'total', 'state'
    ];

}
