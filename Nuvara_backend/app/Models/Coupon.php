<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'type', 'value', 'min_order', 'expires_at', 'usage_limit', 'status'];

    protected $casts = [
        'value' => 'float',
        'min_order' => 'float',
        'expires_at' => 'datetime',
        'usage_limit' => 'integer',
    ];
}
