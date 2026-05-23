@extends('layouts.app')

@php
    $isAllProducts = $category === null;
    $catName = $isSearch
        ? __('front.search_results', ['q' => $q])
        : ($isAllProducts
            ? __('front.all_products')
            : ($category->getTranslation($locale)?->name ?? $category->getTranslation('en')?->name ?? ''));
    $catMeta = ($isSearch || $isAllProducts)
        ? ''
        : ($category->getTranslation($locale)?->meta_description ?? $category->getTranslation('en')?->meta_description ?? '');
    $formAction = $isSearch ? route('shop.search') : route('shop.category');
    $resetUrl   = $isSearch
        ? route('shop.search', ['q' => $q])
        : ($isAllProducts ? route('shop.category') : route('shop.category', ['category' => $slug]));
@endphp

@section('title', $catName . ' — ' . config('app.name'))
@if($catMeta)
@section('meta_description', $catMeta)
@section('og_description', $catMeta)
@endif
@section('og_title', $catName . ' — ' . config('app.name'))

@push('scripts')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "ItemList",
    "name": "{{ addslashes($catName) }}",
    "url": "{{ url()->current() }}",
    "numberOfItems": {{ $products->total() }},
    "itemListElement": [
        @foreach($products as $i => $item)
        @php
            $t = $item->getTranslation(app()->getLocale()) ?? $item->getTranslation('en');
            $slug = $t?->slug ?? $item->sku;
            $img = $item->primaryImage ? asset('storage/'.$item->primaryImage->path) : null;
        @endphp
        {
            "@@type": "ListItem",
            "position": {{ $products->firstItem() + $i }},
            "item": {
                "@@type": "Product",
                "name": "{{ addslashes($t?->name ?? $item->sku) }}",
                "url": "{{ route('product.show', $slug) }}"
                @if($img),"image": "{{ $img }}"@endif
                @if($t?->short_description),"description": "{{ addslashes(strip_tags($t->short_description)) }}"@endif,
                "offers": {
                    "@@type": "Offer",
                    "priceCurrency": "BDT",
                    "price": "{{ $item->current_price }}",
                    "availability": "https://schema.org/{{ $item->is_in_stock ? 'InStock' : 'OutOfStock' }}"
                }
            }
        }{{ ! $loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endpush

@section('content')

{{-- ── Breadcrumb ───────────────────────────────────────────────────────── --}}
<nav class="bg-gray-50 border-b border-gray-100 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ol class="flex items-center gap-1.5 text-sm text-gray-500 flex-wrap">
            <li>
                <a href="{{ route('home') }}"
                   class="hover:text-primary-600 transition-colors">{{ __('front.home') }}</a>
            </li>
            <li><span class="text-gray-300">/</span></li>

            @if($isSearch)
                <li class="text-gray-900 font-medium">{{ __('front.search_results', ['q' => $q]) }}</li>
            @elseif(! $isAllProducts)
                <li>
                    <a href="{{ route('shop.category') }}"
                       class="hover:text-primary-600 transition-colors">{{ __('front.all_products') }}</a>
                </li>
                <li><span class="text-gray-300">/</span></li>

                @if($category->parent)
                    @php
                        $parentSlug = $category->parent->getTranslation($locale)?->slug
                            ?? $category->parent->getTranslation('en')?->slug;
                        $parentName = $category->parent->getTranslation($locale)?->name
                            ?? $category->parent->getTranslation('en')?->name;
                    @endphp
                    <li>
                        <a href="{{ !empty($parentSlug) ? route('shop.category', ['category' => $parentSlug]) : '#' }}"
                           class="hover:text-primary-600 transition-colors">{{ $parentName }}</a>
                    </li>
                    <li><span class="text-gray-300">/</span></li>
                @endif

                <li class="text-gray-900 font-medium">{{ $catName }}</li>
            @else
                <li class="text-gray-900 font-medium">{{ $catName }}</li>
            @endif
        </ol>
    </div>
</nav>

{{-- ── Main layout ──────────────────────────────────────────────────────── --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

    {{-- Mobile filter toggle --}}
    <button id="filter-toggle"
            type="button"
            class="lg:hidden flex items-center gap-2 mb-4 text-sm font-semibold text-gray-700
                   bg-white border border-gray-200 px-4 py-2 rounded-xl hover:border-primary-300
                   hover:text-primary-700 transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
        </svg>
        {{ __('front.filter_toggle') }}
        @if($priceMin !== null || $priceMax !== null)
            <span class="bg-primary-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full leading-none">
                {{ ($priceMin !== null ? 1 : 0) + ($priceMax !== null ? 1 : 0) }}
            </span>
        @endif
    </button>

    <div class="flex gap-6 lg:gap-8 items-start">

        {{-- ═══════════════════════════════════════
             SIDEBAR
        ════════════════════════════════════════ --}}
        <aside id="shop-sidebar"
               class="hidden lg:block lg:w-64 xl:w-72 shrink-0">
            <div class="lg:sticky lg:top-20 space-y-4">

                <form method="GET"
                      action="{{ $formAction }}"
                      id="filter-form">

                    {{-- Preserve search query, category, sort & view state across filter submissions --}}
                    @if($isSearch)
                        <input type="hidden" name="q" value="{{ $q }}">
                    @elseif(! $isAllProducts)
                        <input type="hidden" name="category" value="{{ $slug }}">
                    @endif
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="view" value="{{ $view }}">

                    {{-- ── Category Tree ── --}}
                    <div class="bg-white rounded-2xl border border-gray-100 p-4">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">
                            {{ __('front.filter_categories') }}
                        </h3>
                        <ul class="space-y-0.5">
                            @foreach($sidebarCategories as $rootCat)
                                @php
                                    $rootName = $rootCat->getTranslation($locale)?->name
                                        ?? $rootCat->getTranslation('en')?->name;
                                    $rootSlug = $rootCat->getTranslation($locale)?->slug
                                        ?? $rootCat->getTranslation('en')?->slug;
                                    $isRootActive = $category && (
                                        $rootCat->id === $category->id
                                        || $rootCat->children->contains('id', $category->id)
                                    );
                                @endphp
                                <li>
                                    <a href="{{ !empty($rootSlug) ? route('shop.category', ['category' => $rootSlug]) : '#' }}"
                                       class="flex items-center justify-between gap-2 px-2.5 py-1.5 rounded-xl
                                              text-sm transition-colors
                                              {{ $isRootActive ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <span>{{ $rootName }}</span>
                                        @if($rootCat->children->isNotEmpty())
                                            <svg class="w-3 h-3 shrink-0 {{ $isRootActive ? 'text-primary-500' : 'text-gray-400' }}"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                      d="{{ $isRootActive ? 'M19 9l-7 7-7-7' : 'M9 5l7 7-7 7' }}"/>
                                            </svg>
                                        @endif
                                    </a>

                                    {{-- Children: show when parent or child is active --}}
                                    @if($rootCat->children->isNotEmpty() && $isRootActive)
                                        <ul class="ml-3 mt-0.5 space-y-0.5 border-l-2 border-primary-100 pl-3">
                                            @foreach($rootCat->children as $child)
                                                @php
                                                    $childName = $child->getTranslation($locale)?->name
                                                        ?? $child->getTranslation('en')?->name;
                                                    $childSlug = $child->getTranslation($locale)?->slug
                                                        ?? $child->getTranslation('en')?->slug;
                                                @endphp
                                                <li>
                                                    <a href="{{ !empty($childSlug) ? route('shop.category', ['category' => $childSlug]) : '#' }}"
                                                       class="block px-2 py-1 rounded-lg text-xs transition-colors
                                                              {{ ($category && $child->id === $category->id)
                                                                  ? 'text-primary-700 font-semibold'
                                                                  : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50' }}">
                                                        {{ $childName }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- ── Price Range ── --}}
                    <div class="bg-white rounded-2xl border border-gray-100 p-4">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">
                            {{ __('front.filter_price') }}
                        </h3>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-medium">৳</span>
                                <input type="number"
                                       name="price_min"
                                       value="{{ $priceMin }}"
                                       placeholder="{{ __('front.price_min') }}"
                                       min="0"
                                       class="w-full pl-6 pr-2 py-1.5 border border-gray-200 rounded-lg text-sm
                                              focus:border-primary-400 focus:ring-1 focus:ring-primary-100
                                              focus:outline-none">
                            </div>
                            <span class="text-gray-300 text-xs shrink-0">—</span>
                            <div class="relative flex-1">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-medium">৳</span>
                                <input type="number"
                                       name="price_max"
                                       value="{{ $priceMax }}"
                                       placeholder="{{ __('front.price_max') }}"
                                       min="0"
                                       class="w-full pl-6 pr-2 py-1.5 border border-gray-200 rounded-lg text-sm
                                              focus:border-primary-400 focus:ring-1 focus:ring-primary-100
                                              focus:outline-none">
                            </div>
                        </div>
                        <button type="submit"
                                class="mt-3 w-full bg-primary-600 hover:bg-primary-700 active:bg-primary-800
                                       text-white text-sm font-semibold py-2 rounded-xl transition-colors">
                            {{ __('front.filter_apply') }}
                        </button>
                    </div>

                    {{-- Reset link --}}
                    @if($priceMin !== null || $priceMax !== null || $sort !== 'newest')
                    <div class="text-center">
                        <a href="{{ $resetUrl }}"
                           class="text-xs text-gray-400 hover:text-red-500 transition-colors inline-flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ __('front.filter_reset') }}
                        </a>
                    </div>
                    @endif

                </form>
            </div>
        </aside>

        {{-- ═══════════════════════════════════════
             MAIN CONTENT
        ════════════════════════════════════════ --}}
        <div class="flex-1 min-w-0">

            {{-- Page heading --}}
            <div class="mb-5">
                <h1 class="text-2xl font-bold text-gray-900">{{ $catName }}</h1>
                @if($catMeta)
                    <p class="text-sm text-gray-500 mt-1">{{ $catMeta }}</p>
                @endif
            </div>

            {{-- ── Sort bar ── --}}
            <div class="flex items-center justify-between gap-3 mb-5 flex-wrap
                        bg-white border border-gray-100 rounded-2xl px-4 py-3">

                {{-- Results count --}}
                <p class="text-sm text-gray-500 shrink-0">
                    @if($products->total() > 0)
                        {!! __('front.showing_results', [
                            'from'  => $products->firstItem(),
                            'to'    => $products->lastItem(),
                            'total' => $products->total(),
                        ]) !!}
                    @else
                        {{ $products->total() }} {{ __('front.filter_categories') }}
                    @endif
                </p>

                <div class="flex items-center gap-2">

                    {{-- Sort select --}}
                    <select name="sort"
                            form="filter-form"
                            onchange="document.getElementById('filter-form').submit()"
                            class="text-sm border border-gray-200 rounded-xl px-3 py-1.5 bg-white
                                   text-gray-700 focus:border-primary-400 focus:ring-1
                                   focus:ring-primary-100 focus:outline-none cursor-pointer
                                   hover:border-gray-300 transition-colors">
                        <option value="newest"     {{ $sort === 'newest'     ? 'selected' : '' }}>{{ __('front.sort_newest') }}</option>
                        <option value="price_asc"  {{ $sort === 'price_asc'  ? 'selected' : '' }}>{{ __('front.sort_price_asc') }}</option>
                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>{{ __('front.sort_price_desc') }}</option>
                        <option value="discount"   {{ $sort === 'discount'   ? 'selected' : '' }}>{{ __('front.sort_discount') }}</option>
                    </select>

                    {{-- Grid / List toggle --}}
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}"
                           title="{{ __('front.view_grid') ?? 'Grid' }}"
                           class="p-2 transition-colors {{ $view === 'grid' ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-600' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
                           title="{{ __('front.view_list') ?? 'List' }}"
                           class="p-2 transition-colors {{ $view === 'list' ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-600' }}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </div>

                </div>
            </div>

            {{-- ── Products ── --}}
            @if($products->isEmpty())
                <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium mb-3">
                        {{ $isSearch ? __('front.search_empty', ['q' => $q]) : __('front.no_products_found') }}
                    </p>
                    <a href="{{ $resetUrl }}"
                       class="inline-flex items-center gap-1.5 text-sm text-primary-600 hover:text-primary-700
                              font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ __('front.filter_reset') }}
                    </a>
                </div>

            @elseif($view === 'list')
                {{-- ── LIST VIEW ── --}}
                <div class="space-y-3">
                    @foreach($products as $product)
                        @php
                            $t = $product->getTranslation($locale) ?? $product->getTranslation('en');
                            $discountPct = $product->is_on_sale
                                ? round((($product->price - $product->sale_price) / $product->price) * 100)
                                : 0;
                        @endphp
                        <div class="bg-white rounded-2xl border border-gray-100 hover:border-primary-200
                                    hover:shadow-md transition-all duration-200 flex gap-4 p-4">

                            {{-- Thumbnail --}}
                            <div class="w-28 h-28 sm:w-32 sm:h-32 shrink-0 bg-gray-50 rounded-xl overflow-hidden relative">
                                @if($product->primaryImage)
                                    <img src="{{ asset('storage/' . $product->primaryImage->path) }}"
                                         alt="{{ $t?->name ?? $product->sku }}"
                                         loading="lazy"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                @if($product->is_on_sale && $discountPct > 0)
                                    <span class="absolute top-1.5 left-1.5 bg-red-500 text-white text-[10px] font-bold
                                                 px-1.5 py-0.5 rounded-lg leading-none">
                                        -{{ $discountPct }}%
                                    </span>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-800 text-sm sm:text-base line-clamp-2 leading-snug">
                                        {{ $t?->name ?? $product->sku }}
                                    </h3>
                                    @if($t?->short_description)
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2 hidden sm:block">
                                            {{ $t->short_description }}
                                        </p>
                                    @endif
                                    @if($product->is_low_stock)
                                        <p class="text-xs text-amber-600 font-medium mt-1 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full inline-block"></span>
                                            {{ __('front.low_stock') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex items-center justify-between mt-3 flex-wrap gap-2">
                                    <div class="flex items-baseline gap-2">
                                        <span class="font-bold text-gray-900 text-lg">
                                            ৳{{ number_format($product->current_price, 0) }}
                                        </span>
                                        @if($product->is_on_sale)
                                            <span class="text-xs text-gray-400 line-through">
                                                ৳{{ number_format($product->price, 0) }}
                                            </span>
                                        @endif
                                    </div>
                                    <button type="button"
                                            class="bg-primary-600 hover:bg-primary-700 active:bg-primary-800
                                                   text-white text-xs font-semibold px-4 py-2 rounded-xl
                                                   transition-colors flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ __('front.add_to_cart') }}
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

            @else
                {{-- ── GRID VIEW ── --}}
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                    @foreach($products as $product)
                        @include('partials.product-card', [
                            'product' => $product,
                            'locale'  => $locale,
                        ])
                    @endforeach
                </div>
            @endif

            {{-- ── Pagination ── --}}
            @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->links('vendor.pagination.tailwind') }}
                </div>
            @endif

        </div>
        {{-- /main content --}}

    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    var btn = document.getElementById('filter-toggle');
    var sidebar = document.getElementById('shop-sidebar');
    if (!btn || !sidebar) return;
    btn.addEventListener('click', function () {
        sidebar.classList.toggle('hidden');
    });
})();
</script>
@endpush
