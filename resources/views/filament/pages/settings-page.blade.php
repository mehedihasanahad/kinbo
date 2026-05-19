<x-filament-panels::page>
    <form wire:submit="save">

        {{-- Branding --}}
        <x-filament::section>
            <x-slot name="heading">Branding</x-slot>
            <x-slot name="description">Store logo and favicon displayed across the storefront.</x-slot>

            {{ $this->brandingForm }}
        </x-filament::section>

        {{-- General --}}
        <x-filament::section class="mt-6">
            <x-slot name="heading">General</x-slot>
            <x-slot name="description">Store name, tagline and locale.</x-slot>

            {{ $this->generalForm }}
        </x-filament::section>

        {{-- Contact --}}
        <x-filament::section class="mt-6">
            <x-slot name="heading">Contact Information</x-slot>
            <x-slot name="description">Customer-facing contact details.</x-slot>

            {{ $this->contactForm }}
        </x-filament::section>

        {{-- Payment --}}
        <x-filament::section class="mt-6">
            <x-slot name="heading">Payment Settings</x-slot>
            <x-slot name="description">bKash, Nagad merchant numbers and shipping thresholds.</x-slot>

            {{ $this->paymentForm }}
        </x-filament::section>

        {{-- Return & Refund Policy --}}
        <x-filament::section class="mt-6">
            <x-slot name="heading">Return & Refund Policy</x-slot>
            <x-slot name="description">Control whether returns are allowed, the return window, and refund processing time.</x-slot>

            {{ $this->policyForm }}
        </x-filament::section>

        {{-- Social --}}
        <x-filament::section class="mt-6">
            <x-slot name="heading">Social Media</x-slot>
            <x-slot name="description">Links shown in the storefront footer.</x-slot>

            {{ $this->socialForm }}
        </x-filament::section>

        {{-- OAuth / Social Login --}}
        <x-filament::section class="mt-6">
            <x-slot name="heading">Social Login</x-slot>
            <x-slot name="description">Allow customers to sign in with their Google account. Enter your Google Cloud OAuth credentials to enable this feature.</x-slot>

            {{ $this->oauthForm }}
        </x-filament::section>

        {{-- Homepage --}}
        <x-filament::section class="mt-6">
            <x-slot name="heading">Homepage</x-slot>
            <x-slot name="description">Control the promotional banner displayed between New Arrivals and Best Deals.</x-slot>

            {{ $this->homepageImageForm }}

            <div class="mt-6">
                {{ $this->homepageForm }}
            </div>
        </x-filament::section>

        <div class="mt-6 flex justify-end">
            <x-filament::button type="submit" size="lg">
                Save Settings
            </x-filament::button>
        </div>

    </form>
</x-filament-panels::page>
