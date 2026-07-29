@extends('layouts.admin')

@section('title', 'Admin - Customer Testimonials')
@section('header_title', 'Customer Testimonials')

@section('content')
<div class="space-y-8">
    <!-- Create Testimonial Form -->
    <div class="glass-card rounded-2xl p-6 shadow-md text-left transition-all duration-300 hover:border-brass/25">
        <div class="flex justify-between items-center mb-6 border-b border-border pb-3">
            <h3 class="text-xs font-bold text-brass uppercase tracking-wider">
                Add Customer Review / Testimonial
            </h3>
            <span class="text-[10px] text-gray-500 font-mono">Total Reviews: {{ count($testimonials) }}</span>
        </div>

        <form action="{{ route('admin.testimonials.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Customer Name</label>
                <input type="text" name="name" required placeholder="Name" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Star Rating (1 - 5)</label>
                <select name="rating" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-300 text-sm focus:outline-none focus:border-brass">
                    <option value="5">★★★★★ (5 Stars)</option>
                    <option value="4">★★★★☆ (4 Stars)</option>
                    <option value="3">★★★☆☆ (3 Stars)</option>
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Testimonial Quote (EN)</label>
                <textarea name="quote_en" required rows="3" placeholder="Nuvara completely changed my online shopping experience. Shipping was fast and quality was top-notch." class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200"></textarea>
            </div>

            <div class="md:col-span-3 pt-2">
                <button type="submit" class="px-6 py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-lg transition-colors">
                    Add Customer Testimonial
                </button>
            </div>
        </form>
    </div>

    <!-- Testimonials List -->
    <div class="glass-card rounded-2xl overflow-hidden shadow-lg border border-border">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface text-gray-400 text-[10px] uppercase font-bold tracking-wider border-b border-border">
                        <th class="p-5">Author</th>
                        <th class="p-5">Rating</th>
                        <th class="p-5">Quote / Review</th>
                        <th class="p-5">Featured</th>
                        <th class="p-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border text-sm">
                    @foreach($testimonials as $test)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="p-5 font-bold text-[#F5EFE4]">
                            {{ $test->name }}
                        </td>
                        <td class="p-5 text-amber-400 font-bold">
                            {{ str_repeat('★', $test->rating) }}
                        </td>
                        <td class="p-5 text-xs text-gray-300 italic max-w-md">
                            "{{ $test->getLocalized('quote', 'en') }}"
                        </td>
                        <td class="p-5">
                            <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded {{ $test->is_featured ? 'bg-emerald-500/20 text-emerald-400' : 'bg-gray-500/20 text-gray-400' }}">
                                {{ $test->is_featured ? 'Featured' : 'Standard' }}
                            </span>
                        </td>
                        <td class="p-5 text-right">
                            <form action="{{ route('admin.testimonials.delete', $test->id) }}" method="POST" class="inline-block" onsubmit="return confirmDelete(event, '{{ addslashes($test->name) }}\'s review');">
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