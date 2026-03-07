@extends('layouts.app')

@section('title', __('front.page_contact_title') . ' — ' . config('app.name'))
@section('meta_description', __('front.page_contact_subtitle'))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 text-white py-16 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-16 -right-16 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block bg-accent-500/20 text-accent-300 text-xs font-bold px-4 py-1.5 rounded-full mb-5 tracking-widest uppercase">
            {{ __('front.page_contact_badge') }}
        </span>
        <h1 class="text-4xl sm:text-5xl font-extrabold mb-4">{{ __('front.page_contact_title') }}</h1>
        <p class="text-primary-200 text-lg">{{ __('front.page_contact_subtitle') }}</p>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid lg:grid-cols-5 gap-12">

        {{-- Contact Info --}}
        <div class="lg:col-span-2 space-y-8">
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('front.page_contact_info_title') }}</h2>
                <div class="space-y-5">

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-0.5">{{ __('front.page_contact_email') }}</p>
                            @php $contactEmail = \App\Models\Setting::get('contact_email', 'support@' . parse_url(config('app.url'), PHP_URL_HOST)); @endphp
                            <a href="mailto:{{ $contactEmail }}" class="text-primary-600 hover:underline font-medium">{{ $contactEmail }}</a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-0.5">{{ __('front.page_contact_phone') }}</p>
                            @php $contactPhone = \App\Models\Setting::get('contact_phone', '+880 1700-000000'); @endphp
                            <a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}" class="text-primary-600 hover:underline font-medium">{{ $contactPhone }}</a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-0.5">{{ __('front.page_contact_address') }}</p>
                            @php $contactAddress = \App\Models\Setting::get('contact_address', 'Dhaka, Bangladesh'); @endphp
                            <p class="text-gray-700 font-medium">{{ $contactAddress }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-0.5">{{ __('front.page_contact_hours') }}</p>
                            <p class="text-gray-700 font-medium">{{ __('front.page_contact_hours_val') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact Form --}}
        <div class="lg:col-span-3">
            <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
                <h2 class="text-xl font-bold text-gray-900 mb-6">{{ __('front.page_contact_form_title') }}</h2>

                @if(session('contact_sent'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-4 mb-6 text-sm">
                        {{ __('front.page_contact_sent') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('page.contact.send') }}" class="space-y-5">
                    @csrf

                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('front.page_contact_name') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all @error('name') border-red-400 @enderror">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('front.page_contact_email_field') }}</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all @error('email') border-red-400 @enderror">
                            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('front.page_contact_subject') }}</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required
                               placeholder="{{ __('front.page_contact_subject_ph') }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all @error('subject') border-red-400 @enderror">
                        @error('subject')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('front.page_contact_message') }}</label>
                        <textarea name="message" rows="5" required
                                  placeholder="{{ __('front.page_contact_message_ph') }}"
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all resize-none @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                        @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        {{ __('front.page_contact_send') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
