<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'rating',
        'quote',
        'avatar',
        'is_featured',
        'sort_order',
        'status'
    ];

    protected $casts = [
        'quote' => 'array',
        'is_featured' => 'boolean',
        'status' => 'boolean'
    ];

    public function getLocalized($field, $locale = null)
    {
        $locale = $locale ?: request()->header('Accept-Language', 'en');
        if (is_array($this->$field)) {
            return $this->$field[$locale] ?? ($this->$field['en'] ?? '');
        }
        return $this->$field;
    }
}
