@extends('layouts.app')

@section('title', config('app.name') . ' — ' . __('front.hero_cta_shop'))

@section('content')

{{-- ============================================================
    1. HERO SLIDER (Swiper) — picture/srcset for responsive images
============================================================ --}}
<section class="relative overflow-hidden">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">

            {{-- ── Dynamic slides from DB ── --}}
            @forelse($banners as $banner)
                <div class="swiper-slide">
                    <div class="hero-slide-inner">

                        {{-- Single image — CSS object-fit handles all screen sizes --}}
                        <img
                            src="{{ asset('storage/' . $banner->image) }}"
                            alt="{{ $banner->title }}"
                            class="hero-slide-bg"
                            loading="eager"
                            fetchpriority="{{ $loop->first ? 'high' : 'auto' }}">

                        {{-- Gradient overlay --}}
                        <div class="hero-slide-overlay"></div>

                        {{-- Text content --}}
                        <div class="hero-slide-content">
                            <div class="max-w-2xl">

                                {{-- Optional badge chip --}}
                                <span class="inline-block bg-accent-500/25 text-accent-200 text-xs font-bold
                                             px-4 py-1.5 rounded-full mb-4 tracking-widest uppercase
                                             backdrop-blur-sm border border-accent-400/20">
                                    {{ __('front.hero_badge') }}
                                </span>

                                <h1 class="hero-slide-title">
                                    {!! nl2br(e($banner->title)) !!}
                                </h1>

                                @if($banner->subtitle)
                                    <p class="hero-slide-subtitle">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif

                                {{-- CTA buttons --}}
                                <div class="flex flex-wrap gap-3 justify-center sm:justify-start">
                                    @if($banner->button_text && $banner->button_url)
                                        <a href="{{ $banner->button_url }}" class="hero-slide-btn">
                                            {{ $banner->button_text }}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                        </a>
                                    @endif
                                    <a href="#categories"
                                       class="inline-flex items-center gap-2 border-2 border-white/30 text-white font-semibold
                                              px-6 py-3.5 rounded-full hover:bg-white/15 hover:border-white/50
                                              backdrop-blur-sm transition-all duration-200 text-sm sm:text-base">
                                        {{ __('front.hero_cta_browse') }}
                                    </a>
                                </div>

                                {{-- Trust micro-badges (hidden on smallest screens to keep it clean) --}}
                                <div class="hidden sm:flex flex-wrap gap-5 mt-7 text-sm text-primary-200">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-accent-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ __('front.hero_rated') }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('front.hero_returns') }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        {{ __('front.hero_secure') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                {{-- ── Fallback static slide when no banners in DB ── --}}
                <div class="swiper-slide">
                    <div class="hero-slide-inner bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800">

                        {{-- Decorative blobs --}}
                        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-primary-700/15 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute bottom-0 -left-32 w-96 h-96 bg-accent-500/8 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-primary-600/5 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="hero-slide-content">
                            <div class="max-w-2xl">
                                <span class="inline-block bg-accent-500/20 text-accent-300 text-xs font-bold
                                             px-4 py-1.5 rounded-full mb-5 tracking-widest uppercase">
                                    {{ __('front.hero_badge') }}
                                </span>
                                <h1 class="hero-slide-title">
                                    {!! __('front.hero_title') !!}
                                </h1>
                                <p class="hero-slide-subtitle">
                                    {{ __('front.hero_subtitle') }}
                                </p>
                                <div class="flex flex-wrap gap-3 justify-center sm:justify-start">
                                    <a href="#featured" class="hero-slide-btn">
                                        {{ __('front.hero_cta_shop') }}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    </a>
                                    <a href="#categories"
                                       class="inline-flex items-center gap-2 border-2 border-white/30 text-white font-semibold
                                              px-6 py-3.5 rounded-full hover:bg-white/15 hover:border-white/50
                                              transition-all duration-200 text-sm sm:text-base">
                                        {{ __('front.hero_cta_browse') }}
                                    </a>
                                </div>
                                <div class="hidden sm:flex flex-wrap gap-5 mt-7 text-sm text-primary-300">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-accent-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ __('front.hero_rated') }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('front.hero_returns') }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        {{ __('front.hero_secure') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse

        </div>

        {{-- Swiper controls --}}
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-pagination"></div>
    </div>
</section>
{{-- ============================================================
    2. VALUE PROPOSITIONS BAR
============================================================ --}}
<section class="bg-primary-50 border-b border-primary-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
            @foreach([
                ['icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 10a2 2 0 002 2h8a2 2 0 002-2L19 8', 'title' => __('front.prop_delivery_title'), 'sub' => __('front.prop_delivery_sub')],
                ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => __('front.prop_returns_title'), 'sub' => __('front.prop_returns_sub')],
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => __('front.prop_secure_title'), 'sub' => __('front.prop_secure_sub')],
                ['icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z', 'title' => __('front.prop_support_title'), 'sub' => __('front.prop_support_sub')],
            ] as $prop)
                <div class="flex items-center gap-3 justify-center lg:justify-start">
                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $prop['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="text-left hidden sm:block">
                        <p class="font-semibold text-gray-800 text-sm">{{ $prop['title'] }}</p>
                        <p class="text-gray-500 text-xs">{{ $prop['sub'] }}</p>
                    </div>
                    <div class="sm:hidden text-xs font-semibold text-gray-700">{{ $prop['title'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
    3. CATEGORIES SECTION
============================================================ --}}
<section id="categories" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <p class="text-primary-600 text-sm font-semibold uppercase tracking-wider mb-1">{{ __('front.browse_by') }}</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('front.shop_categories') }}</h2>
        </div>
        <a href="#" class="text-sm text-primary-600 font-semibold hover:text-primary-800 transition-colors hidden sm:block">
            {{ __('front.all_categories') }}
        </a>
    </div>

    @if($categories->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($categories as $category)
                @php $t = $category->getTranslation(app()->getLocale()) ?? $category->getTranslation('en'); @endphp
                <a href="#"
                   class="group relative bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl overflow-hidden border border-gray-100 hover:border-primary-300 hover:shadow-lg transition-all duration-200 p-6 flex flex-col items-center text-center">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}"
                             alt="{{ $t?->name ?? 'Category' }}"
                             class="w-16 h-16 object-contain mb-3 group-hover:scale-110 transition-transform duration-200">
                    @else
                        <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-primary-200 transition-colors">
                            <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    @endif
                    <span class="font-semibold text-sm text-gray-800 group-hover:text-primary-700 transition-colors">
                        {{ $t?->name ?? 'Category' }}
                    </span>
                    @if($category->children->isNotEmpty())
                        <span class="text-xs text-gray-400 mt-0.5">
                            {{ trans('front.sub_categories', ['count' => $category->children->count()]) }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 text-gray-400">{{ __('front.no_categories') }}</div>
    @endif
</section>

{{-- ============================================================
    4. FEATURED PRODUCTS — Swiper Slider
============================================================ --}}
<section id="featured" class="bg-primary-50/50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-primary-600 text-sm font-semibold uppercase tracking-wider mb-1">{{ __('front.hand_picked') }}</p>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('front.featured_products') }}</h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="#" class="text-sm text-primary-600 font-semibold hover:text-primary-800 transition-colors hidden sm:block">
                    {{ __('front.view_all') }}
                </a>
                <div class="flex gap-2">
                    <button class="featured-prev w-9 h-9 bg-white border border-gray-200 rounded-full flex items-center justify-center text-primary-600 hover:bg-primary-600 hover:text-white hover:border-primary-600 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button class="featured-next w-9 h-9 bg-white border border-gray-200 rounded-full flex items-center justify-center text-primary-600 hover:bg-primary-600 hover:text-white hover:border-primary-600 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        @if($featuredProducts->isNotEmpty())
            <div class="swiper products-swiper">
                <div class="swiper-wrapper pb-2">
                    @foreach($featuredProducts as $product)
                        <div class="swiper-slide h-auto">
                            @include('partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-16 text-gray-400">{{ __('front.no_featured') }}</div>
        @endif
    </div>
</section>

{{-- ============================================================
    5. PROMO BANNERS
============================================================ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div class="relative bg-gradient-to-r from-orange-500 to-red-500 rounded-3xl p-8 text-white overflow-hidden group cursor-pointer hover:shadow-xl transition-shadow">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full group-hover:scale-110 transition-transform duration-300"></div>
            <div class="absolute top-4 right-4 w-20 h-20 bg-white/5 rounded-full"></div>
            <p class="text-orange-100 text-xs font-bold uppercase tracking-widest mb-2">{{ __('front.flash_sale_badge') }}</p>
            <h3 class="text-2xl font-bold mb-1">{{ __('front.flash_sale_title') }}</h3>
            <p class="text-orange-100 text-sm mb-5">{{ __('front.flash_sale_sub') }}</p>
            <a href="#sale" class="inline-block bg-white text-orange-600 font-bold text-sm px-5 py-2.5 rounded-full hover:bg-orange-50 transition-colors">
                {{ __('front.flash_sale_btn') }}
            </a>
        </div>

        <div class="relative bg-gradient-to-r from-primary-600 to-primary-800 rounded-3xl p-8 text-white overflow-hidden group cursor-pointer hover:shadow-xl transition-shadow">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full group-hover:scale-110 transition-transform duration-300"></div>
            <div class="absolute top-4 right-4 w-20 h-20 bg-white/5 rounded-full"></div>
            <p class="text-primary-200 text-xs font-bold uppercase tracking-widest mb-2">{{ __('front.new_in_badge') }}</p>
            <h3 class="text-2xl font-bold mb-1">{{ __('front.new_arrivals_title') }}</h3>
            <p class="text-primary-200 text-sm mb-5">{{ __('front.new_arrivals_sub') }}</p>
            <a href="#new-arrivals" class="inline-block bg-white text-primary-700 font-bold text-sm px-5 py-2.5 rounded-full hover:bg-primary-50 transition-colors">
                {{ __('front.explore_now') }}
            </a>
        </div>
    </div>
</section>

{{-- ============================================================
    6. ON SALE / DEALS SECTION
============================================================ --}}
@if($onSaleProducts->isNotEmpty())
<section id="sale" class="bg-red-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-red-500 text-sm font-semibold uppercase tracking-wider mb-1">{{ __('front.save_big') }}</p>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('front.todays_deals') }}</h2>
            </div>
            <a href="#" class="text-sm text-red-500 font-semibold hover:text-red-700 transition-colors hidden sm:block">
                {{ __('front.all_deals') }}
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($onSaleProducts as $product)
                @php
                    $t = $product->getTranslation(app()->getLocale()) ?? $product->getTranslation('en');
                    $discount = $product->price > 0
                        ? round((($product->price - $product->sale_price) / $product->price) * 100)
                        : 0;
                @endphp
                <div class="bg-white rounded-2xl overflow-hidden border border-red-100 hover:shadow-lg transition-shadow group">
                    <div class="relative h-48 flex items-center justify-center bg-gray-50 overflow-hidden">
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->path) }}"
                                 alt="{{ $t?->name ?? $product->sku }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-20 h-20 bg-gray-200 rounded-xl flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        @if($discount > 0)
                            <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                -{{ $discount }}%
                            </span>
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-400 mb-1 truncate">{{ $product->brand?->name }}</p>
                        <h4 class="font-semibold text-gray-800 text-sm line-clamp-2 mb-2 min-h-[2.5rem]">
                            {{ $t?->name ?? $product->sku }}
                        </h4>
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <span class="text-red-600 font-bold">৳{{ number_format($product->sale_price, 0) }}</span>
                                <span class="text-gray-400 text-xs line-through ml-1">৳{{ number_format($product->price, 0) }}</span>
                            </div>
                            <button class="bg-red-500 hover:bg-red-600 text-white text-xs font-semibold py-2 px-3 rounded-xl transition-colors whitespace-nowrap">
                                {{ __('front.add_to_cart') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================================
    7. NEW ARRIVALS — Swiper Slider
============================================================ --}}
<section id="new-arrivals" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <p class="text-accent-600 text-sm font-semibold uppercase tracking-wider mb-1">{{ __('front.just_landed') }}</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('front.new_arrivals') }}</h2>
        </div>
        <div class="flex items-center gap-3">
            <a href="#" class="text-sm text-accent-600 font-semibold hover:text-accent-800 transition-colors hidden sm:block">
                {{ __('front.see_all') }}
            </a>
            <div class="flex gap-2">
                <button class="arrivals-prev w-9 h-9 bg-white border border-gray-200 rounded-full flex items-center justify-center text-primary-600 hover:bg-primary-600 hover:text-white hover:border-primary-600 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button class="arrivals-next w-9 h-9 bg-white border border-gray-200 rounded-full flex items-center justify-center text-primary-600 hover:bg-primary-600 hover:text-white hover:border-primary-600 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @if($newArrivals->isNotEmpty())
        <div class="swiper new-arrivals-swiper">
            <div class="swiper-wrapper pb-2">
                @foreach($newArrivals as $product)
                    <div class="swiper-slide h-auto">
                        @include('partials.product-card', ['product' => $product, 'badge' => __('front.new_badge')])
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center py-16 text-gray-400">{{ __('front.no_new_arrivals') }}</div>
    @endif
</section>

{{-- ============================================================
    8. BRANDS SECTION
============================================================ --}}
@if($brands->isNotEmpty())
<section class="bg-primary-50/50 py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <p class="text-primary-600 text-sm font-semibold uppercase tracking-wider mb-2">{{ __('front.trusted_brands') }}</p>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('front.shop_by_brand') }}</h2>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-5">
            @foreach($brands as $brand)
                <a href="#"
                   class="bg-white border border-gray-100 rounded-2xl p-4 flex items-center justify-center hover:border-primary-200 hover:shadow-md transition-all group aspect-square">
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}"
                             alt="{{ $brand->name }}"
                             class="max-h-12 max-w-full object-contain grayscale group-hover:grayscale-0 transition-all duration-300 opacity-60 group-hover:opacity-100">
                    @else
                        <span class="text-xs font-bold text-gray-500 group-hover:text-primary-600 transition-colors text-center leading-tight">
                            {{ $brand->name }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================================
    9. REVIEWS SLIDER (Swiper)
============================================================ --}}
@if($testimonials->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <p class="text-primary-600 text-sm font-semibold uppercase tracking-wider mb-2">{{ __('front.happy_customers') }}</p>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('front.reviews_heading') }}</h2>
    </div>

    <div class="swiper reviews-swiper">
        <div class="swiper-wrapper">
            @foreach($testimonials as $review)
                <div class="swiper-slide h-auto">
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow h-full flex flex-col">
                        {{-- Stars --}}
                        <div class="flex items-center gap-0.5 mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-accent-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>

                        @if($review->title)
                            <h4 class="font-semibold text-gray-800 mb-2">{{ $review->title }}</h4>
                        @endif

                        <p class="text-gray-600 text-sm leading-relaxed line-clamp-4 flex-1">
                            "{{ $review->body }}"
                        </p>

                        <div class="mt-4 pt-4 border-t border-gray-50 flex items-center gap-3">
                            <div class="w-9 h-9 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-bold text-sm shrink-0">
                                {{ strtoupper(substr($review->user?->name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $review->user?->name ?? 'Anonymous' }}</p>
                                @if($review->is_verified_purchase)
                                    <p class="text-xs text-green-500 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ __('front.verified_purchase') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination mt-4"></div>
    </div>
</section>
@endif

{{-- ============================================================
    10. NEWSLETTER / CTA SECTION
============================================================ --}}
<section class="bg-gradient-to-r from-primary-700 via-primary-600 to-primary-800 text-white py-16 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 -left-16 w-60 h-60 bg-accent-500/10 rounded-full blur-2xl"></div>
    </div>
    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-3">{{ __('front.newsletter_title') }}</h2>
        <p class="text-primary-200 mb-8">{{ __('front.newsletter_sub') }}</p>

        <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto" onsubmit="return false">
            <input type="email"
                   placeholder="{{ __('front.newsletter_placeholder') }}"
                   class="flex-1 px-5 py-3 rounded-full text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-white/50 placeholder-gray-400">
            <button type="submit"
                    class="bg-accent-500 hover:bg-accent-400 text-white font-bold px-7 py-3 rounded-full transition-colors text-sm whitespace-nowrap shadow-lg">
                {{ __('front.newsletter_btn') }}
            </button>
        </form>
        <p class="text-primary-300 text-xs mt-4">{{ __('front.newsletter_note') }}</p>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Hero Slider ──
    new Swiper('.hero-swiper', {
        loop: true,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        speed: 800,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.hero-swiper .swiper-button-next',
            prevEl: '.hero-swiper .swiper-button-prev',
        },
        pagination: {
            el: '.hero-swiper .swiper-pagination',
            clickable: true,
        },
    });

    // ── Featured Products Slider ──
    new Swiper('.products-swiper', {
        slidesPerView: 1.2,
        spaceBetween: 16,
        grabCursor: true,
        navigation: {
            nextEl: '.featured-next',
            prevEl: '.featured-prev',
        },
        breakpoints: {
            480:  { slidesPerView: 2,   spaceBetween: 16 },
            768:  { slidesPerView: 3,   spaceBetween: 20 },
            1024: { slidesPerView: 4,   spaceBetween: 20 },
        },
    });

    // ── New Arrivals Slider ──
    new Swiper('.new-arrivals-swiper', {
        slidesPerView: 1.2,
        spaceBetween: 16,
        grabCursor: true,
        navigation: {
            nextEl: '.arrivals-next',
            prevEl: '.arrivals-prev',
        },
        breakpoints: {
            480:  { slidesPerView: 2,   spaceBetween: 16 },
            768:  { slidesPerView: 3,   spaceBetween: 20 },
            1024: { slidesPerView: 4,   spaceBetween: 20 },
        },
    });

    // ── Reviews Slider ──
    new Swiper('.reviews-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        grabCursor: true,
        loop: true,
        autoplay: {
            delay: 4500,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.reviews-swiper .swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            640:  { slidesPerView: 2, spaceBetween: 20 },
            1024: { slidesPerView: 3, spaceBetween: 24 },
        },
    });

});
</script>
@endpush
