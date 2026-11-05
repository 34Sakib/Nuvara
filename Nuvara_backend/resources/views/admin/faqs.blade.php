@extends('layouts.admin')

@section('title', 'Admin - FAQs Management')
@section('header_title', 'Frequently Asked Questions')

@section('content')
<div class="space-y-8 animate-fade-in text-left">
    
    <!-- Add New FAQ Card -->
    <div class="glass-card rounded-2xl p-6 md:p-8 shadow-xl border border-brass/20">
        <div class="flex items-center justify-between border-b border-border pb-4 mb-6">
            <h3 class="text-sm font-bold text-brass uppercase tracking-wider">
                Create New FAQ Entry
            </h3>
            <span class="text-[10px] text-gray-500 font-mono">Total FAQs: {{ count($faqs) }}</span>
        </div>

        <form action="{{ route('admin.faqs.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Category</label>
                <select name="category" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-400 text-sm focus:outline-none focus:border-brass">
                    <option value="general">General Questions</option>
                    <option value="shipping">Orders & Shipping</option>
                    <option value="returns">Returns & Refunds</option>
                    <option value="payment">Payments & Pricing</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="1" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Question (EN)</label>
                <input type="text" name="question_en" required placeholder="What countries do you ship to?" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Question (BN)</label>
                <input type="text" name="question_bn" required placeholder="কোন কোন দেশে শিপিং করা হয়?" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Answer (EN)</label>
                <textarea name="answer_en" rows="3" required placeholder="Full answer explanation..." class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200"></textarea>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Answer (BN)</label>
                <textarea name="answer_bn" rows="3" required placeholder="সম্পূর্ণ উত্তর সংক্ষেপ..." class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass text-gray-200"></textarea>
            </div>

            <div class="md:col-span-2 flex items-center justify-between pt-2">
                <label class="flex items-center space-x-2 text-xs text-gray-300 font-bold uppercase cursor-pointer">
                    <input type="checkbox" name="status" value="1" checked class="w-4 h-4 rounded text-brass bg-black border-border focus:ring-0">
                    <span>Active (Visible on Storefront)</span>
                </label>

                <button type="submit" class="px-6 py-3 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-lg transition-colors active:scale-95 duration-150">
                    Add FAQ Entry
                </button>
            </div>
        </form>
    </div>

    <!-- Existing FAQs Table Card -->
    <div class="glass-card rounded-2xl p-6 md:p-8 shadow-xl border border-brass/20">
        <h3 class="text-sm font-bold text-brass uppercase tracking-wider mb-6 border-b border-border pb-3">
            Active Storefront FAQs
        </h3>

        <div class="overflow-x-auto">
            <table id="faqs-table" class="w-full text-left border-collapse dataTable">
                <thead>
                    <tr class="border-b border-border text-[10px] uppercase font-bold text-brass tracking-wider">
                        <th class="p-5 w-12 text-center">#</th>
                        <th class="p-5">Category</th>
                        <th class="p-5">Question (EN / BN)</th>
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
        $('#faqs-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.faqs') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-mono text-xs text-gray-500' },
                { data: 'category_badge', name: 'category' },
                { data: 'question_view', name: 'question' },
                { data: 'status_badge', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right' }
            ],
            order: [[1, 'asc']],
            language: {
                search: "SEARCH:",
                searchPlaceholder: "Search FAQs...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ FAQs",
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
