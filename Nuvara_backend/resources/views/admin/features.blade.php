@extends('layouts.admin')

@section('title', 'Admin - Trust Features')
@section('header_title', 'Trust Features')

@section('content')
<div class="space-y-8">
    <!-- Create Feature Form -->
    <div class="glass-card rounded-2xl p-6 shadow-md text-left transition-all duration-300 hover:border-brass/25">
        <div class="flex justify-between items-center mb-6 border-b border-border pb-3">
            <h3 class="text-xs font-bold text-brass uppercase tracking-wider">
                Add Trust Bar Feature Item
            </h3>
            <span class="text-[10px] text-gray-500 font-mono">Total Features: {{ count($features) }}</span>
        </div>
        
        <form action="{{ route('admin.features.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Feature Key</label>
                <input type="text" name="feature_key" required placeholder="free_shipping" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Title (EN)</label>
                <input type="text" name="title_en" required placeholder="Free Shipping" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Subtitle (EN)</label>
                <input type="text" name="sub_en" required placeholder="Free shipping on orders over $150" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Lucide Icon Name</label>
                <select name="icon" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-300 text-sm focus:outline-none focus:border-brass">
                    <option value="Truck">Truck (Free Shipping)</option>
                    <option value="ShieldCheck">ShieldCheck (Secure Payment)</option>
                    <option value="RefreshCw">RefreshCw (Easy Returns)</option>
                    <option value="Headphones">Headphones (24/7 Support)</option>
                    <option value="Sparkles">Sparkles (Quality Guarantee)</option>
                    <option value="Award">Award (Certified Authentic)</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Icon Text Color Class</label>
                <input type="text" name="icon_color" placeholder="text-emerald-500" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Background Color Class</label>
                <input type="text" name="bg_color" placeholder="bg-emerald-500/10" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div class="md:col-span-3 pt-2">
                <button type="submit" class="px-6 py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-lg transition-colors">
                    Add Feature Item
                </button>
            </div>
        </form>
    </div>

    <!-- Features List -->
    <div class="glass-card rounded-2xl overflow-hidden shadow-lg border border-border">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface text-gray-400 text-[10px] uppercase font-bold tracking-wider border-b border-border">
                        <th class="p-5">Icon</th>
                        <th class="p-5">Feature Title</th>
                        <th class="p-5">Subtitle</th>
                        <th class="p-5">Key</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm">
                    @foreach($features as $feat)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="p-5 font-mono text-xs text-brass">
                                {{ $feat->icon }}
                            </td>
                            <td class="p-5 font-bold text-[#F5EFE4]">
                                {{ $feat->getLocalized('title', 'en') }}
                            </td>
                            <td class="p-5 text-xs text-gray-400">
                                {{ $feat->getLocalized('sub', 'en') }}
                            </td>
                            <td class="p-5 font-mono text-xs text-gray-500">
                                {{ $feat->feature_key }}
                            </td>
                            <td class="p-5 text-right">
                                <form action="{{ route('admin.features.delete', $feat->id) }}" method="POST" class="inline-block" onsubmit="return confirmDelete(event, '{{ addslashes($feat->getLocalized('title', 'en')) }}');">
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
