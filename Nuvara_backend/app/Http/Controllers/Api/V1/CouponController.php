<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Coupon;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $request->code)->where('status', 'active')->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code'
            ], 422);
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon has expired'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'coupon' => [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => (float)$coupon->value,
                'min_order' => (float)$coupon->min_order
            ]
        ]);
    }
}
