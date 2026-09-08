<?php
namespace App\Services;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
final class CheckoutQuote {
    public function calculate(array $data): array {
        $ids = array_column(array_column($data['cart'], 'product'), 'id');
        $products = Product::whereIn('id', $ids)->orderBy('id')->lockForUpdate()->get()->keyBy('id');
        $lines = []; $productQuantities = []; $variantQuantities = []; $subtotal = 0;
        foreach ($data['cart'] as $item) {
            $product = $products->get($item['product']['id']);
            if (!$product || $product->status !== 'active') $this->fail('A product is no longer available.');
            $variants = $product->variants()->orderBy('id')->lockForUpdate()->get();
            $selection = array_filter($item['variant'] ?? [], fn ($v) => $v !== null && $v !== '');
            $variant = null;
            if ($variants->isNotEmpty()) {
                $matches = $variants->filter(function ($candidate) use ($selection) {
                    $attributes = array_intersect_key($candidate->attribute_set, array_flip(['color', 'size']));
                    ksort($attributes); ksort($selection);
                    return $attributes === $selection;
                });
                if ($matches->count() !== 1) $this->fail('Select a valid, complete product variant.');
                $variant = $matches->first();
            } elseif ($selection) $this->fail('This product does not have the selected variant.');
            $quantity = (int) $item['quantity'];
            $productQuantities[$product->id] = ($productQuantities[$product->id] ?? 0) + $quantity;
            if ($productQuantities[$product->id] > $product->stock) $this->fail('Insufficient product stock. Please update your cart.');
            if ($variant) {
                $variantQuantities[$variant->id] = ($variantQuantities[$variant->id] ?? 0) + $quantity;
                if ($variantQuantities[$variant->id] > $variant->stock) $this->fail('Insufficient stock for the selected variant.');
            }
            $price = Money::cents($variant?->getRawOriginal('price_override') ?? $product->getRawOriginal('price'));
            $subtotal += $price * $quantity;
            if ($subtotal > 9999998499) $this->fail('This order exceeds the supported total.');
            $lines[] = ['product_id' => $product->id, 'variant_id' => $variant?->id,
                'name' => $product->name, 'sku' => $variant?->sku ?? $product->sku,
                'variant' => $selection, 'quantity' => $quantity,
                'unit_price' => Money::decimal($price), 'total' => Money::decimal($price * $quantity)];
        }
        $coupon = app(CouponRules::class)->resolve($data['coupon'] ?? null, $subtotal, true);
        $discount = 0;
        if ($coupon?->type === 'flat') $discount = min($subtotal, Money::cents($coupon->getRawOriginal('value')));
        if ($coupon?->type === 'percent') $discount = intdiv($subtotal * Money::cents($coupon->getRawOriginal('value')) + 5000, 10000);
        $shipping = $subtotal > 15000 || $coupon?->type === 'free_shipping' ? 0 : 1500;
        return ['currency' => 'USD', 'subtotal' => Money::decimal($subtotal),
            'discount' => Money::decimal($discount), 'shipping' => Money::decimal($shipping),
            'total' => Money::decimal($subtotal - $discount + $shipping),
            'total_minor' => $subtotal - $discount + $shipping, 'coupon_code' => $coupon?->code, 'lines' => $lines];
    }
    private function fail(string $message): never {
        throw ValidationException::withMessages(['cart' => $message]);
    }
}
