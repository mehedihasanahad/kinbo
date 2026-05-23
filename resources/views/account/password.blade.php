@extends('layouts.app')

@section('title', __('front.change_password') . ' — ' . config('app.name'))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-2xl font-bold text-gray-900 mb-8">{{ __('front.my_account') }}</h1>

    <div class="flex flex-col lg:flex-row gap-8">

        @include('account.partials.sidebar')

        <div class="flex-1 min-w-0">

            @if(session('password_updated'))
                <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-4 mb-6">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-emerald-800">{{ __('front.password_updated_success') }}</p>
                </div>
            @endif

            <div class="bg-white border border-gray-100 rounded-2xl p-6">

                <h2 class="text-base font-bold text-gray-900 mb-6">{{ __('front.change_password') }}</h2>

                @if(auth()->user()->provider)
                    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-6">
                        <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-blue-700">{{ __('front.oauth_set_password_note') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('account.password.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    @if(! auth()->user()->provider)
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">
                                {{ __('front.current_password') }}
                            </label>
                            <x-password-input name="current_password" autocomplete="current-password" :has-error="$errors->has('current_password')" />
                            @error('current_password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                            {{ __('front.new_password') }}
                        </label>
                        <x-password-input name="password" autocomplete="new-password" :has-error="$errors->has('password')" />
                        <x-password-rules for="password" />
                        @error('password')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">
                            {{ __('front.confirm_new_password') }}
                        </label>
                        <x-password-input name="password_confirmation" autocomplete="new-password" />
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('front.update_password') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
