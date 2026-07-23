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

Route::prefix('v1')->group(function () {
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
    Route::post('/checkout', [OrderController::class, 'checkout']);

    // Authentication
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::put('/me', [AuthController::class, 'updateProfile']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Wishlist
        Route::post('/wishlist/{product}', [WishlistController::class, 'toggle']);

        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store']);
    });
});
