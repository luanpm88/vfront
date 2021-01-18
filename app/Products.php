<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table ='products';
    public function category()
	{
		return $this->belongsTo('App\Category','category_id');
	}
}
