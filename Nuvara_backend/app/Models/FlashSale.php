<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $fillable = [
        'title',
        'ends_at',
        'discount_label',
        'status'
    ];

    protected $casts = [
        'title' => 'array',
        'ends_at' => 'datetime',
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
