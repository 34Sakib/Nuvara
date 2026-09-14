<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{Order, Product, ProductVariant, Coupon};
use App\Services\CheckoutQuote;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    private function rules(): array
    {
        return [
            'cart' => 'required|array|min:1|max:100',
            'cart.*.product.id' => 'required|integer|min:1',
            'cart.*.quantity' => 'required|integer|min:1|max:10000',
            'cart.*.variant' => 'nullable|array:color,size',
            'cart.*.variant.color' => 'nullable|string|max:100',
            'cart.*.variant.size' => 'nullable|string|max:100',
            'coupon' => 'nullable|string|max:100',
        ];
    }

    public function quote(Request $request, CheckoutQuote $pricing)
    {
        $data = $request->validate($this->rules());
        return response()->json(DB::transaction(fn () => $pricing->calculate($data), 3));
    }

    public function checkout(Request $request, CheckoutQuote $pricing)
    {
        $data = $request->validate($this->rules() + [
            'fullName' => 'required|string|max:255', 'email' => 'required|email|max:255',
            'address' => 'required|string|max:255', 'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255', 'zip' => 'required|string|max:255',
            'country' => 'required|string|max:255', 'checkout_key' => 'required|uuid',
            'expected_total_minor' => 'required|integer|min:0|max:9999999999',
        ]);
        $user = $request->user('sanctum');
        if ($request->bearerToken() && !$user) abort(401);
        $hash = hash('sha256', json_encode([$user?->id, $data], JSON_THROW_ON_ERROR));
        $existing = Order::where('checkout_key', $data['checkout_key'])->first();
        if ($existing) return $this->replay($existing, $hash);

        try {
            $order = DB::transaction(function () use ($data, $user, $hash, $pricing, $request) {
                $quote = $pricing->calculate($data);
                if ($quote['total_minor'] !== (int) $data['expected_total_minor']) {
                    throw ValidationException::withMessages(['total' => 'Prices changed. Review the updated total before confirming.']);
                }
                $order = Order::create([
                    'user_id' => $user?->id, 'order_number' => 'NVR-'.strtoupper((string) Str::ulid()),
                    'status' => 'pending', 'payment_status' => 'unpaid',
                    'subtotal' => $quote['subtotal'], 'discount' => $quote['discount'],
                    'shipping_fee' => $quote['shipping'], 'total' => $quote['total'],
                    'currency' => $quote['currency'], 'coupon_code' => $quote['coupon_code'],
                    'locale' => substr($request->header('Accept-Language', 'en'), 0, 10),
                    'shipping_name' => $data['fullName'], 'customer_email' => $data['email'],
                    'shipping_address' => $data['address'], 'shipping_city' => $data['city'],
                    'shipping_state' => $data['state'] ?? '', 'shipping_zip' => $data['zip'],
                    'shipping_country' => $data['country'], 'checkout_key' => $data['checkout_key'],
                    'checkout_hash' => $hash, 'checkout_receipt' => $quote,
                ]);
                foreach ($quote['lines'] as $line) {
                    $updated = Product::whereKey($line['product_id'])->where('stock', '>=', $line['quantity'])
                        ->decrement('stock', $line['quantity']);
                    if (!$updated) throw ValidationException::withMessages(['cart' => 'Product stock changed. Please review your cart.']);
                    if ($line['variant_id']) {
                        $updated = ProductVariant::whereKey($line['variant_id'])->where('stock', '>=', $line['quantity'])
                            ->decrement('stock', $line['quantity']);
                        if (!$updated) throw ValidationException::withMessages(['cart' => 'Variant stock changed. Please review your cart.']);
                    }
                    $order->items()->create(array_intersect_key($line, array_flip(['product_id', 'variant_id', 'quantity', 'unit_price', 'total'])));
                }
                if ($quote['coupon_code']) {
                    $used = Coupon::where('code', $quote['coupon_code'])
                        ->where(fn ($q) => $q->whereNull('usage_limit')->orWhereColumn('times_used', '<', 'usage_limit'))
                        ->increment('times_used');
                    if (!$used) throw ValidationException::withMessages(['coupon' => 'This coupon has reached its usage limit.']);
                }
                return $order;
            }, 3);
        } catch (QueryException|ValidationException $error) {
            // Concurrent retries may have committed while this transaction was waiting.
            $existing = Order::where('checkout_key', $data['checkout_key'])->first();
            if ($existing) return $this->replay($existing, $hash);
            throw $error;
        }
        return $this->replay($order, $hash);
    }

    public function posSale(Request $request, CheckoutQuote $pricing)
    {
        $data = $request->validate($this->rules() + [
            'payment_method' => 'required|in:cash,card',
            'cash_received' => 'nullable|numeric|min:0|max:99999999.99',
            'checkout_key' => 'required|uuid',
            'cashier_name' => 'nullable|string|max:120',
        ]);
        if ($data['payment_method'] === 'card') {
            throw ValidationException::withMessages(['payment_method' => 'Card payments are not configured yet. Use cash or configure a provider.']);
        }
        $hash = hash('sha256', json_encode($data, JSON_THROW_ON_ERROR));
        $existing = Order::where('checkout_key', $data['checkout_key'])->first();
        if ($existing) return $this->replay($existing, $hash);
        $order = DB::transaction(function () use ($data, $pricing, $hash) {
            $quote = $pricing->calculate($data);
            if ($data['payment_method'] === 'cash' && (float) ($data['cash_received'] ?? 0) < (float) $quote['total']) {
                throw ValidationException::withMessages(['cash_received' => 'Cash received is less than the amount due.']);
            }
            $order = Order::create([
                'order_number' => 'POS-'.strtoupper((string) Str::ulid()), 'status' => 'completed',
                'payment_status' => 'paid', 'channel' => 'pos', 'payment_method' => $data['payment_method'],
                'cashier_name' => $data['cashier_name'] ?? 'Cashier', 'subtotal' => $quote['subtotal'],
                'discount' => $quote['discount'], 'shipping_fee' => '0.00', 'total' => $quote['total'],
                'currency' => $quote['currency'], 'shipping_name' => 'Walk-in customer',
                'customer_email' => null, 'shipping_address' => 'In-store purchase', 'shipping_city' => '',
                'shipping_state' => '', 'shipping_zip' => '', 'shipping_country' => '',
                'checkout_key' => $data['checkout_key'], 'checkout_hash' => $hash, 'checkout_receipt' => $quote,
            ]);
            foreach ($quote['lines'] as $line) {
                $updated = Product::whereKey($line['product_id'])->where('stock', '>=', $line['quantity'])->decrement('stock', $line['quantity']);
                if (!$updated) throw ValidationException::withMessages(['cart' => 'Stock changed. Refresh the sale and try again.']);
                if ($line['variant_id']) {
                    $updated = ProductVariant::whereKey($line['variant_id'])->where('stock', '>=', $line['quantity'])->decrement('stock', $line['quantity']);
                    if (!$updated) throw ValidationException::withMessages(['cart' => 'Variant stock changed. Refresh the sale and try again.']);
                }
                $order->items()->create(array_intersect_key($line, array_flip(['product_id', 'variant_id', 'quantity', 'unit_price', 'total'])));
            }
            return $order;
        }, 3);
        return $this->replay($order, $hash);
    }

    private function replay(Order $order, string $hash)
    {
        abort_unless(hash_equals($order->checkout_hash, $hash), 409, 'This checkout key belongs to a different request.');
        return response()->json([
            'success' => true, 'order_id' => $order->order_number,
            'status' => $order->status, 'payment_status' => $order->payment_status,
            'total' => $order->checkout_receipt['total'], 'receipt' => $order->checkout_receipt,
            'message' => 'Order received. Payment has not been collected.',
        ]);
    }
}
