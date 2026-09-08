<?php
namespace App\Services;
use Illuminate\Validation\ValidationException;
final class Money {
    public static function cents(string|int|float $value): int {
        $value = (string) $value;
        if (!preg_match('/^\d{1,8}(?:\.\d{1,2})?$/D', $value)) {
            throw ValidationException::withMessages(['cart' => 'A price is invalid. Please contact the store.']);
        }
        [$whole, $fraction] = array_pad(explode('.', $value), 2, '');
        return ((int) $whole * 100) + (int) str_pad($fraction, 2, '0');
    }
    public static function decimal(int $cents): string {
        return intdiv($cents, 100).'.'.str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);
    }
}
