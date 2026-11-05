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
                <input type="text" id="name_en" name="name_en" required placeholder="product name" oninput="document.getElementById('slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Product Name (BN)</label>
                <input type="text" name="name_bn" required placeholder="প্রোডাক্ট নাম" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Slug</label>
                <input type="text" id="slug" name="slug" required placeholder="product-slug" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Price ($)</label>
                <input type="number" step="0.01" name="price" required placeholder=" " class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Stock Inventory</label>
                <input type="number" name="stock" required placeholder=" " class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">SKU Identifier</label>
                <input type="text" name="sku" required placeholder=" " class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
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
            <table id="products-table" class="w-full text-left border-collapse dataTable">
                <thead>
                    <tr class="bg-surface text-gray-400 text-[10px] uppercase font-bold tracking-wider border-b border-border">
                        <th class="p-5 w-12 text-center">#</th>
                        <th class="p-5 w-20">Image</th>
                        <th class="p-5">Product Details</th>
                        <th class="p-5">Category</th>
                        <th class="p-5">Price</th>
                        <th class="p-5">Stock Level</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm">
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.products') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-mono text-xs text-gray-500' },
                { data: 'image', name: 'image', orderable: false, searchable: false },
                { data: 'details', name: 'name' },
                { data: 'category', name: 'category.name' },
                { data: 'price_formatted', name: 'price' },
                { data: 'stock_badge', name: 'stock' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right' }
            ],
            order: [[2, 'asc']],
            language: {
                search: "SEARCH:",
                searchPlaceholder: "Search products, SKU...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ products",
                paginate: {
                    previous: "‹ Prev",
                    next: "Next ›"
                }
            }
        });

        // Delegated event listener for edit modal
        $(document).on('click', '.btn-edit-product', function() {
            var prod = $(this).data('product');
            if (typeof prod === 'string') {
                try { prod = JSON.parse(prod); } catch(e){}
            }
            openEditModal(prod);
        });
    });
</script>
@endpush

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