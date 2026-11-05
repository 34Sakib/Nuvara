@extends('layouts.admin')

@section('title', 'Admin - Sliders & Banners')
@section('header_title', 'Sliders & Banners')

@section('content')
<div class="space-y-8">
    <!-- Create Banner Form -->
    <div class="glass-card rounded-2xl p-6 shadow-md text-left transition-all duration-300 hover:border-brass/25">
        <div class="flex justify-between items-center mb-6 border-b border-border pb-3">
            <h3 class="text-xs font-bold text-brass uppercase tracking-wider">
                Add Hero Slider / Promotional Banner
            </h3>
            <span class="text-[10px] text-gray-500 font-mono">Total Banners: {{ count($banners) }}</span>
        </div>
        
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Banner Type</label>
                <select name="type" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-300 text-sm focus:outline-none focus:border-brass">
                    <option value="hero_slider">Hero Slider</option>
                    <option value="promo_banner">Promotional Banner</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Banner Title</label>
                <input type="text" name="title_en" required placeholder="Exclusive Sale" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Headline</label>
                <input type="text" name="headline_en" required placeholder="Happening Now!" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Badge Category</label>
                <input type="text" name="badge" placeholder="EXCLUSIVE" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Badge Text</label>
                <input type="text" name="badge_text" placeholder="Sale" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Button Label</label>
                <input type="text" name="button_text_en" required placeholder="SHOP NOW" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Subtitle / Description</label>
                <input type="text" name="sub_en" required placeholder="Discover amazing deals on our website!" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Link URL</label>
                <input type="text" name="link" placeholder="/category/electronics" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Link Product (Optional)</label>
                <select name="product_id" class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-300 text-sm focus:outline-none focus:border-brass">
                    <option value="">-- Select Linked Product --</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}">{{ $prod->getLocalized('name', 'en') }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Image (File Upload or Image URL)</label>
                <div class="grid grid-cols-2 gap-3">
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-border bg-black/40 text-sm text-gray-400">
                    <input type="text" name="image_url" placeholder="https://images.unsplash.com/..." class="w-full px-4 py-2 rounded-lg border border-border bg-black/40 text-sm text-gray-200">
                </div>
            </div>

            <div class="md:col-span-3 pt-2">
                <button type="submit" class="px-6 py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-lg transition-colors">
                    Add Banner / Slider
                </button>
            </div>
        </form>
    </div>

    <!-- Banners List -->
    <div class="glass-card rounded-2xl overflow-hidden shadow-lg border border-border">
        <div class="overflow-x-auto">
            <table id="banners-table" class="w-full text-left border-collapse dataTable">
                <thead>
                    <tr class="bg-surface text-gray-400 text-[10px] uppercase font-bold tracking-wider border-b border-border">
                        <th class="p-5 w-12 text-center">#</th>
                        <th class="p-5 w-20">Preview</th>
                        <th class="p-5">Banner Info</th>
                        <th class="p-5">Type</th>
                        <th class="p-5">Link</th>
                        <th class="p-5">Status</th>
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
        $('#banners-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.banners') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-mono text-xs text-gray-500' },
                { data: 'preview', name: 'image', orderable: false, searchable: false },
                { data: 'info', name: 'title' },
                { data: 'type_badge', name: 'type' },
                { data: 'link_path', name: 'link' },
                { data: 'status_badge', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right' }
            ],
            order: [[2, 'asc']],
            language: {
                search: "SEARCH:",
                searchPlaceholder: "Search banners...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ banners",
                paginate: {
                    previous: "‹ Prev",
                    next: "Next ›"
                }
            }
        });
    });
</script>
@endpush
 const f=document.createElement('form'); f.method='POST'; f.action='/admin/banners/'+b.id+'/update'; f.innerHTML=`<input name="_token" value="{{ csrf_token() }}"><input name="title_en"><input name="headline_en"><input name="sub_en"><input name="button_text_en"><input name="link"><input name="type" value="${b.type||'hero_slider'}"><input name="status" value="1">`; const vals=[title,headline,sub,button,link]; ['title_en','headline_en','sub_en','button_text_en','link'].forEach((n,i)=>f.querySelector('[name='+n+']').value=vals[i]); document.body.appendChild(f); f.submit();
}
</script>
@endsection
