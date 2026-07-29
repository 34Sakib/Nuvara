<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrustFeature extends Model
{
    protected $fillable = [
        'feature_key',
        'title',
        'sub',
        'icon',
        'icon_color',
        'bg_color',
        'sort_order',
        'status'
    ];

    protected $casts = [
        'title' => 'array',
        'sub' => 'array',
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
