<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images', 'variants'])->where('status', 'active');

        // Filter by Category slug
        if ($request->filled('category') && $request->category !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by Search Query
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('brand', function ($qb) use ($search) {
                      $qb->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Price Range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by Brands (expect comma-separated string or array)
        if ($request->filled('brand')) {
            $brands = is_array($request->brand) ? $request->brand : explode(',', $request->brand);
            $query->whereHas('brand', function ($q) use ($brands) {
                $q->whereIn('name', $brands)->orWhereIn('slug', $brands);
            });
        }

        // Filter by Rating
        if ($request->filled('rating')) {
            $query->where('avg_rating', '>=', $request->rating);
        }

        // Sort results
        $sort = $request->input('sort', 'popularity');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'new':
                $query->orderBy('is_new', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $query->orderBy('avg_rating', 'desc');
                break;
            case 'popularity':
            default:
                $query->orderBy('avg_rating', 'desc')->orderBy('review_count', 'desc');
                break;
        }

        $products = $query->paginate($request->input('limit', 12));
        
        $products->getCollection()->transform(function ($product) {
            $imageUrls = $product->images->pluck('path')->toArray();
            if (empty($imageUrls)) {
                $imageUrls = ['https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80'];
            }
            
            $colors = [];
            $sizes = [];
            foreach ($product->variants as $variant) {
                $attrs = $variant->attribute_set;
                if (isset($attrs['color'])) {
                    $colors[$attrs['color']] = [
                        'name' => $attrs['color'],
                        'value' => $attrs['color_val'] ?? '#000000'
                    ];
                }
                if (isset($attrs['size'])) {
                    $sizes[] = $attrs['size'];
                }
            }

            $productArray = $product->toArray();
            $productArray['images'] = $imageUrls;
            $productArray['brand'] = $product->brand->name;
            $productArray['variants'] = [
                'colors' => array_values($colors),
                'sizes' => array_values(array_unique($sizes))
            ];
            
            return $productArray;
        });

        return response()->json($products);
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'brand', 'images', 'variants', 'reviews.user'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $imageUrls = $product->images->pluck('path')->toArray();
        if (empty($imageUrls)) {
            $imageUrls = ['https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80'];
        }

        $colors = [];
        $sizes = [];
        foreach ($product->variants as $variant) {
            $attrs = $variant->attribute_set;
            if (isset($attrs['color'])) {
                $colors[$attrs['color']] = [
                    'name' => $attrs['color'],
                    'value' => $attrs['color_val'] ?? '#000000'
                ];
            }
            if (isset($attrs['size'])) {
                $sizes[] = $attrs['size'];
            }
        }

        $reviews = $product->reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'name' => $review->user ? $review->user->name : ($review->comment['author'] ?? 'Anonymous'),
                'rating' => $review->rating,
                'date' => $review->created_at->format('Y-m-d'),
                'comment' => $review->comment
            ];
        });

        $productArray = $product->toArray();
        $productArray['images'] = $imageUrls;
        $productArray['brand'] = $product->brand->name;
        $productArray['variants'] = [
            'colors' => array_values($colors),
            'sizes' => array_values(array_unique($sizes))
        ];
        $productArray['reviews'] = $reviews;

        return response()->json($productArray);
    }

    public function related($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $related = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        $formatted = $related->map(function ($prod) {
            $imageUrls = $prod->images->pluck('path')->toArray();
            if (empty($imageUrls)) {
                $imageUrls = ['https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80'];
            }

            $colors = [];
            $sizes = [];
            foreach ($prod->variants as $variant) {
                $attrs = $variant->attribute_set;
                if (isset($attrs['color'])) {
                    $colors[$attrs['color']] = [
                        'name' => $attrs['color'],
                        'value' => $attrs['color_val'] ?? '#000000'
                    ];
                }
                if (isset($attrs['size'])) {
                    $sizes[] = $attrs['size'];
                }
            }

            $prodArray = $prod->toArray();
            $prodArray['images'] = $imageUrls;
            $prodArray['brand'] = $prod->brand->name;
            $prodArray['variants'] = [
                'colors' => array_values($colors),
                'sizes' => array_values(array_unique($sizes))
            ];
            return $prodArray;
        });

        return response()->json($formatted);
    }
}
