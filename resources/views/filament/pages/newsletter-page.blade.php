<x-filament-panels::page>

    {{-- Stats row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-filament::section>
            <div class="text-center">
                <p class="text-3xl font-bold text-primary-600">{{ $this->totalActive }}</p>
                <p class="text-sm text-gray-500 mt-1">Active Subscribers</p>
            </div>
        </x-filament::section>
        <x-filament::section>
            <div class="text-center">
                <p class="text-3xl font-bold text-blue-600">{{ \App\Models\Subscriber::where('status','pending')->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Pending Confirmation</p>
            </div>
        </x-filament::section>
        <x-filament::section>
            <div class="text-center">
                <p class="text-3xl font-bold text-gray-400">{{ \App\Models\Subscriber::where('status','unsubscribed')->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Unsubscribed</p>
            </div>
        </x-filament::section>
    </div>

    {{-- Compose form --}}
    <x-filament::section icon="heroicon-o-pencil-square" icon-color="primary">
        <x-slot name="heading">Compose Newsletter</x-slot>
        <x-slot name="description">Write your newsletter and send it to active subscribers. Emails are dispatched via queue.</x-slot>

        <form wire:submit.prevent="send">
            {{ $this->form }}

            <div class="mt-6 flex items-center gap-4">
                <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                    Send Newsletter
                </x-filament::button>
                <p class="text-sm text-gray-500">
                    Will be sent to <strong>{{ $this->recipientCount }}</strong> active subscriber(s).
                </p>
            </div>
        </form>
    </x-filament::section>

    {{-- Subscriber list --}}
    <x-filament::section icon="heroicon-o-users" icon-color="gray">
        <x-slot name="heading">All Subscribers</x-slot>

        @php
            $subscribers = \App\Models\Subscriber::orderByDesc('created_at')->paginate(15, ['*'], 'sub_page');
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="py-2 pr-4 font-semibold text-gray-600 dark:text-gray-300">Email</th>
                        <th class="py-2 pr-4 font-semibold text-gray-600 dark:text-gray-300">Locale</th>
                        <th class="py-2 pr-4 font-semibold text-gray-600 dark:text-gray-300">Status</th>
                        <th class="py-2 pr-4 font-semibold text-gray-600 dark:text-gray-300">Subscribed</th>
                        <th class="py-2 font-semibold text-gray-600 dark:text-gray-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($subscribers as $sub)
                    <tr>
                        <td class="py-2 pr-4 text-gray-800 dark:text-gray-200">{{ $sub->email }}</td>
                        <td class="py-2 pr-4">
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $sub->locale === 'en' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ strtoupper($sub->locale) }}
                            </span>
                        </td>
                        <td class="py-2 pr-4">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                {{ $sub->status === 'active'       ? 'bg-emerald-100 text-emerald-700'
                                 : ($sub->status === 'pending'     ? 'bg-blue-100 text-blue-700'
                                 :                                    'bg-gray-100 text-gray-500') }}">
                                {{ ucfirst($sub->status) }}
                            </span>
                        </td>
                        <td class="py-2 pr-4 text-gray-500">{{ $sub->created_at->format('d M Y') }}</td>
                        <td class="py-2">
                            <button
                                wire:click="deleteSubscriber({{ $sub->id }})"
                                wire:confirm="Delete this subscriber?"
                                class="text-xs text-red-500 hover:text-red-700 font-medium">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-400">No subscribers yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscribers->hasPages())
            <div class="mt-4">
                {{ $subscribers->links() }}
            </div>
        @endif
    </x-filament::section>

    <x-filament-actions::modals />

</x-filament-panels::page>
