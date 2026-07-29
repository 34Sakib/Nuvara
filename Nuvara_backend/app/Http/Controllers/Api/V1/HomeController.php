<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\TrustFeature;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->header('Accept-Language', 'en');

        $formatProduct = function ($prod) use ($locale) {
            $imageUrls = $prod->images->pluck('path')->toArray();
            if (empty($imageUrls)) {
                $imageUrls = ['https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80'];
            }

            return [
                'id' => $prod->id,
                'sku' => $prod->sku,
                'slug' => $prod->slug,
                'name' => $prod->getLocalized('name', $locale),
                'description' => $prod->getLocalized('description', $locale),
                'price' => (float)$prod->price,
                'compare_price' => $prod->compare_price ? (float)$prod->compare_price : null,
                'stock' => $prod->stock,
                'rating' => (float)$prod->avg_rating,
                'reviewCount' => $prod->review_count,
                'isBestSeller' => (bool)$prod->is_best_seller,
                'isNew' => (bool)$prod->is_new,
                'brand' => $prod->brand ? $prod->brand->name : 'Nuvara',
                'category' => $prod->category ? $prod->category->getLocalized('name', $locale) : '',
                'images' => $imageUrls
            ];
        };

        // 1. Hero Sliders
        $heroSlides = Banner::with(['product.images'])
            ->where('type', 'hero_slider')
            ->where('status', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($banner) use ($locale) {
                return [
                    'id' => $banner->id,
                    'badge' => $banner->badge,
                    'badgeText' => $banner->badge_text,
                    'headline' => $banner->getLocalized('headline', $locale),
                    'sub' => $banner->getLocalized('sub', $locale),
                    'buttonText' => $banner->getLocalized('button_text', $locale),
                    'link' => $banner->link ?: ($banner->product ? '/product/' . $banner->product->slug : '#'),
                    'product' => $banner->product ? [
                        'id' => $banner->product->id,
                        'name' => $banner->product->getLocalized('name', $locale),
                        'slug' => $banner->product->slug,
                        'price' => (float)$banner->product->price,
                        'images' => $banner->product->images->pluck('path')->toArray()
                    ] : null,
                    'image' => $banner->image,
                    'bgGradient' => $banner->bg_gradient ?: 'from-[#FDE047] via-[#FACC15] to-[#EAB308]',
                    'textColor' => $banner->text_color ?: 'text-gray-950',
                    'badgeBg' => $banner->badge_bg ?: 'bg-white text-black font-black uppercase tracking-wider px-3.5 py-1.5 rounded-md shadow-md text-xs sm:text-sm inline-block',
                    'btnStyle' => $banner->btn_style ?: 'bg-black text-white hover:bg-gray-800 shadow-2xl border-none font-black uppercase tracking-wider px-8 py-3.5 rounded-xl text-sm'
                ];
            });

        // 2. Trust Features
        $trustFeatures = TrustFeature::where('status', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($feat) use ($locale) {
                return [
                    'id' => $feat->id,
                    'key' => $feat->feature_key,
                    'title' => $feat->getLocalized('title', $locale),
                    'sub' => $feat->getLocalized('sub', $locale),
                    'icon' => $feat->icon,
                    'iconColor' => $feat->icon_color,
                    'bgColor' => $feat->bg_color
                ];
            });

        // 3. Categories
        $categories = Category::where('status', 'active')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($cat) use ($locale) {
                return [
                    'id' => $cat->id,
                    'slug' => $cat->slug,
                    'name' => $cat->getLocalized('name', $locale),
                    'image' => $cat->image
                ];
            });

        // 4. Flash Sale
        $flashSale = FlashSale::where('status', true)->latest()->first();
        $flashProducts = Product::with(['images', 'category', 'brand'])
            ->where('status', 'active')
            ->where('is_flash_deal', true)
            ->take(4)
            ->get()
            ->map($formatProduct);

        // 5. Featured Products (Best Sellers & New Arrivals)
        $bestSellers = Product::with(['images', 'category', 'brand'])
            ->where('status', 'active')
            ->where('is_best_seller', true)
            ->get()
            ->map($formatProduct);

        $newArrivals = Product::with(['images', 'category', 'brand'])
            ->where('status', 'active')
            ->where('is_new', true)
            ->get()
            ->map($formatProduct);

        // 6. Promotional Banner
        $promoBannerModel = Banner::where('type', 'promo_banner')
            ->where('status', true)
            ->orderBy('sort_order')
            ->first();

        $promoBanner = $promoBannerModel ? [
            'id' => $promoBannerModel->id,
            'badge' => $promoBannerModel->badge ?: 'Limited Edition',
            'headline' => $promoBannerModel->getLocalized('headline', $locale),
            'sub' => $promoBannerModel->getLocalized('sub', $locale),
            'buttonText' => $promoBannerModel->getLocalized('button_text', $locale) ?: 'Explore Collection',
            'link' => $promoBannerModel->link ?: '/category/home-living',
            'image' => $promoBannerModel->image
        ] : null;

        // 7. Testimonials
        $testimonials = Testimonial::where('status', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($t) use ($locale) {
                return [
                    'id' => $t->id,
                    'name' => $t->name,
                    'rating' => $t->rating,
                    'quote' => $t->getLocalized('quote', $locale),
                    'avatar' => $t->avatar
                ];
            });

        return response()->json([
            'hero_slides' => $heroSlides,
            'trust_features' => $trustFeatures,
            'categories' => $categories,
            'flash_sale' => $flashSale ? [
                'id' => $flashSale->id,
                'title' => $flashSale->getLocalized('title', $locale),
                'ends_at' => $flashSale->ends_at ? $flashSale->ends_at->toIso8601String() : now()->addHours(4)->toIso8601String(),
                'discount_label' => $flashSale->discount_label,
            ] : null,
            'flash_products' => $flashProducts,
            'best_sellers' => $bestSellers,
            'new_arrivals' => $newArrivals,
            'promo_banner' => $promoBanner,
            'testimonials' => $testimonials
        ]);
    }
}
