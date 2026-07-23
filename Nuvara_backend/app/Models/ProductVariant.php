<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'sku', 'attribute_set', 'price_override', 'stock'];

    protected $casts = [
        'attribute_set' => 'array',
        'price_override' => 'float',
        'stock' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
