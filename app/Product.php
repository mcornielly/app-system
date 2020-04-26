<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $fillable = [
    	'category_id', 'code', 'name', 'price', 'margin_gain','margin_amount','price_divisa','stock', 'description', 'condition'
    ];

    public function category(){
    	return $this->belongsTo('App\Category');
    }
}
