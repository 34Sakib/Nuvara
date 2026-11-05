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
use App\Models\Faq;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

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
    public function products(Request $request)
    {
        $this->checkAdmin();
        if ($request->ajax()) {
            $products = Product::with(['category', 'images'])->select('products.*');
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('image', function ($prod) {
                    $src = $prod->images->first()->path ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80';
                    return '<img src="' . e($src) . '" alt="" class="w-14 h-14 object-cover rounded-lg border border-border/80 shadow">';
                })
                ->addColumn('details', function ($prod) {
                    $name = e($prod->getLocalized('name', 'en'));
                    $sku = e($prod->sku);
                    $statusBadge = $prod->status === 'active'
                        ? '<span class="text-[8px] font-bold bg-green-950/20 border border-green-900/30 text-green-400 px-1.5 py-0.5 rounded uppercase">Active</span>'
                        : '<span class="text-[8px] font-bold bg-red-950/20 border border-red-900/30 text-red-400 px-1.5 py-0.5 rounded uppercase">Hidden</span>';
                    $bestSellerBadge = $prod->is_best_seller
                        ? '<span class="text-[8px] font-bold bg-amber-500/10 border border-amber-500/20 text-amber-500 px-1.5 py-0.5 rounded uppercase">Best Seller</span>'
                        : '';
                    return '<div class="font-bold text-[#F5EFE4] text-sm">' . $name . '</div>'
                        . '<div class="flex items-center space-x-2 mt-1 select-none">'
                        . '<span class="text-[9px] font-mono text-gray-500 bg-black/40 border border-border px-2 py-0.5 rounded uppercase">SKU: ' . $sku . '</span>'
                        . $statusBadge . $bestSellerBadge . '</div>';
                })
                ->addColumn('category', function ($prod) {
                    $catName = $prod->category ? e($prod->category->getLocalized('name', 'en')) : 'Unassigned';
                    return '<span class="text-xs font-semibold text-gray-400">' . $catName . '</span>';
                })
                ->addColumn('price_formatted', function ($prod) {
                    return '<span class="font-black text-brass text-base font-serif">$' . number_format($prod->price, 2) . '</span>';
                })
                ->addColumn('stock_badge', function ($prod) {
                    if ($prod->stock > 5) {
                        return '<span class="px-2.5 py-1 text-[9px] font-extrabold uppercase bg-green-950/30 border border-green-900/40 text-green-400 rounded-full">' . $prod->stock . ' In Stock</span>';
                    }
                    return '<span class="px-2.5 py-1 text-[9px] font-extrabold uppercase bg-red-950/30 border border-red-900/40 text-red-400 rounded-full">LOW STOCK (' . $prod->stock . ')</span>';
                })
                ->addColumn('actions', function ($prod) {
                    $json = htmlspecialchars(json_encode($prod), ENT_QUOTES, 'UTF-8');
                    $delUrl = route('admin.products.delete', $prod->id);
                    $csrf = csrf_field();
                    $delName = addslashes($prod->getLocalized('name', 'en'));
                    return '<div class="flex items-center justify-end space-x-2">'
                        . '<button type="button" class="btn-edit-product px-3 py-1.5 border border-brass/35 text-brass hover:bg-brass/10 rounded-lg text-xs font-bold transition-all duration-150" data-product="' . $json . '">Edit</button>'
                        . '<form action="' . $delUrl . '" method="POST" onsubmit="return confirmDelete(event, \'' . $delName . '\');" class="inline">'
                        . $csrf
                        . '<button type="submit" class="px-3 py-1.5 border border-red-950/40 border-red-900/30 text-red-400 hover:bg-red-950/20 rounded-lg text-xs font-bold transition-all duration-150">Remove</button>'
                        . '</form>'
                        . '</div>';
                })
                ->rawColumns(['image', 'details', 'category', 'price_formatted', 'stock_badge', 'actions'])
                ->make(true);
        }
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
    public function categories(Request $request)
    {
        $this->checkAdmin();
        if ($request->ajax()) {
            $categories = Category::select('categories.*');
            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('image', function ($cat) {
                    return '<img src="' . e($cat->image) . '" alt="" class="w-14 h-14 object-cover rounded-lg border border-border shadow">';
                })
                ->addColumn('name_en', function ($cat) {
                    return '<div class="font-bold text-[#F5EFE4] text-sm">' . e($cat->getLocalized('name', 'en')) . '</div>';
                })
                ->addColumn('name_bn', function ($cat) {
                    return '<div class="text-[#F5EFE4] text-sm">' . e($cat->getLocalized('name', 'bn')) . '</div>';
                })
                ->addColumn('slug_path', function ($cat) {
                    $status = $cat->status === 'active'
                        ? '<span class="text-[8px] font-bold bg-green-950/20 border border-green-900/30 text-green-400 px-1.5 py-0.5 rounded uppercase">Active</span>'
                        : '<span class="text-[8px] font-bold bg-red-950/20 border border-red-900/30 text-red-400 px-1.5 py-0.5 rounded uppercase">Hidden</span>';
                    return '<div class="text-gray-400 font-mono text-xs">/category/' . e($cat->slug) . '</div><div class="mt-1 flex">' . $status . '</div>';
                })
                ->addColumn('actions', function ($cat) {
                    $json = htmlspecialchars(json_encode($cat), ENT_QUOTES, 'UTF-8');
                    $delUrl = route('admin.categories.delete', $cat->id);
                    $csrf = csrf_field();
                    $delName = addslashes($cat->getLocalized('name', 'en'));
                    return '<div class="flex items-center justify-end space-x-2">'
                        . '<button type="button" class="btn-edit-category px-3 py-1.5 border border-brass/35 text-brass hover:bg-brass/10 rounded-lg text-xs font-bold transition-all duration-150" data-category="' . $json . '">Edit</button>'
                        . '<form action="' . $delUrl . '" method="POST" onsubmit="return confirmDelete(event, \'' . $delName . '\');" class="inline">'
                        . $csrf
                        . '<button type="submit" class="px-3 py-1.5 border border-red-950/40 border-red-900/30 text-red-400 hover:bg-red-950/20 rounded-lg text-xs font-bold transition-all duration-150">Remove</button>'
                        . '</form>'
                        . '</div>';
                })
                ->rawColumns(['image', 'name_en', 'name_bn', 'slug_path', 'actions'])
                ->make(true);
        }
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
    public function banners(Request $request)
    {
        $this->checkAdmin();
        if ($request->ajax()) {
            $banners = Banner::select('banners.*');
            return DataTables::of($banners)
                ->addIndexColumn()
                ->addColumn('preview', function ($b) {
                    return '<img src="' . e($b->image) . '" alt="" class="w-16 h-10 object-cover rounded border border-border shadow">';
                })
                ->addColumn('info', function ($b) {
                    return '<div class="font-bold text-[#F5EFE4] text-sm">' . e($b->getLocalized('title', 'en')) . '</div>'
                        . '<div class="text-[10px] text-gray-400 font-medium mt-0.5">' . e($b->getLocalized('headline', 'en')) . '</div>';
                })
                ->addColumn('type_badge', function ($b) {
                    return '<span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded bg-brass/10 text-brass border border-brass/25">' . e(str_replace('_', ' ', $b->type)) . '</span>';
                })
                ->addColumn('link_path', function ($b) {
                    return '<span class="text-gray-400 text-xs font-mono">' . e($b->link ?: 'None') . '</span>';
                })
                ->addColumn('status_badge', function ($b) {
                    return $b->status
                        ? '<span class="text-[8px] font-bold bg-green-950/20 border border-green-900/30 text-green-400 px-1.5 py-0.5 rounded uppercase">Active</span>'
                        : '<span class="text-[8px] font-bold bg-red-950/20 border border-red-900/30 text-red-400 px-1.5 py-0.5 rounded uppercase">Disabled</span>';
                })
                ->addColumn('actions', function ($b) {
                    $editUrl = route('admin.banners.edit', $b->id);
                    $delUrl = route('admin.banners.delete', $b->id);
                    $csrf = csrf_field();
                    $delName = addslashes($b->getLocalized('title', 'en'));
                    return '<div class="flex items-center justify-end space-x-2">'
                        . '<a href="' . $editUrl . '" class="px-3 py-1.5 border border-brass/35 text-brass hover:bg-brass/10 rounded-lg text-xs font-bold transition-all duration-150">Edit</a>'
                        . '<form action="' . $delUrl . '" method="POST" onsubmit="return confirmDelete(event, \'' . $delName . '\');" class="inline">'
                        . $csrf
                        . '<button type="submit" class="px-3 py-1.5 border border-red-950/40 border-red-900/30 text-red-400 hover:bg-red-950/20 rounded-lg text-xs font-bold transition-all duration-150">Delete</button>'
                        . '</form>'
                        . '</div>';
                })
                ->rawColumns(['preview', 'info', 'type_badge', 'link_path', 'status_badge', 'actions'])
                ->make(true);
        }
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

    public function editBanner($id)
    {
        $this->checkAdmin();
        $banner = Banner::findOrFail($id);
        $products = Product::where('status', 'active')->orderBy('id')->get();
        return view('admin.banner-edit', compact('banner', 'products'));
    }

    public function deleteBanner($id)
    {
        $this->checkAdmin();
        Banner::findOrFail($id)->delete();
        return redirect()->route('admin.banners')->with('success', 'Banner deleted successfully.');
    }

    // --- TRUST FEATURES ---
    public function features(Request $request)
    {
        $this->checkAdmin();
        if ($request->ajax()) {
            $features = TrustFeature::select('trust_features.*');
            return DataTables::of($features)
                ->addIndexColumn()
                ->addColumn('icon_view', function ($feat) {
                    return '<span class="font-mono text-xs text-brass font-bold">' . e($feat->icon) . '</span>';
                })
                ->addColumn('title_view', function ($feat) {
                    return '<span class="font-bold text-[#F5EFE4] text-sm">' . e($feat->getLocalized('title', 'en')) . '</span>';
                })
                ->addColumn('sub_view', function ($feat) {
                    return '<span class="text-xs text-gray-400">' . e($feat->getLocalized('sub', 'en')) . '</span>';
                })
                ->addColumn('key_view', function ($feat) {
                    return '<span class="font-mono text-xs text-gray-500">' . e($feat->feature_key) . '</span>';
                })
                ->addColumn('actions', function ($feat) {
                    $delUrl = route('admin.features.delete', $feat->id);
                    $csrf = csrf_field();
                    $delName = addslashes($feat->getLocalized('title', 'en'));
                    return '<form action="' . $delUrl . '" method="POST" class="inline-block" onsubmit="return confirmDelete(event, \'' . $delName . '\');">'
                        . $csrf
                        . '<button type="submit" class="px-3 py-1.5 bg-red-950/60 hover:bg-red-900 border border-red-800/50 text-red-300 text-xs font-bold rounded-lg transition-colors">Delete</button>'
                        . '</form>';
                })
                ->rawColumns(['icon_view', 'title_view', 'sub_view', 'key_view', 'actions'])
                ->make(true);
        }
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
    public function testimonials(Request $request)
    {
        $this->checkAdmin();
        if ($request->ajax()) {
            $testimonials = Testimonial::select('testimonials.*');
            return DataTables::of($testimonials)
                ->addIndexColumn()
                ->addColumn('author', function ($test) {
                    return '<span class="font-bold text-[#F5EFE4] text-sm">' . e($test->name) . '</span>';
                })
                ->addColumn('rating_stars', function ($test) {
                    return '<span class="text-amber-400 font-bold">' . str_repeat('★', max(1, min(5, $test->rating))) . '</span>';
                })
                ->addColumn('quote_text', function ($test) {
                    return '<span class="text-xs text-gray-300 italic max-w-md block">"' . e($test->getLocalized('quote', 'en')) . '"</span>';
                })
                ->addColumn('featured_badge', function ($test) {
                    $cls = $test->is_featured ? 'bg-emerald-500/20 text-emerald-400' : 'bg-gray-500/20 text-gray-400';
                    return '<span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded ' . $cls . '">' . ($test->is_featured ? 'Featured' : 'Standard') . '</span>';
                })
                ->addColumn('actions', function ($test) {
                    $delUrl = route('admin.testimonials.delete', $test->id);
                    $csrf = csrf_field();
                    $delName = addslashes($test->name);
                    return '<form action="' . $delUrl . '" method="POST" class="inline-block" onsubmit="return confirmDelete(event, \'' . $delName . '\');">'
                        . $csrf
                        . '<button type="submit" class="px-3 py-1.5 bg-red-950/60 hover:bg-red-900 border border-red-800/50 text-red-300 text-xs font-bold rounded-lg transition-colors">Delete</button>'
                        . '</form>';
                })
                ->rawColumns(['author', 'rating_stars', 'quote_text', 'featured_badge', 'actions'])
                ->make(true);
        }
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
    public function orders(Request $request)
    {
        $this->checkAdmin();
        if ($request->ajax()) {
            $orders = Order::with('items.product')->select('orders.*');
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('order_code', function ($ord) {
                    return '<span class="font-mono font-bold text-brass text-sm">#' . e($ord->order_number) . '</span>';
                })
                ->addColumn('customer', function ($ord) {
                    return '<div class="font-bold text-[#F5EFE4] text-sm">' . e($ord->shipping_name) . '</div>'
                        . '<div class="text-[10px] text-gray-500 font-semibold mt-0.5">' . e($ord->shipping_address) . ', ' . e($ord->shipping_city) . ' (' . e($ord->shipping_zip) . ')</div>';
                })
                ->addColumn('date', function ($ord) {
                    return '<span class="text-gray-400 text-xs font-semibold">' . $ord->created_at->format('Y-m-d H:i') . '</span>';
                })
                ->addColumn('items', function ($ord) {
                    $html = '<div class="space-y-1">';
                    foreach ($ord->items as $item) {
                        $pName = $item->product ? e($item->product->getLocalized('name', 'en')) : 'Product';
                        $html .= '<div class="text-xs text-gray-400 font-medium"><span class="text-gray-300 font-semibold">' . $pName . '</span> x <span class="text-brass">' . $item->quantity . '</span></div>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('total', function ($ord) {
                    return '<span class="font-black text-[#F5EFE4] text-base font-serif">$' . number_format($ord->total, 2) . '</span>';
                })
                ->addColumn('status', function ($ord) {
                    $cls = $ord->status === 'delivered' ? 'bg-green-950/30 border border-green-900/40 text-green-400'
                        : ($ord->status === 'shipped' ? 'bg-indigo-950/30 border border-indigo-900/40 text-indigo-400'
                        : 'bg-yellow-950/30 border border-yellow-900/40 text-yellow-500');
                    return '<span class="px-2.5 py-1 text-[9px] font-black uppercase rounded-full tracking-wider ' . $cls . '">' . e($ord->status) . '</span>';
                })
                ->addColumn('actions', function ($ord) {
                    $url = route('admin.orders.status', $ord->id);
                    $csrf = csrf_field();
                    $statuses = ['pending', 'processing', 'shipped', 'in_transit', 'out_for_delivery', 'delivered'];
                    $options = '';
                    foreach ($statuses as $s) {
                        $sel = $ord->status === $s ? 'selected' : '';
                        $options .= '<option value="' . $s . '" ' . $sel . '>' . ucfirst(str_replace('_', ' ', $s)) . '</option>';
                    }
                    return '<form action="' . $url . '" method="POST" class="inline-flex items-center">'
                        . $csrf
                        . '<select name="status" onchange="this.form.submit()" class="bg-surface text-gray-400 border border-border rounded-lg text-xs font-bold px-2.5 py-1.5 focus:outline-none focus:border-brass/65 focus:ring-1 focus:ring-brass/65">'
                        . $options
                        . '</select>'
                        . '</form>';
                })
                ->rawColumns(['order_code', 'customer', 'date', 'items', 'total', 'status', 'actions'])
                ->make(true);
        }
        $orders = Order::with('items.product')->orderBy('created_at', 'desc')->get();
        return view('admin.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $this->checkAdmin();
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,in_transit,out_for_delivery,delivered'
        ]);

        Order::findOrFail($id)->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.orders')->with('success', 'Order status updated successfully.');
    }

    // --- COUPONS ---
    public function coupons(Request $request)
    {
        $this->checkAdmin();
        if ($request->ajax()) {
            $coupons = Coupon::select('coupons.*');
            return DataTables::of($coupons)
                ->addIndexColumn()
                ->addColumn('code_badge', function ($coup) {
                    $status = $coup->status === 'active'
                        ? '<span class="text-[8px] font-bold bg-green-950/20 border border-green-900/30 text-green-400 px-1.5 py-0.5 rounded uppercase">Active</span>'
                        : '<span class="text-[8px] font-bold bg-red-950/20 border border-red-900/30 text-red-400 px-1.5 py-0.5 rounded uppercase">Inactive</span>';
                    return '<div class="font-mono font-bold text-brass text-sm select-all"><div>' . e($coup->code) . '</div><div class="mt-1 flex">' . $status . '</div></div>';
                })
                ->addColumn('type_formatted', function ($coup) {
                    return '<span class="text-gray-400 uppercase text-xs font-bold tracking-wide">' . e(str_replace('_', ' ', $coup->type)) . '</span>';
                })
                ->addColumn('value_formatted', function ($coup) {
                    $val = $coup->type === 'percent' ? $coup->value . '%' : ($coup->type === 'free_shipping' ? 'Free Shipping' : '$' . number_format($coup->value, 2));
                    return '<span class="font-bold text-[#F5EFE4] text-sm">' . $val . '</span>';
                })
                ->addColumn('min_order_formatted', function ($coup) {
                    return '<span class="text-gray-400 text-xs font-mono font-bold">$' . number_format($coup->min_order, 2) . '</span>';
                })
                ->addColumn('expires_formatted', function ($coup) {
                    return '<span class="text-gray-400 text-xs font-semibold">' . ($coup->expires_at ? $coup->expires_at->format('Y-m-d') : 'No Expiry') . '</span>';
                })
                ->addColumn('actions', function ($coup) {
                    $json = htmlspecialchars(json_encode($coup), ENT_QUOTES, 'UTF-8');
                    $delUrl = route('admin.coupons.delete', $coup->id);
                    $csrf = csrf_field();
                    $delName = addslashes($coup->code);
                    return '<div class="flex items-center justify-end space-x-2">'
                        . '<button type="button" class="btn-edit-coupon px-3 py-1.5 border border-brass/35 text-brass hover:bg-brass/10 rounded-lg text-xs font-bold transition-all duration-150" data-coupon="' . $json . '">Edit</button>'
                        . '<form action="' . $delUrl . '" method="POST" onsubmit="return confirmDelete(event, \'' . $delName . '\');" class="inline">'
                        . $csrf
                        . '<button type="submit" class="px-3 py-1.5 border border-red-950/40 border-red-900/30 text-red-400 hover:bg-red-950/20 rounded-lg text-xs font-bold transition-all duration-150">Remove</button>'
                        . '</form>'
                        . '</div>';
                })
                ->rawColumns(['code_badge', 'type_formatted', 'value_formatted', 'min_order_formatted', 'expires_formatted', 'actions'])
                ->make(true);
        }
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
    public function users(Request $request)
    {
        $this->checkAdmin();
        if ($request->ajax()) {
            $users = User::select('users.*');
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('user_id', function ($u) {
                    return '<span class="text-gray-500 font-mono text-xs">#' . $u->id . '</span>';
                })
                ->addColumn('profile', function ($u) {
                    $initials = e(strtoupper(substr($u->name, 0, 2)));
                    $roleBadge = $u->role === 'admin'
                        ? '<span class="px-2 py-0.5 text-[8px] font-black uppercase bg-brass text-black rounded select-none">Admin</span>'
                        : '<span class="px-2 py-0.5 text-[8px] font-bold uppercase bg-zinc-800 border border-zinc-700 text-gray-400 rounded select-none">Customer</span>';
                    return '<div class="flex items-center space-x-3">'
                        . '<div class="w-9 h-9 bg-brass/10 border border-brass/15 text-brass font-bold rounded-full flex items-center justify-center uppercase text-sm select-none">' . $initials . '</div>'
                        . '<div><div class="font-bold text-[#F5EFE4] text-sm flex items-center space-x-2"><span>' . e($u->name) . '</span>' . $roleBadge . '</div></div>'
                        . '</div>';
                })
                ->addColumn('email', function ($u) {
                    return '<span class="text-gray-400 text-xs font-semibold">' . e($u->email) . '</span>';
                })
                ->addColumn('phone', function ($u) {
                    return '<span class="text-gray-400 font-mono text-xs">' . e($u->phone ?: 'Not provided') . '</span>';
                })
                ->addColumn('locale', function ($u) {
                    return '<span class="text-center text-xs font-black uppercase text-brass block">' . e($u->locale_preference ?: 'en') . '</span>';
                })
                ->addColumn('theme', function ($u) {
                    $cls = $u->theme_preference === 'dark' ? 'bg-surface border border-border text-gray-400' : 'bg-gray-200 border border-gray-300 text-gray-800';
                    return '<div class="text-center"><span class="px-2.5 py-1 text-[9px] font-bold uppercase rounded-md tracking-wide select-none ' . $cls . '">' . e($u->theme_preference ?: 'light') . '</span></div>';
                })
                ->addColumn('date', function ($u) {
                    return '<span class="text-gray-400 text-xs font-semibold">' . $u->created_at->format('Y-m-d H:i') . '</span>';
                })
                ->addColumn('actions', function ($u) {
                    $json = htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8');
                    return '<button type="button" class="btn-edit-user px-3 py-1.5 border border-brass/35 text-brass hover:bg-brass/10 rounded-lg text-xs font-bold transition-all duration-150" data-user="' . $json . '">Edit</button>';
                })
                ->rawColumns(['user_id', 'profile', 'email', 'phone', 'locale', 'theme', 'date', 'actions'])
                ->make(true);
        }
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
    public function adminFaqs(Request $request)
    {
        $this->checkAdmin();
        if ($request->ajax()) {
            $faqs = Faq::select('faqs.*');
            return DataTables::of($faqs)
                ->addIndexColumn()
                ->addColumn('category_badge', function ($faq) {
                    return '<span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-full bg-brass/10 border border-brass/30 text-brass">' . strtoupper(e($faq->category)) . '</span>';
                })
                ->addColumn('question_view', function ($faq) {
                    return '<div class="font-bold text-[#F5EFE4] text-sm">' . e($faq->getLocalized('question', 'en')) . '</div>'
                        . '<div class="text-xs text-gray-400 mt-0.5">' . e($faq->getLocalized('question', 'bn')) . '</div>';
                })
                ->addColumn('status_badge', function ($faq) {
                    return $faq->status
                        ? '<span class="text-[8px] font-bold bg-green-950/20 border border-green-900/30 text-green-400 px-1.5 py-0.5 rounded uppercase">Active</span>'
                        : '<span class="text-[8px] font-bold bg-red-950/20 border border-red-900/30 text-red-400 px-1.5 py-0.5 rounded uppercase">Hidden</span>';
                })
                ->addColumn('actions', function ($faq) {
                    $delUrl = route('admin.faqs.delete', $faq->id);
                    $csrf = csrf_field();
                    $delName = addslashes($faq->getLocalized('question', 'en'));
                    return '<form action="' . $delUrl . '" method="POST" class="inline-block" onsubmit="return confirmDelete(event, \'' . $delName . '\');">'
                        . $csrf
                        . '<button type="submit" class="px-3 py-1.5 bg-red-950/60 hover:bg-red-900 border border-red-800/50 text-red-300 text-xs font-bold rounded-lg transition-colors">Delete</button>'
                        . '</form>';
                })
                ->rawColumns(['category_badge', 'question_view', 'status_badge', 'actions'])
                ->make(true);
        }
        $faqs = Faq::orderBy('sort_order')->get();
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
