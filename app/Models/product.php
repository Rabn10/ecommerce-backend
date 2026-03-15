<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $fillable = [
        'title',
        'price',
        'compare_price',
        'description',
        'short_description',
        'category_id',
        'barcode',
        'brand_id',
        'quantity',
        'sku',
        'status',
        'is_featured',
        'image'
    ];

     public function category()
    {
        return $this->belongsTo(Category::class);
    }

     public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
