@extends('layouts.app')

@section('title', __('front.request_return') . ' — ' . config('app.name'))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-2xl font-bold text-gray-900 mb-8">{{ __('front.my_account') }}</h1>

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Sidebar --}}
        @include('account.partials.sidebar')

        {{-- Main --}}
        <div class="flex-1 min-w-0">

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('orders.show', $order) }}"
                   class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h2 class="text-lg font-bold text-gray-900">{{ __('front.request_return') }}</h2>
                <span class="text-sm text-gray-400">{{ $order->order_number }}</span>
            </div>

            {{-- Flash --}}
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Already submitted --}}
            @if($order->returnRequest)
                @php
                    $ret = $order->returnRequest;
                    $statusColors = [
                        'pending'  => 'bg-amber-100 text-amber-700',
                        'approved' => 'bg-emerald-100 text-emerald-700',
                        'rejected' => 'bg-red-100 text-red-700',
                    ];
                @endphp
                <div class="bg-white border border-gray-100 rounded-2xl p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-900">{{ __('front.return_request_status') }}</h3>
                        <span class="text-xs px-3 py-1 rounded-full font-semibold {{ $statusColors[$ret->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ __('front.return_status_' . $ret->status) }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-600 space-y-3">
                        <div>
                            <p class="text-xs text-gray-400 mb-1">{{ __('front.return_reason') }}</p>
                            <p class="bg-gray-50 rounded-xl px-4 py-3 leading-relaxed">{{ $ret->reason }}</p>
                        </div>
                        @if($ret->admin_notes)
                            <div>
                                <p class="text-xs text-gray-400 mb-1">{{ __('front.return_admin_notes') }}</p>
                                <p class="bg-gray-50 rounded-xl px-4 py-3 leading-relaxed">{{ $ret->admin_notes }}</p>
                            </div>
                        @endif
                        <p class="text-xs text-gray-400">{{ __('front.submitted_on') }}: {{ $ret->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>

            @else
                {{-- Return policy notice --}}
                <div class="bg-blue-50 border border-blue-200 rounded-2xl px-5 py-4 mb-6 flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @php
                        $windowDays  = (int) \App\Models\Setting::get('return_window_days', '7');
                        $refundDays  = (int) \App\Models\Setting::get('refund_days', '5');
                    @endphp
                    <div>
                        <p class="text-sm font-semibold text-blue-800">
                            {{ $windowDays }}-{{ __('front.return_policy_title') }}
                        </p>
                        <p class="text-sm text-blue-700 mt-0.5">
                            {{ __('front.return_policy_desc_dynamic', ['days' => $windowDays, 'refund_days' => $refundDays]) }}
                        </p>
                    </div>
                </div>

                {{-- Order summary --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-6 mb-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('front.order_items') }}</h3>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-3">
                                @php $img = $item->product?->primaryImage; @endphp
                                @if($img)
                                    <img src="{{ asset('storage/' . $img->path) }}"
                                         class="w-12 h-12 rounded-xl object-cover border border-gray-100 shrink-0"
                                         alt="{{ $item->product_name }}">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 shrink-0"></div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800">{{ $item->product_name }}</p>
                                    @if($item->variant_label)
                                        <p class="text-xs text-gray-500">{{ $item->variant_label }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">{{ $item->quantity }} × ৳{{ number_format($item->unit_price, 0) }}</p>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 shrink-0">৳{{ number_format($item->subtotal, 0) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Return form --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('front.return_reason_label') }}</h3>

                    <form action="{{ route('orders.return.store', $order) }}" method="POST">
                        @csrf

                        <div class="mb-5">
                            <textarea name="reason" rows="5" required minlength="20" maxlength="1000"
                                      placeholder="{{ __('front.return_reason_placeholder') }}"
                                      class="w-full px-4 py-3 border {{ $errors->has('reason') ? 'border-red-400' : 'border-gray-200' }} rounded-xl text-sm focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all resize-none">{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 mt-1">{{ __('front.return_reason_hint') }}</p>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('orders.show', $order) }}"
                               class="flex-1 flex items-center justify-center py-3 text-sm font-semibold border-2 border-gray-200 text-gray-600 hover:border-gray-300 rounded-xl transition-colors">
                                {{ __('front.cancel') }}
                            </a>
                            <button type="submit"
                                    class="flex-1 py-3 text-sm font-semibold bg-primary-600 hover:bg-primary-700 text-white rounded-xl transition-colors">
                                {{ __('front.submit_return_request') }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
