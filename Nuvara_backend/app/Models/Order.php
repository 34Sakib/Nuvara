<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'status', 'subtotal', 'discount', 'shipping_fee', 'total', 
        'currency', 'locale', 'shipping_name', 'shipping_address', 'shipping_city', 
        'shipping_state', 'shipping_zip', 'shipping_country', 'payment_status', 'customer_email',
        'checkout_key', 'checkout_hash', 'checkout_receipt', 'coupon_code', 'channel', 'payment_method', 'cashier_name'
    ];

    protected $casts = [
        'checkout_receipt' => 'array',
        'subtotal' => 'float',
        'discount' => 'float',
        'shipping_fee' => 'float',
        'total' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
