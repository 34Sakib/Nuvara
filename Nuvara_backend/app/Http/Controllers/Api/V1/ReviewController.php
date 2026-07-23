<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'name' => 'required|string|max:255',
        ]);

        $userId = $request->user() ? $request->user()->id : null;

        $review = Review::create([
            'product_id' => $request->product_id,
            'user_id' => $userId,
            'rating' => $request->rating,
            'comment' => [
                'en' => $request->comment,
                'es' => $request->comment,
                'ar' => $request->comment,
                'bn' => $request->comment,
                'author' => $request->name
            ],
            'images' => [],
            'is_approved' => true
        ]);

        $product = Product::find($request->product_id);
        $avgRating = Review::where('product_id', $product->id)->where('is_approved', true)->avg('rating');
        $reviewCount = Review::where('product_id', $product->id)->where('is_approved', true)->count();
        
        $product->update([
            'avg_rating' => round($avgRating ?: 5.0, 1),
            'review_count' => $reviewCount
        ]);

        return response()->json([
            'success' => true,
            'review' => [
                'id' => $review->id,
                'name' => $request->name,
                'rating' => $review->rating,
                'date' => $review->created_at->format('Y-m-d'),
                'comment' => $review->comment
            ]
        ]);
    }
}
