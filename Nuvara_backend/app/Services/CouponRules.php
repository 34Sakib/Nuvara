<?php
namespace App\Services;
use App\Models\Coupon;
use Illuminate\Validation\ValidationException;
final class CouponRules {
    public function resolve(?string $code, ?int $subtotal = null, bool $lock = false): ?Coupon {
        if (!$code) return null;
        $query = Coupon::where('code', $code);
        $coupon = ($lock ? $query->lockForUpdate() : $query)->first();
        $error = match (true) {
            !$coupon || $coupon->status !== 'active' => 'This coupon is not available.',
            $coupon->expires_at && $coupon->expires_at->isPast() => 'This coupon has expired.',
            $coupon->usage_limit !== null && $coupon->times_used >= $coupon->usage_limit => 'This coupon has reached its usage limit.',
            !in_array($coupon->type, ['percent', 'flat', 'free_shipping'], true) => 'This coupon is not supported.',
            $subtotal !== null && $subtotal < Money::cents($coupon->getRawOriginal('min_order')) => 'The minimum spend for this coupon has not been reached.',
            default => null,
        };
        if ($error) throw ValidationException::withMessages(['coupon' => $error]);
        if ($coupon->type === 'percent' && Money::cents($coupon->getRawOriginal('value')) > 10000) {
            throw ValidationException::withMessages(['coupon' => 'This coupon has an invalid percentage.']);
        }
        return $coupon;
    }
}
