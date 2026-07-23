<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['title', 'image', 'link', 'position', 'active_from', 'active_to'];

    protected $casts = [
        'title' => 'array',
        'active_from' => 'datetime',
        'active_to' => 'datetime',
    ];

    public function getLocalized($field, $locale = null)
    {
        $locale = $locale ?: request()->header('Accept-Language', 'en');
        return $this->$field[$locale] ?? ($this->$field['en'] ?? '');
    }
}
