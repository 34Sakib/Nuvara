<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function toggle(Request $request, $productId)
    {
        $userId = $request->user()->id;
        
        $exists = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();
        
        if ($exists) {
            $exists->delete();
            return response()->json(['status' => 'removed', 'message' => 'Product removed from wishlist']);
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            return response()->json(['status' => 'added', 'message' => 'Product added to wishlist']);
        }
    }
}
