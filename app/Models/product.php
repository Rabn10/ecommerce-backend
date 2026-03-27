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

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if($this->image == null) {
            return "";
        }
        return asset('/uploads/products/small/' . $this->image);
    }

    function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    function product_sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}
