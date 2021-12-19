<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = "products";
    protected $fillable = [
        'category_id', 'name','product_price','product_sku','image','description', 'status',
    ];
}
