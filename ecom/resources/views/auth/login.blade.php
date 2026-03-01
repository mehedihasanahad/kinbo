@extends('layouts.app')

@section('title', __('front.login_title') . ' — ' . config('app.name', 'ShopZone'))

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-14 px-4">
    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-8 py-10">

            {{-- Logo / heading --}}
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('front.login_title') }}</h1>
            </div>

            {{-- Session Status --}}
            @if(session('status'))
                <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('front.email') }}
                    </label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm bg-gray-50 focus:bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all
                                  {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            {{ __('front.password') }}
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-xs text-primary-600 hover:text-primary-700 transition-colors">
                                {{ __('front.forgot_password') }}
                            </a>
                        @endif
                    </div>
                    <input type="password" id="password" name="password"
                           required autocomplete="current-password"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm bg-gray-50 focus:bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all
                                  {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember_me" name="remember"
                           class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    <label for="remember_me" class="text-sm text-gray-600">
                        {{ __('front.remember_me') }}
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white font-semibold py-3 rounded-xl transition-colors text-sm">
                    {{ __('front.sign_in') }}
                </button>
            </form>

            {{-- Register link --}}
            <p class="mt-6 text-center text-sm text-gray-500">
                {{ __('front.no_account') }}
                <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-semibold transition-colors">
                    {{ __('front.register') }}
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
