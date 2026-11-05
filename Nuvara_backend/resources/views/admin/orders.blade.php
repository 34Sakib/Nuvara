@extends('layouts.admin')

@section('title', 'Admin - Orders')
@section('header_title', 'Orders')

@section('content')
<div class="space-y-8">
    <div class="glass-card rounded-2xl overflow-hidden shadow-lg border border-border">
        <div class="overflow-x-auto">
            <table id="orders-table" class="w-full text-left border-collapse dataTable">
                <thead>
                    <tr class="bg-surface text-gray-400 text-[10px] uppercase font-bold tracking-wider border-b border-border">
                        <th class="p-5 w-12 text-center">#</th>
                        <th class="p-5">Order Code</th>
                        <th class="p-5">Customer & Destination</th>
                        <th class="p-5">Checkout Date</th>
                        <th class="p-5">Items Ordered</th>
                        <th class="p-5">Total Paid</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 text-right">Update Progress</th>
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
        $('#orders-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.orders') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-mono text-xs text-gray-500' },
                { data: 'order_code', name: 'order_number' },
                { data: 'customer', name: 'shipping_name' },
                { data: 'date', name: 'created_at' },
                { data: 'items', name: 'items', orderable: false, searchable: false },
                { data: 'total', name: 'total' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-right' }
            ],
            order: [[3, 'desc']],
            language: {
                search: "SEARCH:",
                searchPlaceholder: "Search orders, customer...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ orders",
                paginate: {
                    previous: "‹ Prev",
                    next: "Next ›"
                }
            }
        });
    });
</script>
@endpush
@endsection
