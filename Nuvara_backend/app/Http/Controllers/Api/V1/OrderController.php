<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'cart' => 'required|array|min:1',
            'cart.*.product.id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'coupon' => 'nullable|string'
        ]);

        $userId = $request->user() ? $request->user()->id : null;

        $subtotal = 0;
        $itemsToCreate = [];

        foreach ($request->cart as $item) {
            $product = Product::find($item['product']['id']);
            
            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for product: " . ($product->name[request()->header('Accept-Language', 'en')] ?? $product->name['en'])
                ], 422);
            }

            $variantId = null;
            if (isset($item['variant']) && count($item['variant']) > 0) {
                $color = $item['variant']['color'] ?? null;
                $size = $item['variant']['size'] ?? null;
                
                $variant = ProductVariant::where('product_id', $product->id)
                    ->where(function ($q) use ($color, $size) {
                        if ($color) {
                            $q->whereJsonContains('attribute_set->color', $color);
                        }
                        if ($size) {
                            $q->whereJsonContains('attribute_set->size', $size);
                        }
                    })->first();

                if ($variant) {
                    $variantId = $variant->id;
                    if ($variant->stock < $item['quantity']) {
                        return response()->json([
                            'success' => false,
                            'message' => "Insufficient stock for selected variant of: " . ($product->name[request()->header('Accept-Language', 'en')] ?? $product->name['en'])
                        ], 422);
                    }
                }
            }

            $price = $product->price;
            $totalPrice = $price * $item['quantity'];
            $subtotal += $totalPrice;

            $itemsToCreate[] = [
                'product_id' => $product->id,
                'variant_id' => $variantId,
                'quantity' => $item['quantity'],
                'unit_price' => $price,
                'total' => $totalPrice,
                'product_model' => $product,
                'variant_model' => $variant ?? null
            ];
        }

        $discount = 0.00;
        $coupon = null;
        if ($request->filled('coupon')) {
            $coupon = Coupon::where('code', $request->coupon)->first();
            if ($coupon) {
                if ($coupon->type === 'percent') {
                    $discount = $subtotal * ($coupon->value / 100);
                } elseif ($coupon->type === 'flat') {
                    $discount = min($coupon->value, $subtotal);
                }
            }
        }

        $shipping = $subtotal > 150 ? 0.00 : 15.00;
        if ($coupon && $coupon->type === 'free_shipping') {
            $shipping = 0.00;
        }

        $total = max(0.00, $subtotal - $discount + $shipping);

        $order = DB::transaction(function () use ($userId, $subtotal, $discount, $shipping, $total, $request, $itemsToCreate) {
            $orderNumber = 'NVR-' . strtoupper(Str::random(6)) . rand(100, 999);
            
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => $orderNumber,
                'status' => 'delivered',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_fee' => $shipping,
                'total' => $total,
                'currency' => 'USD',
                'locale' => request()->header('Accept-Language', 'en'),
                'shipping_name' => $request->fullName,
                'shipping_address' => $request->address,
                'shipping_city' => $request->city,
                'shipping_state' => $request->state,
                'shipping_zip' => $request->zip,
                'shipping_country' => $request->country
            ]);

            foreach ($itemsToCreate as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'variant_id' => $itemData['variant_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total' => $itemData['total']
                ]);

                $itemData['product_model']->decrement('stock', $itemData['quantity']);
                if ($itemData['variant_model']) {
                    $itemData['variant_model']->decrement('stock', $itemData['quantity']);
                }
            }

            return $order;
        });

        return response()->json([
            'success' => true,
            'order_id' => $order->order_number,
            'total' => $order->total,
            'message' => 'Order processed successfully'
        ]);
    }
}
