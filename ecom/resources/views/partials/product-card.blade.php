@php
    $locale      = app()->getLocale();
    $t           = $product->getTranslation($locale) ?? $product->getTranslation('en');
    $hasDiscount = $product->is_on_sale;
    $currentPrice = $product->current_price;
    $badge       = $badge ?? null;
    $productName = $t?->name ?? $product->sku;
    $productSlug = $t?->slug ?? ($product->getTranslation('en')?->slug ?? $product->sku);
@endphp

<div class="product-card bg-white rounded-2xl overflow-hidden border border-gray-100
            hover:border-primary-300 hover:shadow-xl transition-all duration-300 group">

    {{-- Clickable area: image + body ── --}}
    <a href="{{ route('product.show', $productSlug) }}" class="block">

        {{-- ── Fixed-height image area ── --}}
        <div class="product-card-image relative bg-gray-50 overflow-hidden">
            @if($product->primaryImage)
                <img src="{{ asset('storage/' . $product->primaryImage->path) }}"
                     alt="{{ $productName }}"
                     class="w-full h-full object-cover object-center
                            group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0
                                 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0
                                 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif

            {{-- Badges (top-left) --}}
            <div class="absolute top-2.5 left-2.5 flex flex-col gap-1">
                @if($hasDiscount)
                    @php $pct = round((($product->price - $product->sale_price) / $product->price) * 100); @endphp
                    <span class="bg-red-500 text-white text-[11px] font-bold px-2 py-0.5 rounded-full leading-tight">
                        -{{ $pct }}%
                    </span>
                @elseif($badge)
                    <span class="bg-primary-600 text-white text-[11px] font-bold px-2 py-0.5 rounded-full leading-tight">
                        {{ $badge === 'New' ? __('front.new_badge') : $badge }}
                    </span>
                @endif

                @if($product->is_low_stock)
                    <span class="bg-amber-100 text-amber-700 text-[11px] font-semibold px-2 py-0.5 rounded-full leading-tight">
                        {{ __('front.low_stock') }}
                    </span>
                @endif
            </div>

            {{-- Wishlist button (top-right, appears on hover) --}}
            <button onclick="event.preventDefault()"
                    class="absolute top-2.5 right-2.5 w-8 h-8 bg-white/90 hover:bg-white rounded-full
                           flex items-center justify-center opacity-0 group-hover:opacity-100
                           transition-all duration-200 shadow text-gray-400 hover:text-red-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0
                             00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>
        </div>

        {{-- ── Card body (flex-grow) ── --}}
        <div class="product-card-body">

            {{-- Brand --}}
            @if($product->brand)
                <p class="text-xs text-primary-600 font-medium mb-0.5 truncate">
                    {{ $product->brand->name }}
                </p>
            @endif

            {{-- Product name — exactly 2 lines max, ellipsis after --}}
            <h4 class="product-card-name text-sm font-semibold text-gray-800 mb-2">
                {{ $productName }}
            </h4>

            {{-- Star rating --}}
            <div class="flex items-center gap-1 mb-3">
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0
                                     00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0
                                     00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54
                                     1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0
                                     00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0
                                     00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
            </div>

            {{-- Price --}}
            <div class="product-card-footer flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <span class="text-base font-bold text-gray-900">
                        ৳{{ number_format($currentPrice, 0) }}
                    </span>
                    @if($hasDiscount)
                        <span class="text-xs text-gray-400 line-through ml-1">
                            ৳{{ number_format($product->price, 0) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </a>

    {{-- ── Add to Cart (outside the <a> tag) ── --}}
    <div class="px-4 pb-4">
        @auth
            @if($product->is_in_stock)
                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit"
                            class="w-full bg-primary-600 hover:bg-primary-700 active:bg-primary-800
                                   text-white text-xs font-semibold px-3 py-2 rounded-xl
                                   transition-colors duration-150 flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184
                                     1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ __('front.add_to_cart') }}
                    </button>
                </form>
            @else
                <button disabled
                        class="w-full bg-gray-200 text-gray-400 text-xs font-semibold px-3 py-2 rounded-xl
                               cursor-not-allowed flex items-center justify-center gap-1.5">
                    {{ __('front.out_of_stock') }}
                </button>
            @endif
        @else
            <a href="{{ route('login') }}"
               class="block w-full text-center bg-primary-600 hover:bg-primary-700
                      text-white text-xs font-semibold px-3 py-2 rounded-xl
                      transition-colors duration-150">
                {{ __('front.add_to_cart') }}
            </a>
        @endauth
    </div>
</div>
