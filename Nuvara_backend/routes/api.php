<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\WishlistController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\NewsletterController;
use App\Http\Controllers\Api\V1\CouponController;
use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\PageController;

Route::prefix('v1')->group(function () {
    // Dynamic Homepage aggregate endpoint
    Route::get('/home', [HomeController::class, 'index']);

    // Dynamic Pages
    Route::get('/pages/about', [PageController::class, 'about']);
    Route::get('/pages/contact', [PageController::class, 'contact']);
    Route::get('/pages/faq', [PageController::class, 'faq']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);

    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);
    Route::get('/products/{slug}/related', [ProductController::class, 'related']);
    Route::get('/search', [ProductController::class, 'index']);

    // Newsletter subscription
    Route::post('/newsletter', [NewsletterController::class, 'store']);

    // Validate Coupon
    Route::post('/coupons/validate', [CouponController::class, 'validateCoupon']);

    // Checkout (supports guest checkouts and authenticated checkouts)
    Route::post('/checkout/quote', [OrderController::class, 'quote'])->middleware('throttle:60,1');
    Route::post('/checkout', [OrderController::class, 'checkout'])->middleware('throttle:30,1');

    // Authentication
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::put('/me', [AuthController::class, 'updateProfile']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Addresses
        Route::get('/addresses', [AddressController::class, 'index']);
        Route::post('/addresses', [AddressController::class, 'store']);
        Route::put('/addresses/{id}', [AddressController::class, 'update']);
        Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);

        // Wishlist
        Route::post('/wishlist/{product}', [WishlistController::class, 'toggle']);

        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store']);
    });
});
