<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'type',
        'title',
        'image',
        'link',
        'badge',
        'badge_text',
        'headline',
        'sub',
        'button_text',
        'bg_gradient',
        'text_color',
        'badge_bg',
        'btn_style',
        'product_id',
        'sort_order',
        'status',
        'position',
        'active_from',
        'active_to'
    ];

    protected $casts = [
        'title' => 'array',
        'headline' => 'array',
        'sub' => 'array',
        'button_text' => 'array',
        'status' => 'boolean',
        'active_from' => 'datetime',
        'active_to' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getLocalized($field, $locale = null)
    {
        $locale = $locale ?: request()->header('Accept-Language', 'en');
        if (is_array($this->$field)) {
            return $this->$field[$locale] ?? ($this->$field['en'] ?? '');
        }
        return $this->$field;
    }
}
