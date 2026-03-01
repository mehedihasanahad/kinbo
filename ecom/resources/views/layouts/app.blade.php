<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'ShopZone'))</title>
    <meta name="description" content="@yield('meta_description', 'Your one-stop online shop for the best products.')">

    {{-- Favicon --}}
    @php $favicon = \App\Models\Setting::get('site_favicon', ''); @endphp
    @if($favicon)
        <link rel="icon" href="{{ Storage::url($favicon) }}" type="image/png">
    @endif

    {{-- Open Graph --}}
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:site_name"   content="{{ config('app.name') }}">
    <meta property="og:title"       content="@yield('og_title', @yield('title', config('app.name')))">
    <meta property="og:description" content="@yield('og_description', @yield('meta_description', 'Your one-stop online shop for the best products.'))">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:image"       content="@yield('og_image', asset('images/og-default.png'))">
    <meta property="og:locale"      content="{{ str_replace('-', '_', app()->getLocale()) }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('og_title', @yield('title', config('app.name')))">
    <meta name="twitter:description" content="@yield('og_description', @yield('meta_description', 'Your one-stop online shop for the best products.'))">
    <meta name="twitter:image"       content="@yield('og_image', asset('images/og-default.png'))">

    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet"/>

    {{-- Swiper CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/swiper@11/swiper-bundle.min.css"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-white text-gray-900 antialiased">

{{-- ── Top announcement bar ── --}}
@php
    $trackUrl = auth()->check() ? route('orders.index') : route('login');
@endphp

@if($announcementBarText)
<div class="bg-primary-950 text-primary-100 text-xs py-2 hidden sm:block">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <span>{!! $announcementBarText !!}</span>
        <div class="flex items-center gap-5">
            <a href="{{ $trackUrl }}" class="hover:text-white transition-colors">{{ __('front.track_order') }}</a>
            <a href="#" class="hover:text-white transition-colors">{{ __('front.help') }}</a>

            {{-- Language switcher --}}
            <div class="flex items-center gap-1 border-l border-primary-800 pl-4">
                <a href="{{ route('lang.switch', 'en') }}"
                   class="px-2 py-0.5 rounded text-xs font-semibold transition-colors {{ app()->getLocale() === 'en' ? 'bg-primary-600 text-white' : 'text-primary-300 hover:text-white' }}">
                    EN
                </a>
                <a href="{{ route('lang.switch', 'bn') }}"
                   class="px-2 py-0.5 rounded text-xs font-semibold transition-colors {{ app()->getLocale() === 'bn' ? 'bg-primary-600 text-white' : 'text-primary-300 hover:text-white' }}">
                    বাং
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Main header ── --}}
<header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl text-primary-600 shrink-0">
                @php $siteLogo = \App\Models\Setting::get('site_logo'); @endphp
                @if($siteLogo)
                    <img src="{{ asset('storage/' . $siteLogo) }}"
                         alt="{{ config('app.name', 'ShopZone') }}"
                         class="h-9 w-auto object-contain">
                @else
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6" stroke="white" stroke-width="2"/>
                        <path d="M16 10a4 4 0 01-8 0" fill="none" stroke="white" stroke-width="2"/>
                    </svg>
                    {{ config('app.name', 'ShopZone') }}
                @endif
            </a>

            {{-- Search --}}
            <form method="GET" action="{{ route('shop.search') }}" role="search"
                  class="hidden md:flex flex-1 max-w-xl mx-8">
                <div class="relative w-full">
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="{{ __('front.search_placeholder') }}"
                           autocomplete="off"
                           class="w-full pl-4 pr-10 py-2.5 border border-gray-200 rounded-full text-sm bg-gray-50 focus:bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </form>

            {{-- Nav actions --}}
            <div class="flex items-center gap-3">
                {{-- Mobile search toggle --}}
                <button id="mobile-search-toggle"
                        type="button"
                        aria-label="Search"
                        class="md:hidden text-gray-500 hover:text-primary-600 transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                @auth
                    {{-- Wishlist --}}
                    <a href="{{ route('wishlist.index') }}" class="hidden sm:flex items-center gap-1.5 text-sm text-gray-600 hover:text-primary-600 transition-colors relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span>{{ __('front.wishlist') }}</span>
                        <span class="wishlist-badge absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center leading-none {{ $wishlistCount > 0 ? '' : 'hidden' }}">
                            {{ $wishlistCount > 9 ? '9+' : $wishlistCount }}
                        </span>
                    </a>

                    {{-- Cart with badge --}}
                    <a href="{{ route('cart.index') }}" class="flex items-center gap-1.5 text-sm text-gray-600 hover:text-primary-600 transition-colors relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="hidden sm:inline">{{ __('front.cart') }}</span>
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-accent-600 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center leading-none">
                                {{ $cartCount > 9 ? '9+' : $cartCount }}
                            </span>
                        @endif
                    </a>

                    {{-- User dropdown --}}
                    <div class="relative group">
                        <button class="flex items-center gap-1.5 text-sm text-gray-600 hover:text-primary-600 transition-colors">
                            <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-semibold text-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 z-50">
                            <a href="{{ route('account.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-t-xl">{{ __('front.my_account') }}</a>
                            <a href="{{ $trackUrl }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">{{ __('front.my_orders') }}</a>
                            <a href="{{ route('account.addresses') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">{{ __('front.address_book') }}</a>
                            <div class="border-t border-gray-100 my-0.5"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-b-xl">
                                    {{ __('front.sign_out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline text-sm text-gray-600 hover:text-primary-600 transition-colors">
                        {{ __('front.sign_in') }}
                    </a>
                    <a href="{{ route('register') }}" class="text-sm bg-primary-600 text-white px-4 py-2 rounded-full hover:bg-primary-700 transition-colors font-medium">
                        {{ __('front.register') }}
                    </a>
                @endauth

                {{-- Mobile language switcher --}}
                <div class="flex items-center gap-1 sm:hidden ml-1">
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="px-1.5 py-0.5 rounded text-xs font-bold transition-colors {{ app()->getLocale() === 'en' ? 'bg-primary-600 text-white' : 'text-gray-500' }}">
                        EN
                    </a>
                    <a href="{{ route('lang.switch', 'bn') }}"
                       class="px-1.5 py-0.5 rounded text-xs font-bold transition-colors {{ app()->getLocale() === 'bn' ? 'bg-primary-600 text-white' : 'text-gray-500' }}">
                        বাং
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Mobile search bar (hidden by default, toggled by JS) --}}
<div id="mobile-search-bar"
     class="md:hidden hidden bg-white border-b border-gray-100 px-4 py-3 shadow-sm">
    <form method="GET" action="{{ route('shop.search') }}" role="search">
        <div class="relative">
            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="{{ __('front.search_placeholder') }}"
                   autocomplete="off"
                   autofocus
                   id="mobile-search-input"
                   class="w-full pl-4 pr-10 py-2.5 border border-gray-200 rounded-full text-sm
                          bg-gray-50 focus:bg-white focus:border-primary-400 focus:ring-2
                          focus:ring-primary-100 focus:outline-none transition-all">
            <button type="submit"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
        </div>
    </form>
</div>

{{-- Flash messages --}}
@if(session('cart_success'))
    <div class="bg-emerald-50 border-b border-emerald-200 text-emerald-800 text-sm px-4 py-3 text-center">
        {{ session('cart_success') }}
    </div>
@endif
@if(session('cart_error'))
    <div class="bg-red-50 border-b border-red-200 text-red-800 text-sm px-4 py-3 text-center">
        {{ session('cart_error') }}
    </div>
@endif

{{-- Main content --}}
<main>
    @yield('content')
</main>

{{-- ── Footer ── --}}
<footer class="bg-primary-950 text-primary-300 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <div>
                <h3 class="text-white font-bold text-lg mb-4">{{ config('app.name', 'ShopZone') }}</h3>
                <p class="text-sm leading-relaxed">{{ __('front.footer_tagline') }}</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">{{ __('front.quick_links') }}</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">{{ __('front.about_us') }}</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">{{ __('front.contact') }}</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">{{ __('front.blog') }}</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">{{ __('front.careers') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">{{ __('front.customer_care') }}</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">{{ __('front.track_my_order') }}</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">{{ __('front.returns_exchanges') }}</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">{{ __('front.faq') }}</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">{{ __('front.privacy_policy') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">{{ __('front.newsletter_footer') }}</h4>
                <p class="text-sm mb-3">{{ __('front.newsletter_footer_sub') }}</p>
                <div class="flex gap-2">
                    <input type="email"
                           placeholder="{{ __('front.footer_email_placeholder') }}"
                           class="flex-1 text-sm px-3 py-2 rounded-lg bg-primary-900 border border-primary-800 text-white placeholder-primary-500 focus:outline-none focus:border-primary-500">
                    <button class="bg-accent-600 hover:bg-accent-500 text-white text-sm px-4 py-2 rounded-lg transition-colors font-semibold">
                        {{ __('front.footer_go') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="border-t border-primary-900 py-5 text-center text-xs text-primary-600">
        &copy; {{ date('Y') }} {{ config('app.name', 'ShopZone') }}. {{ __('front.all_rights') }}
    </div>
</footer>

{{-- Swiper JS --}}
<script src="https://unpkg.com/swiper@11/swiper-bundle.min.js"></script>

{{-- Shared wishlist AJAX toggle --}}
<script>
function toggleWishlist(btn, productId) {
    const wishlisted = btn.dataset.wishlisted === 'true';
    const url = wishlisted ? btn.dataset.destroyUrl : btn.dataset.storeUrl;
    const method = wishlisted ? 'DELETE' : 'POST';
    const body = wishlisted ? null : JSON.stringify({ product_id: productId });

    fetch(url, {
        method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body,
    })
    .then(res => res.json())
    .then(data => {
        const nowWishlisted = data.in_wishlist;
        btn.dataset.wishlisted = nowWishlisted ? 'true' : 'false';

        // Update SVG fill
        const svg = btn.querySelector('svg');
        if (svg) svg.setAttribute('fill', nowWishlisted ? 'currentColor' : 'none');

        // Update button colours
        if (nowWishlisted) {
            btn.classList.add('text-red-500');
            btn.classList.remove('text-gray-400');
            // detail page border
            btn.classList.add('border-red-400');
            btn.classList.remove('border-gray-200');
        } else {
            btn.classList.remove('text-red-500');
            btn.classList.add('text-gray-400');
            btn.classList.remove('border-red-400');
            btn.classList.add('border-gray-200');
        }

        // Update header badge count
        const badge = document.querySelector('.wishlist-badge');
        if (badge) {
            if (data.wishlist_count > 0) {
                badge.textContent = data.wishlist_count > 9 ? '9+' : data.wishlist_count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    });
}
</script>

{{-- Mobile search toggle --}}
<script>
(function () {
    var toggleBtn = document.getElementById('mobile-search-toggle');
    var searchBar = document.getElementById('mobile-search-bar');
    var searchInput = document.getElementById('mobile-search-input');
    if (!toggleBtn || !searchBar) return;
    toggleBtn.addEventListener('click', function () {
        var isHidden = searchBar.classList.contains('hidden');
        searchBar.classList.toggle('hidden', !isHidden);
        if (isHidden && searchInput) {
            searchInput.focus();
        }
    });
    // Close when clicking outside
    document.addEventListener('click', function (e) {
        if (!searchBar.classList.contains('hidden') &&
            !searchBar.contains(e.target) &&
            !toggleBtn.contains(e.target)) {
            searchBar.classList.add('hidden');
        }
    });
})();
</script>

@stack('scripts')
</body>
</html>
