@extends('layouts.admin')

@section('title', 'Admin - Orders')
@section('header_title', 'Orders')

@section('content')
<div class="space-y-8">
    <div class="glass-card rounded-2xl overflow-hidden shadow-lg border border-border">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface text-gray-400 text-[10px] uppercase font-bold tracking-wider border-b border-border">
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
                    @forelse($orders as $ord)
                        <tr class="hover:bg-white/5 transition-colors duration-200">
                            <td class="p-5 font-mono font-bold text-brass text-sm">
                                #{{ $ord->order_number }}
                            </td>
                            <td class="p-5">
                                <div class="font-bold text-[#F5EFE4] text-sm">{{ $ord->shipping_name }}</div>
                                <div class="text-[10px] text-gray-500 font-semibold mt-0.5">{{ $ord->shipping_address }}, {{ $ord->shipping_city }} ({{ $ord->shipping_zip }})</div>
                            </td>
                            <td class="p-5 text-gray-400 text-xs font-semibold">
                                {{ $ord->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="p-5">
                                <div class="space-y-1">
                                    @foreach($ord->items as $item)
                                        <div class="text-xs text-gray-400 font-medium">
                                            <span class="text-gray-300 font-semibold">{{ $item->product ? $item->product->getLocalized('name', 'en') : 'Product' }}</span> 
                                            x <span class="text-brass">{{ $item->quantity }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="p-5 font-black text-[#F5EFE4] text-base font-serif">
                                ${{ number_format($ord->total, 2) }}
                            </td>
                            <td class="p-5">
                                <span class="px-2.5 py-1 text-[9px] font-black uppercase rounded-full tracking-wider
                                    @if($ord->status === 'delivered') bg-green-950/30 border border-green-900/40 text-green-400
                                    @elseif($ord->status === 'shipped') bg-indigo-950/30 border border-indigo-900/40 text-indigo-400
                                    @else bg-yellow-950/30 border border-yellow-900/40 text-yellow-500 @endif
                                ">
                                    {{ $ord->status }}
                                </span>
                            </td>
                            <td class="p-5 text-right">
                                <form action="{{ route('admin.orders.status', $ord->id) }}" method="POST" class="inline-flex items-center">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="bg-surface text-gray-400 border border-border rounded-lg text-xs font-bold px-2.5 py-1.5 focus:outline-none focus:border-brass/65 focus:ring-1 focus:ring-brass/65">
                                        <option value="processing" {{ $ord->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ $ord->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $ord->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-10 text-center text-gray-500 italic">
                                No checkouts recorded in database yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
