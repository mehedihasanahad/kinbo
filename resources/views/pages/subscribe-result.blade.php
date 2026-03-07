@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
<div class="min-h-[50vh] flex items-center justify-center px-4 py-20">
    <div class="text-center max-w-md">
        @if($type === 'confirmed')
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        @else
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
        @endif
        <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $message }}</h1>
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 mt-6 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-full transition-colors text-sm">
            {{ __('front.home') }}
        </a>
    </div>
</div>
@endsection
