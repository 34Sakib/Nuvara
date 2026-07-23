<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'locale_preference' => $request->input('locale_preference', 'en'),
            'theme_preference' => $request->input('theme_preference', 'light'),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load(['addresses', 'orders.items.product', 'wishlists.product.brand', 'wishlists.product.images']);
        
        $wishlistProducts = $user->wishlists->map(function ($wish) {
            if (!$wish->product) return null;
            $imageUrls = $wish->product->images->pluck('path')->toArray();
            if (empty($imageUrls)) {
                $imageUrls = ['https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80'];
            }
            $pArray = $wish->product->toArray();
            $pArray['images'] = $imageUrls;
            $pArray['brand'] = $wish->product->brand->name;
            return $pArray;
        })->filter()->values();

        $formattedOrders = $user->orders->map(function ($order) {
            return [
                'id' => $order->order_number,
                'date' => $order->created_at->format('Y-m-d'),
                'status' => $order->status,
                'total' => (float)$order->total,
                'itemsCount' => $order->items->sum('quantity'),
                'items' => $order->items->map(function ($item) {
                    return [
                        'name' => $item->product ? $item->product->name : 'Product',
                        'price' => (float)$item->unit_price,
                        'quantity' => $item->quantity
                    ];
                })
            ];
        });

        $userArray = $user->toArray();
        $userArray['wishlist'] = $wishlistProducts;
        $userArray['orders'] = $formattedOrders;

        return response()->json($userArray);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'locale_preference' => 'nullable|string',
            'theme_preference' => 'nullable|string',
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'locale_preference', 'theme_preference']));

        return response()->json($user);
    }
}
