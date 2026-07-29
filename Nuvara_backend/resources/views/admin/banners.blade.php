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
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface text-gray-400 text-[10px] uppercase font-bold tracking-wider border-b border-border">
                        <th class="p-5 w-20">Preview</th>
                        <th class="p-5">Banner Info</th>
                        <th class="p-5">Type</th>
                        <th class="p-5">Link</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm">
                    @foreach($banners as $banner)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="p-5">
                                <img src="{{ $banner->image }}" alt="" class="w-16 h-12 object-cover rounded-lg border border-border">
                            </td>
                            <td class="p-5">
                                <div class="font-bold text-[#F5EFE4]">{{ $banner->getLocalized('title', 'en') }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $banner->getLocalized('headline', 'en') }}</div>
                            </td>
                            <td class="p-5">
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase {{ $banner->type === 'hero_slider' ? 'bg-amber-500/20 text-amber-300' : 'bg-purple-500/20 text-purple-300' }}">
                                    {{ $banner->type }}
                                </span>
                            </td>
                            <td class="p-5 font-mono text-xs text-gray-400">{{ $banner->link }}</td>
                            <td class="p-5">
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded {{ $banner->status ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                                    {{ $banner->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="p-5 text-right space-x-2">
                                <form action="{{ route('admin.banners.delete', $banner->id) }}" method="POST" class="inline-block" onsubmit="return confirmDelete(event, '{{ addslashes($banner->getLocalized('title', 'en')) }}');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-red-950/60 hover:bg-red-900 border border-red-800/50 text-red-300 text-xs font-bold rounded-lg transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
