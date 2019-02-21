<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailSale extends Model
{
    protected $fillable = [
    	'sale_id', 'product_id', 'type_voucher', 'quantity', 'price', 'discount'
    ];
}
