<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['product_id', 'user_id', 'rating', 'comment', 'images', 'is_approved'];

    protected $casts = [
        'comment' => 'array',
        'images' => 'array',
        'is_approved' => 'boolean',
        'rating' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLocalized($field, $locale = null)
    {
        $locale = $locale ?: request()->header('Accept-Language', 'en');
        return $this->$field[$locale] ?? ($this->$field['en'] ?? '');
    }
}
