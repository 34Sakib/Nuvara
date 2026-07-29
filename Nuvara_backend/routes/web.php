<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

// 1. Root / Login Routes
Route::get('/', [AdminController::class, 'loginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// 2. Admin Dashboard & Subsections (Guarded under Admin Web Middleware)
Route::middleware(['web'])->group(function () {
    // Dashboard Overview
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Products Management CRUD
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::post('/admin/products/store', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::post('/admin/products/{id}/update', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::post('/admin/products/{id}/delete', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');

    // Categories Management CRUD
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/admin/categories/store', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::post('/admin/categories/{id}/update', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::post('/admin/categories/{id}/delete', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');

    // Banners & Sliders CRUD
    Route::get('/admin/banners', [AdminController::class, 'banners'])->name('admin.banners');
    Route::post('/admin/banners/store', [AdminController::class, 'storeBanner'])->name('admin.banners.store');
    Route::post('/admin/banners/{id}/update', [AdminController::class, 'updateBanner'])->name('admin.banners.update');
    Route::post('/admin/banners/{id}/delete', [AdminController::class, 'deleteBanner'])->name('admin.banners.delete');

    // Trust Features CRUD
    Route::get('/admin/features', [AdminController::class, 'features'])->name('admin.features');
    Route::post('/admin/features/store', [AdminController::class, 'storeFeature'])->name('admin.features.store');
    Route::post('/admin/features/{id}/update', [AdminController::class, 'updateFeature'])->name('admin.features.update');
    Route::post('/admin/features/{id}/delete', [AdminController::class, 'deleteFeature'])->name('admin.features.delete');

    // Flash Sale Campaign
    Route::get('/admin/flash-sale', [AdminController::class, 'flashSale'])->name('admin.flash-sale');
    Route::post('/admin/flash-sale/update', [AdminController::class, 'updateFlashSale'])->name('admin.flash-sale.update');

    // Customer Testimonials CRUD
    Route::get('/admin/testimonials', [AdminController::class, 'testimonials'])->name('admin.testimonials');
    Route::post('/admin/testimonials/store', [AdminController::class, 'storeTestimonial'])->name('admin.testimonials.store');
    Route::post('/admin/testimonials/{id}/update', [AdminController::class, 'updateTestimonial'])->name('admin.testimonials.update');
    Route::post('/admin/testimonials/{id}/delete', [AdminController::class, 'deleteTestimonial'])->name('admin.testimonials.delete');

    // Dynamic Pages Management: About Us & Contact
    Route::get('/admin/pages/about', [AdminController::class, 'adminAbout'])->name('admin.pages.about');
    Route::post('/admin/pages/about/update', [AdminController::class, 'updateAbout'])->name('admin.pages.about.update');

    Route::get('/admin/pages/contact', [AdminController::class, 'adminContact'])->name('admin.pages.contact');
    Route::post('/admin/pages/contact/update', [AdminController::class, 'updateContact'])->name('admin.pages.contact.update');

    // FAQs Management CRUD
    Route::get('/admin/faqs', [AdminController::class, 'adminFaqs'])->name('admin.faqs');
    Route::post('/admin/faqs/store', [AdminController::class, 'storeFaq'])->name('admin.faqs.store');
    Route::post('/admin/faqs/{id}/update', [AdminController::class, 'updateFaq'])->name('admin.faqs.update');
    Route::post('/admin/faqs/{id}/delete', [AdminController::class, 'deleteFaq'])->name('admin.faqs.delete');

    // Orders Tracking and Delivery Status
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::post('/admin/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');

    // Coupons CRUD
    Route::get('/admin/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
    Route::post('/admin/coupons/store', [AdminController::class, 'storeCoupon'])->name('admin.coupons.store');
    Route::post('/admin/coupons/{id}/update', [AdminController::class, 'updateCoupon'])->name('admin.coupons.update');
    Route::post('/admin/coupons/{id}/delete', [AdminController::class, 'deleteCoupon'])->name('admin.coupons.delete');

    // Users Directory
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/admin/users/store', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::post('/admin/users/{id}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');

    // Profile Settings & Password Updates
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
});
