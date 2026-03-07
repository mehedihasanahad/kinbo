@extends('layouts.app')

@section('title', __('front.page_blog_title') . ' — ' . config('app.name'))
@section('meta_description', __('front.page_blog_subtitle'))

@section('content')

{{-- Hero --}}
<section class="bg-linear-to-br from-primary-950 via-primary-900 to-primary-800 text-white py-16 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-16 -right-16 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block bg-accent-500/20 text-accent-300 text-xs font-bold px-4 py-1.5 rounded-full mb-5 tracking-widest uppercase">
            {{ __('front.page_blog_badge') }}
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold mb-4">{{ __('front.page_blog_title') }}</h1>
        <p class="text-primary-200 text-lg">{{ __('front.page_blog_subtitle') }}</p>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    @if($posts->isNotEmpty())

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-7">
            @foreach($posts as $post)
            <article class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col">

                {{-- Thumbnail --}}
                <a href="{{ route('page.blog.post', $post->slug) }}" class="block overflow-hidden aspect-video bg-primary-100">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}"
                             alt="{{ $post->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                    @endif
                </a>

                <div class="p-5 flex flex-col flex-1">
                    {{-- Meta --}}
                    <div class="flex items-center gap-3 mb-3">
                        @if($post->category)
                            <span class="text-xs font-semibold text-primary-600 bg-primary-50 px-2.5 py-0.5 rounded-full">
                                {{ $post->category }}
                            </span>
                        @endif
                        <span class="text-xs text-gray-400">{{ $post->published_at?->format('d M Y') }}</span>
                    </div>

                    {{-- Title --}}
                    <h2 class="font-bold text-gray-900 text-base leading-snug mb-2 line-clamp-2">
                        <a href="{{ route('page.blog.post', $post->slug) }}" class="hover:text-primary-600 transition-colors">
                            {{ $post->title }}
                        </a>
                    </h2>

                    {{-- Excerpt --}}
                    @if($post->excerpt)
                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 flex-1 mb-4">
                            {{ $post->excerpt }}
                        </p>
                    @else
                        <div class="flex-1"></div>
                    @endif

                    {{-- Footer --}}
                    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                        <span class="text-xs text-gray-400">
                            {{ $post->reading_time }} {{ app()->getLocale() === 'bn' ? 'মিনিট পড়া' : 'min read' }}
                        </span>
                        <a href="{{ route('page.blog.post', $post->slug) }}"
                           class="text-xs font-semibold text-primary-600 hover:text-primary-800 transition-colors flex items-center gap-1">
                            {{ app()->getLocale() === 'bn' ? 'পড়ুন' : 'Read more' }}
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($posts->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif

    @else

        <div class="text-center py-20">
            <div class="w-20 h-20 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ __('front.page_blog_coming_soon') }}</h2>
            <p class="text-gray-500 max-w-md mx-auto text-sm leading-relaxed">{{ __('front.page_blog_coming_sub') }}</p>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 mt-8 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-full transition-colors text-sm">
                {{ __('front.home') }}
            </a>
        </div>

    @endif

</section>

@endsection
