<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'category',
        'question',
        'answer',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'question' => 'array',
        'answer' => 'array',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getLocalized($field, $locale = 'en')
    {
        $data = $this->{$field} ?? [];
        if (is_array($data)) {
            return $data[$locale] ?? $data['en'] ?? reset($data) ?? '';
        }
        return (string) $data;
    }
}
