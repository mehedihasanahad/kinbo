@extends('layouts.app')

@section('title', __('front.my_reviews') . ' — ' . config('app.name'))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-2xl font-bold text-gray-900 mb-8">{{ __('front.my_account') }}</h1>

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Sidebar --}}
        @include('account.partials.sidebar')

        {{-- Main --}}
        <div class="flex-1 min-w-0">

            <div class="flex items-center gap-3 mb-6">
                <h2 class="text-lg font-bold text-gray-900">{{ __('front.my_reviews') }}</h2>
                @if($reviews->total() > 0)
                    <span class="text-sm text-gray-400">({{ $reviews->total() }})</span>
                @endif
            </div>

            @if($reviews->isEmpty())
                <div class="bg-white border border-gray-100 rounded-2xl py-16 text-center">
                    <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">{{ __('front.no_reviews_yet') }}</p>
                    <a href="{{ route('home') }}" class="mt-4 inline-block text-sm text-primary-600 hover:text-primary-700 font-medium">
                        {{ __('front.start_shopping') }} →
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        @php
                            $locale = app()->getLocale();
                            $product = $review->product;
                            $productName = $product?->getTranslation($locale)?->name ?? $product?->getTranslation('en')?->name ?? '—';
                            $productSlug = $product?->slug ?? '#';
                            $img = $product?->primaryImage;
                        @endphp
                        <div class="bg-white border border-gray-100 rounded-2xl p-5">
                            <div class="flex items-start gap-4">
                                {{-- Product image --}}
                                <a href="{{ route('product.show', $productSlug) }}" class="shrink-0">
                                    @if($img)
                                        <img src="{{ asset('storage/' . $img->path) }}"
                                             alt="{{ $productName }}"
                                             class="w-16 h-16 rounded-xl object-cover border border-gray-100">
                                    @else
                                        <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center">
                                            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </a>

                                <div class="flex-1 min-w-0">
                                    {{-- Product name + status --}}
                                    <div class="flex items-start justify-between gap-2">
                                        <a href="{{ route('product.show', $productSlug) }}"
                                           class="text-sm font-semibold text-gray-900 hover:text-primary-600 transition-colors truncate">
                                            {{ $productName }}
                                        </a>
                                        @if($review->is_approved)
                                            <span class="shrink-0 text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                                {{ __('front.review_approved') }}
                                            </span>
                                        @else
                                            <span class="shrink-0 text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full">
                                                {{ __('front.review_pending') }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Stars --}}
                                    <div class="flex items-center gap-0.5 mt-1.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                        <span class="text-xs text-gray-400 ml-1.5">{{ $review->created_at->format('d M Y') }}</span>
                                    </div>

                                    {{-- Review content --}}
                                    @if($review->title)
                                        <p class="text-sm font-semibold text-gray-800 mt-2">{{ $review->title }}</p>
                                    @endif
                                    @if($review->body)
                                        <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $review->body }}</p>
                                    @endif

                                    {{-- Helpful count --}}
                                    @if($review->helpful_count > 0)
                                        <p class="text-xs text-gray-400 mt-2">
                                            {{ trans_choice('front.helpful_count', $review->helpful_count, ['count' => $review->helpful_count]) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($reviews->hasPages())
                    <div class="mt-8 flex justify-center">{{ $reviews->links() }}</div>
                @endif
            @endif

        </div>
    </div>
</div>
@endsection
