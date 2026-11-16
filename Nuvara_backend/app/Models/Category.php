<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['parent_id', 'slug', 'name', 'description', 'image', 'sort_order'];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    public function getImageAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        $cleanPath = ltrim($value, '/');
        if (!str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = 'storage/' . $cleanPath;
        }

        return url($cleanPath);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getLocalized($field, $locale = null)
    {
        $locale = $locale ?: request()->header('Accept-Language', 'en');
        return $this->$field[$locale] ?? ($this->$field['en'] ?? '');
    }
}
