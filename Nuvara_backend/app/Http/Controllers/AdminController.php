<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Banner;
use App\Models\TrustFeature;
use App\Models\FlashSale;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    private function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    }

    public function loginForm()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('welcome');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return redirect()->route('admin.login')->with('error', 'Unauthorized access level. Administrator required.');
            }
        }

        return redirect()->route('admin.login')->with('error', 'Invalid admin credentials');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

    public function dashboard()
    {
        $this->checkAdmin();
        $usersCount = User::count();
        $productsCount = Product::count();
        $categoriesCount = Category::count();
        $ordersCount = Order::count();
        
        $liveRevenue = Order::sum('total');
        $revenueTotal = $ordersCount > 0 ? $liveRevenue : 15840.00;

        // Day Stats
        $dayLabels = [];
        $dayValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('M d');
            $dayLabels[] = $dateStr;
            $sum = Order::whereDate('created_at', now()->subDays($i)->toDateString())->sum('total');
            if ($ordersCount == 0) {
                $sum = [150, 320, 280, 450, 310, 620, 580][6 - $i];
            }
            $dayValues[] = (float)$sum;
        }

        // Month Stats
        $monthLabels = [];
        $monthValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $dateStr = now()->subMonths($i)->format('M Y');
            $monthLabels[] = $dateStr;
            $sum = Order::whereYear('created_at', now()->subMonths($i)->year)
                        ->whereMonth('created_at', now()->subMonths($i)->month)
                        ->sum('total');
            if ($ordersCount == 0) {
                $sum = [1200, 2400, 1800, 4500, 3100, 5800][5 - $i];
            }
            $monthValues[] = (float)$sum;
        }

        // Year Stats
        $yearLabels = [];
        $yearValues = [];
        for ($i = 2; $i >= 0; $i--) {
            $dateStr = now()->subYears($i)->format('Y');
            $yearLabels[] = $dateStr;
            $sum = Order::whereYear('created_at', now()->subYears($i)->year)->sum('total');
            if ($ordersCount == 0) {
                $sum = [24000, 38000, 54000][2 - $i];
            }
            $yearValues[] = (float)$sum;
        }

        return view('admin.dashboard', compact(
            'usersCount', 
            'productsCount', 
            'categoriesCount', 
            'ordersCount', 
            'revenueTotal',
            'dayLabels',
            'dayValues',
            'monthLabels',
            'monthValues',
            'yearLabels',
            'yearValues'
        ));
    }

    // --- PRODUCTS ---
    public function products()
    {
        $this->checkAdmin();
        $products = Product::with(['category', 'images'])->orderBy('created_at', 'desc')->get();
        $categories = Category::all();
        return view('admin.products', compact('products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_bn' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        $imagePath = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads', $filename, 'public');
            $imagePath = '/storage/uploads/' . $filename;
        }

        $product = Product::create([
            'name' => [
                'en' => $request->name_en,
                'bn' => $request->name_bn,
                'es' => $request->name_en,
                'ar' => $request->name_en
            ],
            'description' => [
                'en' => 'Premium product created from Admin Panel.',
                'bn' => 'অ্যাডমিন প্যানেল থেকে তৈরি প্রিমিয়াম পণ্য।',
                'es' => 'Premium product.',
                'ar' => 'Premium product.'
            ],
            'slug' => $request->slug,
            'price' => $request->price,
            'stock' => $request->stock,
            'sku' => $request->sku,
            'category_id' => $request->category_id,
            'brand_id' => 1,
            'status' => 'active',
            'avg_rating' => 5.0,
            'review_count' => 0,
            'is_best_seller' => $request->has('is_best_seller'),
            'is_new' => $request->has('is_new'),
            'is_flash_deal' => $request->has('is_flash_deal')
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'path' => $imagePath,
            'sort_order' => 1
        ]);

        return redirect()->route('admin.products')->with('success', 'Product created successfully.');
    }

    public function updateProduct(Request $request, $id)
    {
        $this->checkAdmin();
        $product = Product::findOrFail($id);

        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_bn' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        $product->update([
            'name' => [
                'en' => $request->name_en,
                'bn' => $request->name_bn,
                'es' => $request->name_en,
                'ar' => $request->name_en
            ],
            'slug' => $request->slug,
            'price' => $request->price,
            'stock' => $request->stock,
            'sku' => $request->sku,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'is_best_seller' => $request->has('is_best_seller'),
            'is_new' => $request->has('is_new'),
            'is_flash_deal' => $request->has('is_flash_deal')
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads', $filename, 'public');
            $imagePath = '/storage/uploads/' . $filename;

            $img = ProductImage::where('product_id', $product->id)->first();
            if ($img) {
                $img->update(['path' => $imagePath]);
            } else {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $imagePath,
                    'sort_order' => 1
                ]);
            }
        }

        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }

    public function deleteProduct($id)
    {
        $this->checkAdmin();
        $product = Product::findOrFail($id);
        $product->images()->delete();
        $product->variants()->delete();
        $product->reviews()->delete();
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }

    // --- CATEGORIES ---
    public function categories()
    {
        $this->checkAdmin();
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_bn' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        $imagePath = '';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads', $filename, 'public');
            $imagePath = '/storage/uploads/' . $filename;
        }

        Category::create([
            'name' => [
                'en' => $request->name_en,
                'bn' => $request->name_bn,
                'es' => $request->name_en,
                'ar' => $request->name_en
            ],
            'slug' => $request->slug,
            'image' => $imagePath,
            'status' => 'active',
            'sort_order' => Category::count() + 1
        ]);

        return redirect()->route('admin.categories')->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, $id)
    {
        $this->checkAdmin();
        $category = Category::findOrFail($id);

        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_bn' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug,' . $category->id,
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        $data = [
            'name' => [
                'en' => $request->name_en,
                'bn' => $request->name_bn,
                'es' => $request->name_en,
                'ar' => $request->name_en
            ],
            'slug' => $request->slug,
            'status' => $request->status
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads', $filename, 'public');
            $data['image'] = '/storage/uploads/' . $filename;
        }

        $category->update($data);

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully.');
    }

    public function deleteCategory($id)
    {
        $this->checkAdmin();
        $category = Category::findOrFail($id);
        Product::where('category_id', $category->id)->update(['category_id' => null]);
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully.');
    }

    // --- BANNERS / SLIDERS ---
    public function banners()
    {
        $this->checkAdmin();
        $banners = Banner::orderBy('sort_order')->get();
        $products = Product::where('status', 'active')->get();
        return view('admin.banners', compact('banners', 'products'));
    }

    public function storeBanner(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'type' => 'required|in:hero_slider,promo_banner',
            'title_en' => 'required|string|max:255',
            'headline_en' => 'required|string|max:255',
            'sub_en' => 'required|string',
            'button_text_en' => 'required|string',
            'link' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        $imagePath = $request->input('image_url', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80');
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads', $filename, 'public');
            $imagePath = '/storage/uploads/' . $filename;
        }

        Banner::create([
            'type' => $request->type,
            'title' => ['en' => $request->title_en, 'bn' => $request->title_en],
            'badge' => $request->badge ?: 'EXCLUSIVE',
            'badge_text' => $request->badge_text ?: 'Sale',
            'headline' => ['en' => $request->headline_en, 'bn' => $request->headline_en],
            'sub' => ['en' => $request->sub_en, 'bn' => $request->sub_en],
            'button_text' => ['en' => $request->button_text_en, 'bn' => $request->button_text_en],
            'link' => $request->link,
            'bg_gradient' => $request->bg_gradient ?: 'from-[#FDE047] via-[#FACC15] to-[#EAB308]',
            'text_color' => $request->text_color ?: 'text-gray-950',
            'image' => $imagePath,
            'product_id' => $request->product_id ?: null,
            'sort_order' => Banner::count() + 1,
            'status' => true
        ]);

        return redirect()->route('admin.banners')->with('success', 'Banner/Slider created successfully.');
    }

    public function updateBanner(Request $request, $id)
    {
        $this->checkAdmin();
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title_en' => 'required|string|max:255',
            'headline_en' => 'required|string|max:255',
            'sub_en' => 'required|string',
            'button_text_en' => 'required|string',
        ]);

        $data = [
            'type' => $request->type ?: $banner->type,
            'title' => ['en' => $request->title_en, 'bn' => $request->title_en],
            'badge' => $request->badge,
            'badge_text' => $request->badge_text,
            'headline' => ['en' => $request->headline_en, 'bn' => $request->headline_en],
            'sub' => ['en' => $request->sub_en, 'bn' => $request->sub_en],
            'button_text' => ['en' => $request->button_text_en, 'bn' => $request->button_text_en],
            'link' => $request->link,
            'bg_gradient' => $request->bg_gradient ?: $banner->bg_gradient,
            'text_color' => $request->text_color ?: $banner->text_color,
            'product_id' => $request->product_id ?: null,
            'status' => $request->has('status')
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('uploads', $filename, 'public');
            $data['image'] = '/storage/uploads/' . $filename;
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        $banner->update($data);

        return redirect()->route('admin.banners')->with('success', 'Banner updated successfully.');
    }

    public function deleteBanner($id)
    {
        $this->checkAdmin();
        Banner::findOrFail($id)->delete();
        return redirect()->route('admin.banners')->with('success', 'Banner deleted successfully.');
    }

    // --- TRUST FEATURES ---
    public function features()
    {
        $this->checkAdmin();
        $features = TrustFeature::orderBy('sort_order')->get();
        return view('admin.features', compact('features'));
    }

    public function storeFeature(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'feature_key' => 'required|string|unique:trust_features,feature_key',
            'title_en' => 'required|string',
            'sub_en' => 'required|string',
            'icon' => 'required|string'
        ]);

        TrustFeature::create([
            'feature_key' => strtolower(str_replace(' ', '_', $request->feature_key)),
            'title' => ['en' => $request->title_en, 'bn' => $request->title_en],
            'sub' => ['en' => $request->sub_en, 'bn' => $request->sub_en],
            'icon' => $request->icon,
            'icon_color' => $request->icon_color ?: 'text-emerald-500',
            'bg_color' => $request->bg_color ?: 'bg-emerald-500/10',
            'sort_order' => TrustFeature::count() + 1,
            'status' => true
        ]);

        return redirect()->route('admin.features')->with('success', 'Trust feature created successfully.');
    }

    public function updateFeature(Request $request, $id)
    {
        $this->checkAdmin();
        $feature = TrustFeature::findOrFail($id);

        $request->validate([
            'title_en' => 'required|string',
            'sub_en' => 'required|string',
            'icon' => 'required|string'
        ]);

        $feature->update([
            'title' => ['en' => $request->title_en, 'bn' => $request->title_en],
            'sub' => ['en' => $request->sub_en, 'bn' => $request->sub_en],
            'icon' => $request->icon,
            'icon_color' => $request->icon_color ?: $feature->icon_color,
            'bg_color' => $request->bg_color ?: $feature->bg_color,
            'status' => $request->has('status')
        ]);

        return redirect()->route('admin.features')->with('success', 'Trust feature updated successfully.');
    }

    public function deleteFeature($id)
    {
        $this->checkAdmin();
        TrustFeature::findOrFail($id)->delete();
        return redirect()->route('admin.features')->with('success', 'Trust feature deleted successfully.');
    }

    // --- FLASH SALE ---
    public function flashSale()
    {
        $this->checkAdmin();
        $flashSale = FlashSale::latest()->first();
        $flashProducts = Product::where('is_flash_deal', true)->get();
        $allProducts = Product::where('status', 'active')->get();
        return view('admin.flash_sale', compact('flashSale', 'flashProducts', 'allProducts'));
    }

    public function updateFlashSale(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'title_en' => 'required|string',
            'ends_at' => 'required|date',
            'discount_label' => 'nullable|string'
        ]);

        $flashSale = FlashSale::latest()->first();
        if (!$flashSale) {
            $flashSale = new FlashSale();
        }

        $flashSale->fill([
            'title' => ['en' => $request->title_en, 'bn' => $request->title_en],
            'ends_at' => $request->ends_at,
            'discount_label' => $request->discount_label ?: 'Up to 30% OFF',
            'status' => $request->has('status')
        ])->save();

        // Update selected products as flash deals
        if ($request->has('product_ids')) {
            Product::query()->update(['is_flash_deal' => false]);
            Product::whereIn('id', $request->product_ids)->update(['is_flash_deal' => true]);
        }

        return redirect()->route('admin.flash-sale')->with('success', 'Flash Sale campaign updated successfully.');
    }

    // --- TESTIMONIALS ---
    public function testimonials()
    {
        $this->checkAdmin();
        $testimonials = Testimonial::orderBy('sort_order')->get();
        return view('admin.testimonials', compact('testimonials'));
    }

    public function storeTestimonial(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'quote_en' => 'required|string'
        ]);

        Testimonial::create([
            'name' => $request->name,
            'rating' => $request->rating,
            'quote' => ['en' => $request->quote_en, 'bn' => $request->quote_en],
            'is_featured' => true,
            'sort_order' => Testimonial::count() + 1,
            'status' => true
        ]);

        return redirect()->route('admin.testimonials')->with('success', 'Testimonial created successfully.');
    }

    public function updateTestimonial(Request $request, $id)
    {
        $this->checkAdmin();
        $testimonial = Testimonial::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'quote_en' => 'required|string'
        ]);

        $testimonial->update([
            'name' => $request->name,
            'rating' => $request->rating,
            'quote' => ['en' => $request->quote_en, 'bn' => $request->quote_en],
            'is_featured' => $request->has('is_featured'),
            'status' => $request->has('status')
        ]);

        return redirect()->route('admin.testimonials')->with('success', 'Testimonial updated successfully.');
    }

    public function deleteTestimonial($id)
    {
        $this->checkAdmin();
        Testimonial::findOrFail($id)->delete();
        return redirect()->route('admin.testimonials')->with('success', 'Testimonial deleted successfully.');
    }

    // --- ORDERS ---
    public function orders()
    {
        $this->checkAdmin();
        $orders = Order::with('items.product')->orderBy('created_at', 'desc')->get();
        return view('admin.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $this->checkAdmin();
        $request->validate([
            'status' => 'required|in:processing,shipped,delivered'
        ]);

        Order::findOrFail($id)->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.orders')->with('success', 'Order status updated successfully.');
    }

    // --- COUPONS ---
    public function coupons()
    {
        $this->checkAdmin();
        $coupons = Coupon::all();
        return view('admin.coupons', compact('coupons'));
    }

    public function storeCoupon(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:percent,flat,free_shipping',
            'value' => 'required|numeric|min:0',
            'min_order' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
            'status' => 'required|in:active,inactive'
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'min_order' => $request->min_order,
            'expires_at' => $request->expires_at,
            'status' => $request->status
        ]);

        return redirect()->route('admin.coupons')->with('success', 'Coupon created successfully.');
    }

    public function updateCoupon(Request $request, $id)
    {
        $this->checkAdmin();
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percent,flat,free_shipping',
            'value' => 'required|numeric|min:0',
            'min_order' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
            'status' => 'required|in:active,inactive'
        ]);

        $coupon->update([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'min_order' => $request->min_order,
            'expires_at' => $request->expires_at,
            'status' => $request->status
        ]);

        return redirect()->route('admin.coupons')->with('success', 'Coupon updated successfully.');
    }

    public function deleteCoupon($id)
    {
        $this->checkAdmin();
        Coupon::findOrFail($id)->delete();
        return redirect()->route('admin.coupons')->with('success', 'Coupon deleted successfully.');
    }

    // --- USERS ---
    public function users()
    {
        $this->checkAdmin();
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'password' => 'required|string|min:4',
            'role' => 'required|in:admin,user'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'locale_preference' => 'en',
            'theme_preference' => 'dark'
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function updateUser(Request $request, $id)
    {
        $this->checkAdmin();
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:4'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    // --- PROFILE ---
    public function profile()
    {
        $this->checkAdmin();
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $this->checkAdmin();
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'password' => 'nullable|string|min:4|confirmed'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }

    // --- DYNAMIC PAGES: ABOUT ---
    public function adminAbout()
    {
        $this->checkAdmin();
        $page = \App\Models\PageContent::firstOrCreate(
            ['page_key' => 'about'],
            ['content' => []]
        );
        $content = $page->content ?? [];
        return view('admin.pages.about', compact('content'));
    }

    public function updateAbout(Request $request)
    {
        $this->checkAdmin();
        $page = \App\Models\PageContent::where('page_key', 'about')->firstOrFail();

        $content = [
            'hero_badge' => ['en' => $request->hero_badge_en, 'bn' => $request->hero_badge_bn],
            'hero_title' => ['en' => $request->hero_title_en, 'bn' => $request->hero_title_bn],
            'hero_subtitle' => ['en' => $request->hero_subtitle_en, 'bn' => $request->hero_subtitle_bn],
            'story_title' => ['en' => $request->story_title_en, 'bn' => $request->story_title_bn],
            'story_body' => ['en' => $request->story_body_en, 'bn' => $request->story_body_bn],
            'stats' => [
                ['label' => ['en' => $request->stat1_label_en, 'bn' => $request->stat1_label_bn], 'value' => $request->stat1_val, 'icon' => 'Users', 'color' => 'text-amber-500 bg-amber-500/10'],
                ['label' => ['en' => $request->stat2_label_en, 'bn' => $request->stat2_label_bn], 'value' => $request->stat2_val, 'icon' => 'Award', 'color' => 'text-emerald-500 bg-emerald-500/10'],
                ['label' => ['en' => $request->stat3_label_en, 'bn' => $request->stat3_label_bn], 'value' => $request->stat3_val, 'icon' => 'Globe', 'color' => 'text-indigo-500 bg-indigo-500/10'],
                ['label' => ['en' => $request->stat4_label_en, 'bn' => $request->stat4_label_bn], 'value' => $request->stat4_val, 'icon' => 'ShieldCheck', 'color' => 'text-rose-500 bg-rose-500/10']
            ],
            'team' => [
                [
                    'name' => $request->team1_name,
                    'role' => ['en' => $request->team1_role_en, 'bn' => $request->team1_role_bn],
                    'image' => $request->team1_image ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80',
                    'bio' => ['en' => $request->team1_bio_en, 'bn' => $request->team1_bio_bn]
                ],
                [
                    'name' => $request->team2_name,
                    'role' => ['en' => $request->team2_role_en, 'bn' => $request->team2_role_bn],
                    'image' => $request->team2_image ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop&q=80',
                    'bio' => ['en' => $request->team2_bio_en, 'bn' => $request->team2_bio_bn]
                ],
                [
                    'name' => $request->team3_name,
                    'role' => ['en' => $request->team3_role_en, 'bn' => $request->team3_role_bn],
                    'image' => $request->team3_image ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&auto=format&fit=crop&q=80',
                    'bio' => ['en' => $request->team3_bio_en, 'bn' => $request->team3_bio_bn]
                ]
            ]
        ];

        $page->update(['content' => $content]);

        return redirect()->route('admin.pages.about')->with('success', 'About Us page updated successfully.');
    }

    // --- DYNAMIC PAGES: CONTACT ---
    public function adminContact()
    {
        $this->checkAdmin();
        $page = \App\Models\PageContent::firstOrCreate(
            ['page_key' => 'contact'],
            ['content' => []]
        );
        $content = $page->content ?? [];
        return view('admin.pages.contact', compact('content'));
    }

    public function updateContact(Request $request)
    {
        $this->checkAdmin();
        $page = \App\Models\PageContent::where('page_key', 'contact')->firstOrFail();

        $content = [
            'hero_badge' => ['en' => $request->hero_badge_en, 'bn' => $request->hero_badge_bn],
            'hero_title' => ['en' => $request->hero_title_en, 'bn' => $request->hero_title_bn],
            'hero_subtitle' => ['en' => $request->hero_subtitle_en, 'bn' => $request->hero_subtitle_bn],
            'cards' => [
                [
                    'title' => ['en' => 'Customer Support', 'bn' => 'কাস্টমার সাপোর্ট'],
                    'value' => $request->email_val,
                    'sub' => ['en' => $request->email_sub_en, 'bn' => $request->email_sub_bn],
                    'icon' => 'Mail',
                    'color' => 'text-blue-500 bg-blue-500/10'
                ],
                [
                    'title' => ['en' => 'Direct Hotline', 'bn' => 'হটলাইন নম্বর'],
                    'value' => $request->phone_val,
                    'sub' => ['en' => $request->phone_sub_en, 'bn' => $request->phone_sub_bn],
                    'icon' => 'Phone',
                    'color' => 'text-emerald-500 bg-emerald-500/10'
                ],
                [
                    'title' => ['en' => 'Headquarters', 'bn' => 'প্রধান কার্যালয়'],
                    'value' => $request->address_val,
                    'sub' => ['en' => $request->address_sub_en, 'bn' => $request->address_sub_bn],
                    'icon' => 'MapPin',
                    'color' => 'text-indigo-500 bg-indigo-500/10'
                ]
            ]
        ];

        $page->update(['content' => $content]);

        return redirect()->route('admin.pages.contact')->with('success', 'Contact page updated successfully.');
    }

    // --- DYNAMIC PAGES: FAQS ---
    public function adminFaqs()
    {
        $this->checkAdmin();
        $faqs = \App\Models\Faq::orderBy('sort_order')->get();
        return view('admin.faqs', compact('faqs'));
    }

    public function storeFaq(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'category' => 'required|string',
            'question_en' => 'required|string',
            'question_bn' => 'required|string',
            'answer_en' => 'required|string',
            'answer_bn' => 'required|string',
        ]);

        \App\Models\Faq::create([
            'category' => $request->category,
            'question' => ['en' => $request->question_en, 'bn' => $request->question_bn],
            'answer' => ['en' => $request->answer_en, 'bn' => $request->answer_bn],
            'sort_order' => $request->input('sort_order', 0),
            'status' => $request->has('status')
        ]);

        return redirect()->route('admin.faqs')->with('success', 'FAQ added successfully.');
    }

    public function updateFaq(Request $request, $id)
    {
        $this->checkAdmin();
        $faq = \App\Models\Faq::findOrFail($id);

        $request->validate([
            'category' => 'required|string',
            'question_en' => 'required|string',
            'question_bn' => 'required|string',
            'answer_en' => 'required|string',
            'answer_bn' => 'required|string',
        ]);

        $faq->update([
            'category' => $request->category,
            'question' => ['en' => $request->question_en, 'bn' => $request->question_bn],
            'answer' => ['en' => $request->answer_en, 'bn' => $request->answer_bn],
            'sort_order' => $request->input('sort_order', 0),
            'status' => $request->has('status')
        ]);

        return redirect()->route('admin.faqs')->with('success', 'FAQ updated successfully.');
    }

    public function deleteFaq($id)
    {
        $this->checkAdmin();
        $faq = \App\Models\Faq::findOrFail($id);
        $faq->delete();
        return redirect()->route('admin.faqs')->with('success', 'FAQ deleted successfully.');
    }
}

