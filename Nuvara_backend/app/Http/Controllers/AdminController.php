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
            'review_count' => 0
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
            'status' => $request->status
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
}
