@extends('layouts.app')

@section('title', __('front.wishlist') . ' — ' . config('app.name', 'ShopZone'))

@section('content')
@php $locale = app()->getLocale(); @endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Page heading --}}
    <div class="flex items-center gap-3 mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('front.wishlist') }}</h1>
        @if($wishlistItems->count() > 0)
            <span class="text-sm text-gray-500 font-normal">
                ({{ $wishlistItems->count() }} {{ __('front.items') }})
            </span>
        @endif
    </div>

    @if(session('cart_success'))
        <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('cart_success') }}
        </div>
    @endif

    @if($wishlistItems->isEmpty())
        {{-- Empty state --}}
        <div class="text-center py-20">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ __('front.wishlist_empty') }}</h2>
            <p class="text-gray-500 text-sm mb-8">{{ __('front.wishlist_empty_sub') }}</p>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors text-sm">
                {{ __('front.continue_shopping') }}
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($wishlistItems as $item)
                @php
                    $product = $item->product;
                    $translation = $product->getTranslation($locale) ?? $product->getTranslation('en');
                    $productName = $translation?->name ?? $product->sku;
                    $productSlug = $translation?->slug ?? $product->sku;
                    $image = $product->primaryImage?->path;
                @endphp
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group"
                     id="wishlist-item-{{ $product->id }}">

                    {{-- Product image --}}
                    <a href="{{ route('product.show', $productSlug) }}" class="block relative aspect-square overflow-hidden bg-gray-50">
                        @if($image)
                            <img src="{{ asset('storage/' . $image) }}"
                                 alt="{{ $productName }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Sale badge --}}
                        @if($product->is_on_sale)
                            <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide">
                                {{ __('front.sale') }}
                            </span>
                        @endif
                    </a>

                    {{-- Product info --}}
                    <div class="p-4">
                        <a href="{{ route('product.show', $productSlug) }}"
                           class="block text-sm font-semibold text-gray-800 hover:text-primary-600 transition-colors line-clamp-2 mb-2">
                            {{ $productName }}
                        </a>

                        {{-- Price --}}
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-base font-bold text-primary-600">
                                ৳{{ number_format($product->current_price, 2) }}
                            </span>
                            @if($product->is_on_sale)
                                <span class="text-xs text-gray-400 line-through">
                                    ৳{{ number_format($product->price, 2) }}
                                </span>
                            @endif
                        </div>

                        {{-- Stock status --}}
                        <div class="mb-3">
                            @if($product->is_in_stock)
                                <span class="text-xs text-green-600 font-medium">● {{ __('front.in_stock') }}</span>
                            @else
                                <span class="text-xs text-red-500 font-medium">● {{ __('front.out_of_stock') }}</span>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            {{-- Move to cart --}}
                            @if($product->is_in_stock)
                                <form action="{{ route('wishlist.move-to-cart', $product) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center justify-center gap-1.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold px-3 py-2.5 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ __('front.move_to_cart') }}
                                    </button>
                                </form>
                            @else
                                <span class="flex-1 flex items-center justify-center text-xs text-gray-400 bg-gray-50 border border-gray-200 rounded-lg py-2.5">
                                    {{ __('front.out_of_stock') }}
                                </span>
                            @endif

                            {{-- Remove from wishlist --}}
                            <button type="button"
                                    onclick="removeFromWishlist({{ $product->id }}, '{{ route('wishlist.destroy', $product) }}')"
                                    class="w-9 h-9 flex items-center justify-center border border-gray-200 hover:border-red-300 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-lg transition-colors shrink-0"
                                    title="{{ __('front.remove') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Continue shopping --}}
        <div class="mt-10 text-center">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('front.continue_shopping') }}
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function removeFromWishlist(productId, url) {
    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
    .then(res => res.json())
    .then(data => {
        // Remove the card from DOM
        const card = document.getElementById('wishlist-item-' + productId);
        if (card) {
            card.style.opacity = '0';
            card.style.transition = 'opacity 0.2s';
            setTimeout(() => {
                card.remove();
                updateWishlistBadge(data.wishlist_count);
                // If no items left, reload to show empty state
                const remaining = document.querySelectorAll('[id^="wishlist-item-"]');
                if (remaining.length === 0) location.reload();
            }, 200);
        }
    });
}

function updateWishlistBadge(count) {
    const badge = document.querySelector('.wishlist-badge');
    if (!badge) return;
    if (count > 0) {
        badge.textContent = count > 9 ? '9+' : count;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
}
</script>
@endpush

@endsection
