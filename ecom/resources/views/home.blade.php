@extends('layouts.app')

@section('title', config('app.name') . ' — Shop the Best Products Online')

@section('content')

{{-- ============================================================
    1. HERO SECTION
============================================================ --}}
<section class="relative bg-linear-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white overflow-hidden">
    {{-- Background decorative circles --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/5 rounded-full"></div>
        <div class="absolute bottom-0 -left-16 w-72 h-72 bg-white/5 rounded-full"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 flex flex-col lg:flex-row items-center gap-12">
        <div class="flex-1 text-center lg:text-left">
            <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-4 tracking-wide uppercase">
                New Season Arrivals
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-5">
                Discover Products<br>
                <span class="text-yellow-300">You'll Love</span>
            </h1>
            <p class="text-lg text-indigo-100 mb-8 max-w-lg mx-auto lg:mx-0">
                Shop thousands of curated products — electronics, fashion, home goods and more — with fast delivery across Bangladesh.
            </p>
            <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                <a href="#featured" class="bg-white text-indigo-700 font-semibold px-7 py-3 rounded-full hover:bg-indigo-50 transition-colors shadow-lg">
                    Shop Now
                </a>
                <a href="#categories" class="border border-white/40 text-white font-semibold px-7 py-3 rounded-full hover:bg-white/10 transition-colors">
                    Browse Categories
                </a>
            </div>

            {{-- Trust badges --}}
            <div class="mt-10 flex flex-wrap gap-6 justify-center lg:justify-start text-sm text-indigo-200">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    4.9★ Rated
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Free Returns
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Secure Checkout
                </span>
            </div>
        </div>

        {{-- Hero illustration placeholder --}}
        <div class="flex-1 lg:max-w-sm hidden lg:block">
            <div class="bg-white/10 backdrop-blur rounded-3xl p-8 text-center border border-white/20">
                <div class="w-32 h-32 bg-white/20 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-16 h-16 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <p class="text-white font-semibold text-lg">10,000+ Products</p>
                <p class="text-indigo-200 text-sm mt-1">Ready to ship</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
    2. VALUE PROPOSITIONS BAR
============================================================ --}}
<section class="bg-indigo-50 border-b border-indigo-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
            @foreach([
                ['icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 10a2 2 0 002 2h8a2 2 0 002-2L19 8', 'title' => 'Free Delivery', 'sub' => 'On orders above ৳999'],
                ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => 'Easy Returns', 'sub' => '7-day return policy'],
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Secure Payment', 'sub' => '100% safe transactions'],
                ['icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z', 'title' => '24/7 Support', 'sub' => 'Chat & phone support'],
            ] as $prop)
            <div class="flex items-center gap-3 justify-center lg:justify-start">
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <p class="text-indigo-600 text-sm font-semibold uppercase tracking-wider mb-1">Browse by</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Shop Categories</h2>
        </div>
        <a href="#" class="text-sm text-indigo-600 font-semibold hover:text-indigo-800 transition-colors hidden sm:block">
            All Categories →
        </a>
    </div>

    @if($categories->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4">
            @foreach($categories as $category)
                @php $t = $category->getTranslation('en'); @endphp
                <a href="#" class="group relative bg-linear-to-br from-gray-50 to-gray-100 rounded-2xl overflow-hidden border border-gray-100 hover:border-indigo-200 hover:shadow-md transition-all duration-200 p-6 flex flex-col items-center text-center">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}"
                            alt="{{ $t?->name ?? 'Category' }}"
                            class="w-16 h-16 object-contain mb-3 group-hover:scale-110 transition-transform duration-200">
                    @else
                        <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-indigo-200 transition-colors">
                            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    @endif
                    <span class="font-semibold text-sm text-gray-800 group-hover:text-indigo-700 transition-colors">
                        {{ $t?->name ?? 'Category' }}
                    </span>
                    @if($category->children->isNotEmpty())
                        <span class="text-xs text-gray-400 mt-0.5">{{ $category->children->count() }} sub-categories</span>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 text-gray-400">
            <p>No categories found.</p>
        </div>
    @endif
</section>

{{-- ============================================================
    4. FEATURED PRODUCTS SECTION
============================================================ --}}
<section id="featured" class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-indigo-600 text-sm font-semibold uppercase tracking-wider mb-1">Hand-picked</p>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Featured Products</h2>
            </div>
            <a href="#" class="text-sm text-indigo-600 font-semibold hover:text-indigo-800 transition-colors hidden sm:block">
                View All →
            </a>
        </div>

        @if($featuredProducts->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach($featuredProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @else
            <div class="text-center py-16 text-gray-400">No featured products available.</div>
        @endif
    </div>
</section>

{{-- ============================================================
    5. PROMO BANNER
============================================================ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- Banner 1 --}}
        <div class="relative bg-linear-to-r from-orange-500 to-red-500 rounded-3xl p-8 text-white overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full"></div>
            <p class="text-orange-100 text-sm font-semibold uppercase tracking-wider mb-2">Limited Time</p>
            <h3 class="text-2xl font-bold mb-1">Flash Sale</h3>
            <p class="text-orange-100 text-sm mb-4">Up to 50% off on selected items</p>
            <a href="#sale" class="inline-block bg-white text-orange-600 font-bold text-sm px-5 py-2.5 rounded-full hover:bg-orange-50 transition-colors">
                Shop Sale
            </a>
        </div>

        {{-- Banner 2 --}}
        <div class="relative bg-linear-to-r from-teal-500 to-cyan-600 rounded-3xl p-8 text-white overflow-hidden">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full"></div>
            <p class="text-teal-100 text-sm font-semibold uppercase tracking-wider mb-2">New In</p>
            <h3 class="text-2xl font-bold mb-1">New Arrivals</h3>
            <p class="text-teal-100 text-sm mb-4">Explore the latest products added this week</p>
            <a href="#new-arrivals" class="inline-block bg-white text-teal-600 font-bold text-sm px-5 py-2.5 rounded-full hover:bg-teal-50 transition-colors">
                Explore Now
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
                <p class="text-red-500 text-sm font-semibold uppercase tracking-wider mb-1">Save Big</p>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Today's Deals</h2>
            </div>
            <a href="#" class="text-sm text-red-500 font-semibold hover:text-red-700 transition-colors hidden sm:block">
                All Deals →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($onSaleProducts as $product)
                @php
                    $t = $product->getTranslation('en');
                    $discount = $product->price > 0
                        ? round((($product->price - $product->sale_price) / $product->price) * 100)
                        : 0;
                @endphp
                <div class="bg-white rounded-2xl overflow-hidden border border-red-100 hover:shadow-md transition-shadow group">
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
                        @if($discount > 0)
                            <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                -{{ $discount }}%
                            </span>
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-400 mb-1">{{ $product->brand?->name }}</p>
                        <h4 class="font-semibold text-gray-800 text-sm line-clamp-2 mb-2">
                            {{ $t?->name ?? $product->sku }}
                        </h4>
                        <div class="flex items-center gap-2">
                            <span class="text-red-600 font-bold">৳{{ number_format($product->sale_price, 0) }}</span>
                            <span class="text-gray-400 text-xs line-through">৳{{ number_format($product->price, 0) }}</span>
                        </div>
                        <button class="mt-3 w-full bg-red-500 hover:bg-red-600 text-white text-xs font-semibold py-2 rounded-lg transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============================================================
    7. NEW ARRIVALS SECTION
============================================================ --}}
<section id="new-arrivals" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <p class="text-teal-600 text-sm font-semibold uppercase tracking-wider mb-1">Just Landed</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">New Arrivals</h2>
        </div>
        <a href="#" class="text-sm text-teal-600 font-semibold hover:text-teal-800 transition-colors hidden sm:block">
            See All →
        </a>
    </div>

    @if($newArrivals->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($newArrivals as $product)
                @include('partials.product-card', ['product' => $product, 'badge' => 'New'])
            @endforeach
        </div>
    @else
        <div class="text-center py-16 text-gray-400">No new arrivals found.</div>
    @endif
</section>

{{-- ============================================================
    8. BRANDS SECTION
============================================================ --}}
@if($brands->isNotEmpty())
<section class="bg-gray-50 py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <p class="text-indigo-600 text-sm font-semibold uppercase tracking-wider mb-2">Trusted Brands</p>
            <h2 class="text-2xl font-bold text-gray-900">Shop by Brand</h2>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-6">
            @foreach($brands as $brand)
                <a href="#" class="bg-white border border-gray-100 rounded-xl p-4 flex items-center justify-center hover:border-indigo-200 hover:shadow-sm transition-all group aspect-square">
                    @if($brand->logo)
                        <img src="{{ asset('storage/' . $brand->logo) }}"
                            alt="{{ $brand->name }}"
                            class="max-h-12 max-w-full object-contain grayscale group-hover:grayscale-0 transition-all duration-300 opacity-60 group-hover:opacity-100">
                    @else
                        <span class="text-xs font-bold text-gray-500 group-hover:text-indigo-600 transition-colors text-center leading-tight">
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
    9. TESTIMONIALS SECTION
============================================================ --}}
@if($testimonials->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <p class="text-indigo-600 text-sm font-semibold uppercase tracking-wider mb-2">Happy Customers</p>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">What People Are Saying</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($testimonials as $review)
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow">
                {{-- Stars --}}
                <div class="flex items-center gap-0.5 mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>

                @if($review->title)
                    <h4 class="font-semibold text-gray-800 mb-1">{{ $review->title }}</h4>
                @endif
                <p class="text-gray-600 text-sm leading-relaxed line-clamp-3">
                    "{{ $review->body }}"
                </p>

                <div class="mt-4 pt-4 border-t border-gray-50 flex items-center gap-3">
                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-xs">
                        {{ strtoupper(substr($review->user?->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $review->user?->name ?? 'Anonymous' }}</p>
                        @if($review->is_verified_purchase)
                            <p class="text-xs text-green-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Verified Purchase
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- ============================================================
    10. NEWSLETTER / CTA SECTION
============================================================ --}}
<section class="bg-linear-to-r from-indigo-600 to-purple-700 text-white py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-3">Get Exclusive Deals in Your Inbox</h2>
        <p class="text-indigo-200 mb-8">Subscribe and be the first to know about new arrivals, sales, and exclusive offers.</p>

        <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto" onsubmit="return false">
            <input type="email" placeholder="Enter your email address"
                class="flex-1 px-5 py-3 rounded-full text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-white/50 placeholder-gray-400">
            <button type="submit"
                class="bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-bold px-7 py-3 rounded-full transition-colors text-sm whitespace-nowrap">
                Subscribe Free
            </button>
        </form>
        <p class="text-indigo-300 text-xs mt-4">No spam. Unsubscribe anytime.</p>
    </div>
</section>

@endsection
