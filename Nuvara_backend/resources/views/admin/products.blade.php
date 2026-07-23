@extends('layouts.admin')

@section('title', 'Admin - Products')
@section('header_title', 'Products')

@section('content')
<div class="space-y-8">
    <!-- Collapse Add Product Form -->
    <div class="glass-card rounded-2xl p-6 shadow-md text-left transition-all duration-300 hover:border-brass/25">
        <div class="flex justify-between items-center mb-6 border-b border-border pb-3">
            <h3 class="text-xs font-bold text-brass uppercase tracking-wider">
                Create Catalog Product
            </h3>
            <span class="text-[10px] text-gray-500 font-mono">Catalog: {{ count($products) }} Items</span>
        </div>
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Product Name (EN)</label>
                <input type="text" id="name_en" name="name_en" required placeholder="AeroSound Headphones" oninput="document.getElementById('slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>
            
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Product Name (BN)</label>
                <input type="text" name="name_bn" required placeholder="অ্যারোসাউন্ড হেডফোন" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Slug</label>
                <input type="text" id="slug" name="slug" required placeholder="aerosound-headphones" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Price ($)</label>
                <input type="number" step="0.01" name="price" required placeholder="199.99" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Stock Inventory</label>
                <input type="number" name="stock" required placeholder="25" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">SKU Identifier</label>
                <input type="text" name="sku" required placeholder="NVR-AERO-01" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Category Selection</label>
                <select name="category_id" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-400 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->getLocalized('name', 'en') }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Upload Product Image</label>
                <input type="file" name="image" required accept="image/*" class="w-full px-4 py-2 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-400">
            </div>

            <div class="md:col-span-3 pt-2">
                <button type="submit" class="px-6 py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-lg transition-colors active:scale-95 duration-200">
                    Add Product to Catalog
                </button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="glass-card rounded-2xl overflow-hidden shadow-lg border border-border">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface text-gray-400 text-[10px] uppercase font-bold tracking-wider border-b border-border">
                        <th class="p-5 w-20">Image</th>
                        <th class="p-5">Product Details</th>
                        <th class="p-5">Category</th>
                        <th class="p-5">Price</th>
                        <th class="p-5">Stock Level</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm">
                    @foreach($products as $prod)
                        <tr class="hover:bg-white/5 transition-colors duration-250">
                            <td class="p-5">
                                <img 
                                    src="{{ $prod->images->first()->path ?? 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80' }}" 
                                    alt="" 
                                    class="w-14 h-14 object-cover rounded-lg border border-border/80 shadow"
                                >
                            </td>
                            <td class="p-5">
                                <div class="font-bold text-[#F5EFE4] text-sm">
                                    {{ $prod->getLocalized('name', 'en') }}
                                </div>
                                <div class="flex items-center space-x-2 mt-1 select-none">
                                    <span class="text-[9px] font-mono text-gray-500 bg-black/40 border border-border px-2 py-0.5 rounded uppercase">SKU: {{ $prod->sku }}</span>
                                    @if($prod->status === 'active')
                                        <span class="text-[8px] font-bold bg-green-950/20 border border-green-900/30 text-green-400 px-1.5 py-0.5 rounded uppercase">Active</span>
                                    @else
                                        <span class="text-[8px] font-bold bg-red-950/20 border border-red-900/30 text-red-400 px-1.5 py-0.5 rounded uppercase">Hidden</span>
                                    @endif
                                    @if($prod->is_best_seller)
                                        <span class="text-[8px] font-bold bg-amber-500/10 border border-amber-500/20 text-amber-500 px-1.5 py-0.5 rounded uppercase">Best Seller</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-5">
                                <span class="text-xs font-semibold text-gray-400">
                                    {{ $prod->category ? $prod->category->getLocalized('name', 'en') : 'Unassigned' }}
                                </span>
                            </td>
                            <td class="p-5 font-black text-brass text-base font-serif">
                                ${{ number_format($prod->price, 2) }}
                            </td>
                            <td class="p-5">
                                @if($prod->stock > 5)
                                    <span class="px-2.5 py-1 text-[9px] font-extrabold uppercase bg-green-950/30 border border-green-900/40 text-green-400 rounded-full">
                                        {{ $prod->stock }} In Stock
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[9px] font-extrabold uppercase bg-red-950/30 border border-red-900/40 text-red-400 rounded-full">
                                        LOW STOCK ({{ $prod->stock }})
                                    </span>
                                @endif
                            </td>
                            <td class="p-5 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick='openEditModal({!! json_encode($prod) !!})' class="px-3 py-1.5 border border-brass/35 text-brass hover:bg-brass/10 rounded-lg text-xs font-bold transition-all duration-150">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.products.delete', $prod->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 border border-red-950/40 border-red-900/30 text-red-400 hover:bg-red-950/20 rounded-lg text-xs font-bold transition-all duration-150">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Product Modal Backdrop Overlay -->
<div id="edit-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden animate-fade-in">
    <div class="glass-card w-full max-w-2xl rounded-2xl p-6 md:p-8 relative shadow-2xl text-left border border-brass/25">
        <button onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-500 hover:text-white text-lg">✕</button>
        <h3 class="text-sm font-bold text-brass uppercase tracking-wider mb-6 border-b border-border pb-3">
            Edit Product Configuration
        </h3>
        
        <form id="edit-form" action="" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Product Name (EN)</label>
                <input type="text" id="edit-name-en" name="name_en" required oninput="document.getElementById('edit-slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>
            
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Product Name (BN)</label>
                <input type="text" id="edit-name-bn" name="name_bn" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Slug</label>
                <input type="text" id="edit-slug" name="slug" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Price ($)</label>
                <input type="number" step="0.01" id="edit-price" name="price" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Stock Inventory</label>
                <input type="number" id="edit-stock" name="stock" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">SKU Identifier</label>
                <input type="text" id="edit-sku" name="sku" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Category</label>
                <select id="edit-category" name="category_id" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-400 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->getLocalized('name', 'en') }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Visibility Status</label>
                <select id="edit-status" name="status" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-400 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass">
                    <option value="active">Active (Visible)</option>
                    <option value="inactive">Inactive (Hidden)</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Upload Product Image (Optional)</label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-400">
            </div>

            <div class="md:col-span-3 pt-2 flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-3 border border-border hover:bg-white/5 text-gray-300 font-bold uppercase text-xs tracking-wider rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-lg transition-colors active:scale-95 duration-200">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(prod) {
        document.getElementById('edit-form').action = '/admin/products/' + prod.id + '/update';
        document.getElementById('edit-name-en').value = prod.name.en || '';
        document.getElementById('edit-name-bn').value = prod.name.bn || '';
        document.getElementById('edit-slug').value = prod.slug || '';
        document.getElementById('edit-price').value = prod.price || '';
        document.getElementById('edit-stock').value = prod.stock || '';
        document.getElementById('edit-sku').value = prod.sku || '';
        document.getElementById('edit-category').value = prod.category_id || '';
        document.getElementById('edit-status').value = prod.status || 'active';
        document.getElementById('edit-modal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }
</script>
@endsection
