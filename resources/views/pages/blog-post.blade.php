@extends('layouts.app')

@section('title', $post->title . ' — ' . config('app.name'))
@section('meta_description', $post->excerpt ?? Str::limit(strip_tags($post->content), 160))
@section('og_title', $post->title)
@section('og_description', $post->excerpt ?? Str::limit(strip_tags($post->content), 160))
@if($post->featured_image)
    @section('og_image', asset('storage/' . $post->featured_image))
@endif

@section('content')

{{-- Breadcrumb --}}
<div class="bg-gray-50 border-b border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <nav class="flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">{{ __('front.home') }}</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('page.blog') }}" class="hover:text-primary-600 transition-colors">{{ __('front.page_blog_title') }}</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-700 font-medium truncate max-w-xs">{{ $post->title }}</span>
        </nav>
    </div>
</div>

<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header --}}
    <header class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            @if($post->category)
                <span class="text-xs font-semibold text-primary-600 bg-primary-50 px-3 py-1 rounded-full">
                    {{ $post->category }}
                </span>
            @endif
            <span class="text-xs text-gray-400">{{ $post->published_at?->format('d F Y') }}</span>
            <span class="text-xs text-gray-400">·</span>
            <span class="text-xs text-gray-400">
                {{ $post->reading_time }} {{ app()->getLocale() === 'bn' ? 'মিনিট পড়া' : 'min read' }}
            </span>
        </div>

        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight mb-4">
            {{ $post->title }}
        </h1>

        @if($post->excerpt)
            <p class="text-lg text-gray-500 leading-relaxed border-l-4 border-primary-300 pl-4">
                {{ $post->excerpt }}
            </p>
        @endif

        @if($post->author_name)
            <div class="flex items-center gap-2 mt-5 pt-5 border-t border-gray-100">
                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-sm">
                    {{ strtoupper(substr($post->author_name, 0, 1)) }}
                </div>
                <span class="text-sm font-medium text-gray-700">{{ $post->author_name }}</span>
            </div>
        @endif
    </header>

    {{-- Featured image --}}
    @if($post->featured_image)
        <div class="mb-10 rounded-2xl overflow-hidden">
            <img src="{{ asset('storage/' . $post->featured_image) }}"
                 alt="{{ $post->title }}"
                 class="w-full object-cover max-h-96">
        </div>
    @endif

    {{-- Content --}}
    <div class="prose prose-sm sm:prose lg:prose-lg max-w-none
                prose-headings:font-bold prose-headings:text-gray-900
                prose-p:text-gray-700 prose-p:leading-relaxed
                prose-a:text-primary-600 prose-a:no-underline hover:prose-a:underline
                prose-img:rounded-xl prose-blockquote:border-primary-400
                prose-strong:text-gray-900">
        {!! $post->content !!}
    </div>

    {{-- Share --}}
    <div class="mt-12 pt-6 border-t border-gray-100 flex items-center gap-4">
        <span class="text-sm font-semibold text-gray-600">
            {{ app()->getLocale() === 'bn' ? 'শেয়ার করুন:' : 'Share:' }}
        </span>
        @php $shareUrl = urlencode(url()->current()); $shareText = urlencode($post->title); @endphp
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
           target="_blank" rel="noopener"
           class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition-colors text-xs font-bold">
            f
        </a>
        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}"
           target="_blank" rel="noopener"
           class="w-8 h-8 rounded-full bg-sky-500 text-white flex items-center justify-center hover:bg-sky-600 transition-colors text-xs font-bold">
            𝕏
        </a>
    </div>

</article>

{{-- Related posts --}}
@if($related->isNotEmpty())
<section class="bg-primary-50/50 py-14">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-gray-900 mb-8">
            {{ app()->getLocale() === 'bn' ? 'আরও পড়ুন' : 'More Articles' }}
        </h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($related as $rel)
            <article class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col">
                <a href="{{ route('page.blog.post', $rel->slug) }}" class="block overflow-hidden aspect-video bg-primary-100">
                    @if($rel->featured_image)
                        <img src="{{ asset('storage/' . $rel->featured_image) }}" alt="{{ $rel->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                    @endif
                </a>
                <div class="p-4 flex flex-col flex-1">
                    <span class="text-xs text-gray-400 mb-2">{{ $rel->published_at?->format('d M Y') }}</span>
                    <h3 class="font-semibold text-gray-900 text-sm leading-snug line-clamp-2">
                        <a href="{{ route('page.blog.post', $rel->slug) }}" class="hover:text-primary-600 transition-colors">
                            {{ $rel->title }}
                        </a>
                    </h3>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('styles')
<style>
/* Ensure RichEditor output images are responsive */
.prose img { max-width: 100%; height: auto; }
</style>
@endpush
