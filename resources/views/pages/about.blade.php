@extends('layouts.app')

@section('title', __('front.page_about_title') . ' — ' . config('app.name'))
@section('meta_description', __('front.page_about_mission'))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 text-white py-20 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 -left-16 w-72 h-72 bg-accent-500/10 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block bg-accent-500/20 text-accent-300 text-xs font-bold px-4 py-1.5 rounded-full mb-5 tracking-widest uppercase">
            {{ __('front.page_about_badge') }}
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold mb-5 leading-tight">
            {{ __('front.page_about_title') }}
        </h1>
        <p class="text-primary-200 text-lg max-w-2xl mx-auto leading-relaxed">
            {{ __('front.page_about_mission') }}
        </p>
    </div>
</section>

{{-- Stats --}}
<section class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-8 text-center">
            @foreach([
                ['num' => '10,000+', 'label' => __('front.page_about_stat_products')],
                ['num' => '50,000+', 'label' => __('front.page_about_stat_customers')],
                ['num' => '64+',     'label' => __('front.page_about_stat_cities')],
                ['num' => '24/7',    'label' => __('front.page_about_stat_support')],
            ] as $stat)
            <div>
                <p class="text-4xl font-extrabold text-primary-700 mb-1">{{ $stat['num'] }}</p>
                <p class="text-gray-500 text-sm font-medium">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Who we are --}}
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid md:grid-cols-2 gap-12 items-center">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-5">{{ __('front.page_about_who_title') }}</h2>
            <p class="text-gray-600 leading-relaxed mb-6">{{ __('front.page_about_who') }}</p>

            <div class="space-y-4">
                <div>
                    <h3 class="font-semibold text-primary-700 mb-1">{{ __('front.page_about_mission_title') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ __('front.page_about_mission') }}</p>
                </div>
                <div>
                    <h3 class="font-semibold text-primary-700 mb-1">{{ __('front.page_about_vision_title') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ __('front.page_about_vision') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-3xl p-10 flex items-center justify-center">
            <svg class="w-40 h-40 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.8"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
    </div>
</section>

{{-- Core Values --}}
<section class="bg-primary-50/60 py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('front.page_about_values_title') }}</h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'title' => __('front.page_about_val1_title'), 'desc' => __('front.page_about_val1_desc')],
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => __('front.page_about_val2_title'), 'desc' => __('front.page_about_val2_desc')],
                ['icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'title' => __('front.page_about_val3_title'), 'desc' => __('front.page_about_val3_desc')],
                ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => __('front.page_about_val4_title'), 'desc' => __('front.page_about_val4_desc')],
            ] as $val)
            <div class="bg-white rounded-2xl p-6 shadow-sm text-center">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $val['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">{{ $val['title'] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $val['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('front.hero_cta_shop') }}</h2>
    <a href="{{ route('shop.category') }}"
       class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3.5 rounded-full transition-colors text-sm shadow-md">
        {{ __('front.hero_cta_shop') }}
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
        </svg>
    </a>
</section>

@endsection
