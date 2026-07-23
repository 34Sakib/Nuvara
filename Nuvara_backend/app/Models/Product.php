<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku', 'category_id', 'brand_id', 'slug', 'name', 'description', 
        'price', 'compare_price', 'stock', 'status', 'avg_rating', 'review_count', 
        'specs', 'is_best_seller', 'is_new'
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'specs' => 'array',
        'is_best_seller' => 'boolean',
        'is_new' => 'boolean',
        'price' => 'float',
        'compare_price' => 'float',
        'avg_rating' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true)->latest();
    }

    public function getLocalized($field, $locale = null)
    {
        $locale = $locale ?: request()->header('Accept-Language', 'en');
        return $this->$field[$locale] ?? ($this->$field['en'] ?? '');
    }
}
