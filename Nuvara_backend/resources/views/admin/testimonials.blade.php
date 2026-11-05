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
            <table id="testimonials-table" class="w-full text-left border-collapse dataTable">
                <thead>
                    <tr class="bg-surface text-gray-400 text-[10px] uppercase font-bold tracking-wider border-b border-border">
                        <th class="p-5 w-12 text-center">#</th>
                        <th class="p-5">Author</th>
                        <th class="p-5">Rating</th>
                        <th class="p-5">Quote / Review</th>
                        <th class="p-5">Featured</th>
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
        $('#testimonials-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.testimonials') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-mono text-xs text-gray-500' },
                { data: 'author', name: 'name' },
                { data: 'rating_stars', name: 'rating' },
                { data: 'quote_text', name: 'quote' },
                { data: 'featured_badge', name: 'is_featured' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right' }
            ],
            order: [[1, 'asc']],
            language: {
                search: "SEARCH:",
                searchPlaceholder: "Search testimonials...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ testimonials",
                paginate: {
                    previous: "‹ Prev",
                    next: "Next ›"
                }
            }
        });
    });
</script>
@endpush
</div>
@endsection