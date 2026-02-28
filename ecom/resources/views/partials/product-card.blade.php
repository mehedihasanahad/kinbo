@php
    $t = $product->getTranslation('en');
    $hasDiscount = $product->is_on_sale;
    $currentPrice = $product->current_price;
    $badge = $badge ?? null;
@endphp

<div class="bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-indigo-200 hover:shadow-lg transition-all duration-200 group flex flex-col">

    {{-- Image area --}}
    <div class="relative bg-gray-50 aspect-square flex items-center justify-center overflow-hidden">
        @if($product->primaryImage)
            <img src="{{ asset('storage/' . $product->primaryImage->path) }}"
                alt="{{ $t?->name ?? $product->sku }}"
                class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-20 h-20 bg-gray-200 rounded-xl flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        {{-- Badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1">
            @if($hasDiscount)
                @php $pct = round((($product->price - $product->sale_price) / $product->price) * 100); @endphp
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">-{{ $pct }}%</span>
            @elseif($badge)
                <span class="bg-indigo-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $badge }}</span>
            @endif

            @if($product->is_low_stock)
                <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2 py-0.5 rounded-full">Low Stock</span>
            @endif
        </div>

        {{-- Wishlist button --}}
        <button class="absolute top-3 right-3 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-sm text-gray-400 hover:text-red-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </button>
    </div>

    {{-- Info --}}
    <div class="p-4 flex flex-col flex-1">
        @if($product->brand)
            <p class="text-xs text-indigo-500 font-medium mb-0.5">{{ $product->brand->name }}</p>
        @endif
        <h4 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 flex-1">
            {{ $t?->name ?? $product->sku }}
        </h4>

        {{-- Rating (static placeholder until avg rating is computed) --}}
        <div class="flex items-center gap-1 mb-2">
            <div class="flex">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
        </div>

        {{-- Price + Add to Cart --}}
        <div class="flex items-center justify-between mt-auto">
            <div>
                <span class="text-base font-bold text-gray-900">৳{{ number_format($currentPrice, 0) }}</span>
                @if($hasDiscount)
                    <span class="text-xs text-gray-400 line-through ml-1">৳{{ number_format($product->price, 0) }}</span>
                @endif
            </div>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Add
            </button>
        </div>
    </div>
</div>
