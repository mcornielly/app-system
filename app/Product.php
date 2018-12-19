<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $fillable = [
    	'category_id', 'code', 'name', 'price', 'stock', 'description', 'condition'
    ];

    public function category(){
    	return $this->belongsTo('App\Category');
    }
}
