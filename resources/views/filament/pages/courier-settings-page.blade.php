<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->steadfastForm }}
        {{ $this->pathaoForm }}
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
