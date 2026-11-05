@extends('layouts.admin')

@section('title', 'Admin - Categories')
@section('header_title', 'Categories')

@section('content')
<div class="space-y-8">
    <!-- Collapsible Add Category Form -->
    <div class="glass-card rounded-2xl p-6 shadow-md text-left transition-all duration-300 hover:border-brass/25">
        <div class="flex justify-between items-center mb-6 border-b border-border pb-3">
            <h3 class="text-xs font-bold text-brass uppercase tracking-wider">
                Create Catalog Category
            </h3>
            <span class="text-[10px] text-gray-500 font-mono">Groups: {{ count($categories) }} Categories</span>
        </div>
        
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Category Name (EN)</label>
                <input type="text" id="name_en" name="name_en" required placeholder="Electronics" oninput="document.getElementById('slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>
            
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Category Name (BN)</label>
                <input type="text" name="name_bn" required placeholder="ইলেকট্রনিক্স" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Slug</label>
                <input type="text" id="slug" name="slug" required placeholder="electronics" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Upload Category Image</label>
                <input type="file" name="image" required accept="image/*" class="w-full px-4 py-2 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-400">
            </div>

            <div class="md:col-span-4 pt-2">
                <button type="submit" class="px-6 py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-lg transition-colors active:scale-95 duration-200">
                    Add Category
                </button>
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div class="glass-card rounded-2xl overflow-hidden shadow-lg border border-border">
        <div class="overflow-x-auto">
            <table id="categories-table" class="w-full text-left border-collapse dataTable">
                <thead>
                    <tr class="bg-surface text-gray-400 text-[10px] uppercase font-bold tracking-wider border-b border-border">
                        <th class="p-5 w-12 text-center">#</th>
                        <th class="p-5 w-20">Image</th>
                        <th class="p-5">Category Name (EN)</th>
                        <th class="p-5">Category Name (BN)</th>
                        <th class="p-5">Slug Path</th>
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
        $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.categories') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-mono text-xs text-gray-500' },
                { data: 'image', name: 'image', orderable: false, searchable: false },
                { data: 'name_en', name: 'name' },
                { data: 'name_bn', name: 'name' },
                { data: 'slug_path', name: 'slug' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right' }
            ],
            order: [[2, 'asc']],
            language: {
                search: "SEARCH:",
                searchPlaceholder: "Search categories...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ categories",
                paginate: {
                    previous: "‹ Prev",
                    next: "Next ›"
                }
            }
        });

        // Delegated event listener for edit modal
        $(document).on('click', '.btn-edit-category', function() {
            var cat = $(this).data('category');
            if (typeof cat === 'string') {
                try { cat = JSON.parse(cat); } catch(e){}
            }
            openEditModal(cat);
        });
    });
</script>
@endpush

<!-- Edit Category Modal Backdrop Overlay -->
<div id="edit-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden animate-fade-in">
    <div class="glass-card w-full max-w-xl rounded-2xl p-6 md:p-8 relative shadow-2xl text-left border border-brass/25">
        <button onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-500 hover:text-white text-lg">✕</button>
        <h3 class="text-sm font-bold text-brass uppercase tracking-wider mb-6 border-b border-border pb-3">
            Edit Category Configuration
        </h3>
        
        <form id="edit-form" action="" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Category Name (EN)</label>
                <input type="text" id="edit-name-en" name="name_en" required oninput="document.getElementById('edit-slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>
            
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Category Name (BN)</label>
                <input type="text" id="edit-name-bn" name="name_bn" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Slug</label>
                <input type="text" id="edit-slug" name="slug" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Visibility Status</label>
                <select id="edit-status" name="status" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-400 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass">
                    <option value="active">Active (Visible)</option>
                    <option value="inactive">Inactive (Hidden)</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Upload Category Image (Optional)</label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-400">
            </div>

            <div class="md:col-span-2 pt-2 flex justify-end space-x-3">
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
    function openEditModal(cat) {
        document.getElementById('edit-form').action = '/admin/categories/' + cat.id + '/update';
        document.getElementById('edit-name-en').value = cat.name.en || '';
        document.getElementById('edit-name-bn').value = cat.name.bn || '';
        document.getElementById('edit-slug').value = cat.slug || '';
        document.getElementById('edit-status').value = cat.status || 'active';
        document.getElementById('edit-modal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }
</script>
@endsection
