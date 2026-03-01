@extends('layouts.app')

@section('title', __('front.my_orders') . ' — ' . config('app.name'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex items-center gap-3 mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('front.my_orders') }}</h1>
        @if($orders->total() > 0)
            <span class="text-sm text-gray-400">({{ $orders->total() }})</span>
        @endif
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-20">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ __('front.no_orders') }}</h2>
            <a href="{{ route('home') }}"
               class="mt-6 inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('front.continue_shopping') }}
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                @php
                    $statusColors = [
                        'pending'    => 'bg-amber-100 text-amber-700',
                        'processing' => 'bg-blue-100 text-blue-700',
                        'shipped'    => 'bg-indigo-100 text-indigo-700',
                        'delivered'  => 'bg-emerald-100 text-emerald-700',
                        'cancelled'  => 'bg-red-100 text-red-700',
                        'returned'   => 'bg-gray-100 text-gray-600',
                    ];
                    $paymentColors = [
                        'unpaid'               => 'bg-gray-100 text-gray-600',
                        'pending_verification' => 'bg-amber-100 text-amber-700',
                        'paid'                 => 'bg-emerald-100 text-emerald-700',
                        'refunded'             => 'bg-purple-100 text-purple-700',
                        'failed'               => 'bg-red-100 text-red-700',
                    ];
                @endphp
                <div class="bg-white border border-gray-100 rounded-2xl p-5 hover:border-gray-200 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                        {{-- Order info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-bold text-gray-900">{{ $order->order_number }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ __('front.status_' . $order->status) }}
                                </span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ __('front.payment_' . $order->payment_status) }}
                                </span>
                            </div>
                            <div class="mt-1.5 flex items-center gap-3 text-xs text-gray-400">
                                <span>{{ $order->created_at->format('d M Y') }}</span>
                                <span>·</span>
                                <span>{{ trans_choice('front.items_count', $order->items->count(), ['count' => $order->items->count()]) }}</span>
                                <span>·</span>
                                <span>{{ __('front.payment_method_' . $order->payment_method) }}</span>
                            </div>
                        </div>

                        {{-- Total + action --}}
                        <div class="flex items-center gap-4 shrink-0">
                            <div class="text-right">
                                <p class="text-xs text-gray-400">{{ __('front.total') }}</p>
                                <p class="text-base font-bold text-gray-900">৳{{ number_format($order->total_amount, 0) }}</p>
                            </div>
                            <a href="{{ route('orders.show', $order) }}"
                               class="text-sm font-semibold text-primary-600 hover:text-primary-700 border border-primary-200 hover:border-primary-400 px-4 py-2 rounded-xl transition-colors">
                                {{ __('front.view_order') }}
                            </a>
                        </div>
                    </div>

                    {{-- Item thumbnails (up to 4) --}}
                    @if($order->items->count() > 0)
                        <div class="mt-4 flex items-center gap-2">
                            @foreach($order->items->take(4) as $item)
                                @php $img = $item->product?->primaryImage; @endphp
                                @if($img)
                                    <img src="{{ asset('storage/' . $img->path) }}"
                                         alt="{{ $item->product_name }}"
                                         class="w-12 h-12 rounded-xl object-cover border border-gray-100">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            @endforeach
                            @if($order->items->count() > 4)
                                <span class="text-xs text-gray-400 ml-1">+{{ $order->items->count() - 4 }} more</span>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $orders->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
