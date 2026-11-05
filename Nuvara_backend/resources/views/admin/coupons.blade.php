@extends('layouts.admin')

@section('title', 'Admin - Coupons')
@section('header_title', 'Coupons')

@section('content')
<div class="space-y-8">
    <!-- Collapsible Add Coupon Form -->
    <div class="glass-card rounded-2xl p-6 shadow-md text-left transition-all duration-300 hover:border-brass/25">
        <div class="flex justify-between items-center mb-6 border-b border-border pb-3">
            <h3 class="text-xs font-bold text-brass uppercase tracking-wider">
                Create Voucher Code
            </h3>
            <span class="text-[10px] text-gray-500 font-mono">Active: {{ count($coupons) }} Coupons</span>
        </div>
        
        <form action="{{ route('admin.coupons.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-6">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Coupon Code</label>
                <input type="text" name="code" required placeholder="SUMMER20" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200 uppercase">
            </div>
            
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Discount Type</label>
                <select name="type" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-400 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass">
                    <option value="percent">Percentage Off (%)</option>
                    <option value="flat">Flat Cash Discount ($)</option>
                    <option value="free_shipping">Free Shipping</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Discount Value</label>
                <input type="number" step="0.01" name="value" required placeholder="15.00" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Min. Order Limit ($)</label>
                <input type="number" step="0.01" name="min_order" required placeholder="50.00" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Expires At</label>
                <input type="date" name="expires_at" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-gray-400 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Coupon Status</label>
                <select name="status" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-400 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass">
                    <option value="active">Active (Usable)</option>
                    <option value="inactive">Inactive (Disabled)</option>
                </select>
            </div>

            <div class="md:col-span-6 pt-2">
                <button type="submit" class="px-6 py-3.5 bg-brass hover:bg-brass-dark text-[#0b0b0b] font-bold uppercase text-xs tracking-widest rounded-lg transition-colors active:scale-95 duration-200">
                    Create Coupon
                </button>
            </div>
        </form>
    </div>

    <!-- Coupons Table -->
    <div class="glass-card rounded-2xl overflow-hidden shadow-lg border border-border">
        <div class="overflow-x-auto">
            <table id="coupons-table" class="w-full text-left border-collapse dataTable">
                <thead>
                    <tr class="bg-surface text-gray-400 text-[10px] uppercase font-bold tracking-wider border-b border-border">
                        <th class="p-5 w-12 text-center">#</th>
                        <th class="p-5">Coupon Code</th>
                        <th class="p-5">Discount Type</th>
                        <th class="p-5">Discount Value</th>
                        <th class="p-5">Minimum Order</th>
                        <th class="p-5">Expiration Date</th>
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
        $('#coupons-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.coupons') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-mono text-xs text-gray-500' },
                { data: 'code_badge', name: 'code' },
                { data: 'type_formatted', name: 'type' },
                { data: 'value_formatted', name: 'value' },
                { data: 'min_order_formatted', name: 'min_order' },
                { data: 'expires_formatted', name: 'expires_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right' }
            ],
            order: [[1, 'asc']],
            language: {
                search: "SEARCH:",
                searchPlaceholder: "Search coupon codes...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ coupons",
                paginate: {
                    previous: "‹ Prev",
                    next: "Next ›"
                }
            }
        });

        // Delegated event listener for edit modal
        $(document).on('click', '.btn-edit-coupon', function() {
            var coup = $(this).data('coupon');
            if (typeof coup === 'string') {
                try { coup = JSON.parse(coup); } catch(e){}
            }
            openEditModal(coup);
        });
    });
</script>
@endpush

<!-- Edit Coupon Modal Backdrop Overlay -->
<div id="edit-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden animate-fade-in">
    <div class="glass-card w-full max-w-xl rounded-2xl p-6 md:p-8 relative shadow-2xl text-left border border-brass/25">
        <button onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-500 hover:text-white text-lg">✕</button>
        <h3 class="text-sm font-bold text-brass uppercase tracking-wider mb-6 border-b border-border pb-3">
            Edit Coupon Configuration
        </h3>
        
        <form id="edit-form" action="" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Coupon Code</label>
                <input type="text" id="edit-code" name="code" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200 uppercase">
            </div>
            
            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Discount Type</label>
                <select id="edit-type" name="type" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-400 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass">
                    <option value="percent">Percentage Off (%)</option>
                    <option value="flat">Flat Cash Discount ($)</option>
                    <option value="free_shipping">Free Shipping</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Discount Value</label>
                <input type="number" step="0.01" id="edit-value" name="value" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Min. Order Limit ($)</label>
                <input type="number" step="0.01" id="edit-min-order" name="min_order" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass text-gray-200">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Expires At</label>
                <input type="date" id="edit-expires-at" name="expires_at" class="w-full px-4 py-2.5 rounded-lg border border-border bg-black/40 text-gray-400 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-brass uppercase tracking-wider mb-2">Visibility Status</label>
                <select id="edit-status" name="status" required class="w-full px-4 py-2.5 rounded-lg border border-border bg-surface text-gray-400 text-sm focus:outline-none focus:border-brass focus:ring-1 focus:ring-brass">
                    <option value="active">Active (Usable)</option>
                    <option value="inactive">Inactive (Disabled)</option>
                </select>
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
    function openEditModal(coup) {
        document.getElementById('edit-form').action = '/admin/coupons/' + coup.id + '/update';
        document.getElementById('edit-code').value = coup.code || '';
        document.getElementById('edit-type').value = coup.type || 'percent';
        document.getElementById('edit-value').value = coup.value || '';
        document.getElementById('edit-min-order').value = coup.min_order || '';
        if (coup.expires_at) {
            document.getElementById('edit-expires-at').value = coup.expires_at.substring(0, 10);
        } else {
            document.getElementById('edit-expires-at').value = '';
        }
        document.getElementById('edit-status').value = coup.status || 'active';
        document.getElementById('edit-modal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }
</script>
@endsection
