<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailIncome extends Model
{
    protected $fillable = [
    	'income_id', 'product_id', 'quantity', 'price'
    ];

    public $timestamps = false;
}
