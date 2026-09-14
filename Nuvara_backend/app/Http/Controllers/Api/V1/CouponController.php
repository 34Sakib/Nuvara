<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Services\{CouponRules, Money};
use Illuminate\Http\Request;
class CouponController extends Controller {
    public function validateCoupon(Request $request, CouponRules $rules) {
        $data = $request->validate(['code' => 'required|string|max:100', 'subtotal' => 'required|numeric|min:0|max:99999999.99']);
        // Preview only: checkout independently resolves database prices.
        $coupon = $rules->resolve($data['code'], Money::cents($data['subtotal']));
        return response()->json(['success' => true, 'coupon' => [
            'code' => $coupon->code, 'type' => $coupon->type, 'value' => $coupon->value, 'min_order' => $coupon->min_order,
        ]]);
    }
}
