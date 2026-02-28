<x-filament-panels::page>
    <form wire:submit="save">

        {{-- General Settings --}}
        <x-filament::section>
            <x-slot name="heading">General</x-slot>
            <x-slot name="description">Store name, tagline and locale.</x-slot>

            {{ $this->generalForm }}
        </x-filament::section>

        <x-filament::section class="mt-6">
            <x-slot name="heading">Contact Information</x-slot>
            <x-slot name="description">Customer-facing contact details.</x-slot>

            {{ $this->contactForm }}
        </x-filament::section>

        <x-filament::section class="mt-6">
            <x-slot name="heading">Payment Settings</x-slot>
            <x-slot name="description">bKash, Nagad merchant numbers and shipping thresholds.</x-slot>

            {{ $this->paymentForm }}
        </x-filament::section>

        <x-filament::section class="mt-6">
            <x-slot name="heading">Social Media</x-slot>
            <x-slot name="description">Links shown in the storefront footer.</x-slot>

            {{ $this->socialForm }}
        </x-filament::section>

        <div class="mt-6 flex justify-end">
            <x-filament::button type="submit" size="lg">
                Save Settings
            </x-filament::button>
        </div>

    </form>
</x-filament-panels::page>
