@extends('layouts.app')

@section('title', __('front.register_title') . ' — ' . config('app.name', 'ShopZone'))

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-14 px-4">
    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-8 py-10">

            {{-- Heading --}}
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('front.register_title') }}</h1>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('front.full_name') }}
                    </label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}"
                           required autofocus autocomplete="name"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm bg-gray-50 focus:bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all
                                  {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('front.email') }}
                    </label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           required autocomplete="username"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm bg-gray-50 focus:bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all
                                  {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('front.password') }}
                    </label>
                    <input type="password" id="password" name="password"
                           required autocomplete="new-password"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm bg-gray-50 focus:bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all
                                  {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('front.confirm_password') }}
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           required autocomplete="new-password"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm bg-gray-50 focus:bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all
                                  {{ $errors->has('password_confirmation') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('password_confirmation')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white font-semibold py-3 rounded-xl transition-colors text-sm">
                    {{ __('front.register') }}
                </button>
            </form>

            {{-- Login link --}}
            <p class="mt-6 text-center text-sm text-gray-500">
                {{ __('front.have_account') }}
                <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors">
                    {{ __('front.sign_in') }}
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
