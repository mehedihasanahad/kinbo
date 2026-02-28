<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'ShopZone'))</title>
    <meta name="description" content="@yield('meta_description', 'Your one-stop online shop for the best products.')">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-white text-gray-900 antialiased">

    {{-- Top bar --}}
    <div class="bg-gray-900 text-gray-300 text-xs py-2 hidden sm:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <span>Free shipping on orders over ৳999 &nbsp;|&nbsp; Use code <strong class="text-white">WELCOME10</strong> for 10% off</span>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-white transition-colors">Track Order</a>
                <a href="#" class="hover:text-white transition-colors">Help</a>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl text-indigo-600">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6" stroke="white" stroke-width="2"/><path d="M16 10a4 4 0 01-8 0" fill="none" stroke="white" stroke-width="2"/>
                    </svg>
                    {{ config('app.name', 'ShopZone') }}
                </a>

                {{-- Search --}}
                <div class="hidden md:flex flex-1 max-w-xl mx-8">
                    <div class="relative w-full">
                        <input type="text" placeholder="Search products, brands, categories..."
                            class="w-full pl-4 pr-10 py-2.5 border border-gray-200 rounded-full text-sm bg-gray-50 focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100 focus:outline-none transition-all">
                        <button class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Nav actions --}}
                <div class="flex items-center gap-3">
                    @auth
                        <a href="#" class="hidden sm:flex items-center gap-1.5 text-sm text-gray-600 hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            Wishlist
                        </a>
                        <a href="#" class="flex items-center gap-1.5 text-sm text-gray-600 hover:text-indigo-600 transition-colors relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span class="hidden sm:inline">Cart</span>
                        </a>
                        <div class="relative group">
                            <button class="flex items-center gap-1.5 text-sm text-gray-600 hover:text-indigo-600 transition-colors">
                                <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                            </button>
                        </div>
                    @else
                        <a href="#" class="hidden sm:inline text-sm text-gray-600 hover:text-indigo-600 transition-colors">Sign In</a>
                        <a href="#" class="text-sm bg-indigo-600 text-white px-4 py-2 rounded-full hover:bg-indigo-700 transition-colors font-medium">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- Main content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">{{ config('app.name', 'ShopZone') }}</h3>
                    <p class="text-sm leading-relaxed">Your trusted online store for quality products at the best prices. Fast delivery across Bangladesh.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Customer Care</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Track My Order</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Returns & Exchanges</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Newsletter</h4>
                    <p class="text-sm mb-3">Get deals and updates directly in your inbox.</p>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Your email"
                            class="flex-1 text-sm px-3 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500">
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition-colors">
                            Go
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 py-5 text-center text-xs text-gray-600">
            &copy; {{ date('Y') }} {{ config('app.name', 'ShopZone') }}. All rights reserved.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
