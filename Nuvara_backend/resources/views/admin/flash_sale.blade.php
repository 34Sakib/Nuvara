@extends('layouts.admin')

@section('title', 'Admin - Flash Sale')
@section('header_title', 'Flash Sale Campaign')

@section('content')
<div class="space-y-8">
    <!-- Flash Sale Settings Form -->
    <div class="glass-card rounded-2xl p-6 shadow-md text-left transition-all duration-300 hover:border-brass/25">
        <div class="flex justify-between items-center mb-6 border-b border-border pb-3">
            <h3 class="text-xs font-bold text-brass uppercase tracking-wider">
                Manage Live Flash Sale Campaign
            </h3>
            <span class="text-[10px] text-gray-500 font-mono">
                Status: {{ ($flashSale && $flashSale->status) ? 'ACTIVE' : 'INACTIVE' }}
            </span>
        </div>
        
        <form action="{{ route('admin.flash-sale.update') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Campaign Title (EN)</label>
                    <input type="text" name="title_en" required value="{{ $flashSale ? $flashSale->getLocalized('title', 'en') : 'Flash Deals of the Week' }}" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">End Date & Time</label>
                    <input type="datetime-local" name="ends_at" required value="{{ $flashSale ? $flashSale->ends_at->format('Y-m-d\TH:i') : now()->addHours(4)->format('Y-m-d\TH:i') }}" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Discount Badge / Subtitle</label>
                    <input type="text" name="discount_label" value="{{ $flashSale ? $flashSale->discount_label : 'Up to 30% OFF' }}" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
                </div>
            </div>

            <div class="flex items-center space-x-3 pt-2">
                <input type="checkbox" id="status" name="status" {{ ($flashSale && $flashSale->status) ? 'checked' : '' }} class="w-4 h-4 text-brass rounded border-border focus:ring-brass bg-black/40">
                <label for="status" class="text-xs font-bold text-gray-300 uppercase tracking-wider">Enable Flash Sale Campaign on Homepage</label>
            </div>

            <!-- Select Products for Flash Sale -->
            <div class="pt-4 border-t border-border">
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-3">
                    Select Flash Sale Products
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-80 overflow-y-auto p-2 bg-black/20 rounded-xl border border-border">
                    @foreach($allProducts as $product)
                        <label class="flex items-center space-x-3 p-3 rounded-lg bg-surface border border-border hover:border-brass/30 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="product_ids[]" 
                                value="{{ $product->id }}" 
                                {{ $product->is_flash_deal ? 'checked' : '' }}
                                class="w-4 h-4 text-brass rounded border-border focus:ring-brass"
                            >
                            <img src="{{ $product->images->first()->path ?? '' }}" class="w-10 h-10 object-cover rounded border border-border">
                            <div class="truncate">
                                <div class="text-xs font-bold text-[#F5EFE4] truncate">{{ $product->getLocalized('name', 'en') }}</div>
                                <div class="text-[10px] text-brass font-mono">${{ $product->price }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-6 py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-lg transition-colors">
                    Save Campaign Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
