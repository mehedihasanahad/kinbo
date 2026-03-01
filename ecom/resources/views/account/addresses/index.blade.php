@extends('layouts.app')

@section('title', __('front.address_book') . ' — ' . config('app.name'))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <h1 class="text-2xl font-bold text-gray-900 mb-8">{{ __('front.my_account') }}</h1>

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Sidebar --}}
        @include('account.partials.sidebar')

        {{-- Main --}}
        <div class="flex-1 min-w-0">

            {{-- Flash --}}
            @if(session('address_success'))
                <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('address_success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-900">{{ __('front.address_book') }}</h2>
                <button onclick="openAddressModal()"
                        class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('front.add_address') }}
                </button>
            </div>

            {{-- Address list --}}
            @if($addresses->isEmpty())
                <div class="bg-white border border-gray-100 rounded-2xl py-16 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">{{ __('front.no_addresses') }}</p>
                    <button onclick="openAddressModal()"
                            class="mt-4 text-sm text-primary-600 hover:text-primary-700 font-medium">
                        {{ __('front.add_first_address') }}
                    </button>
                </div>
            @else
                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach($addresses as $address)
                        <div class="bg-white border {{ $address->is_default ? 'border-primary-300 ring-1 ring-primary-200' : 'border-gray-100' }} rounded-2xl p-5 relative">

                            {{-- Default badge --}}
                            @if($address->is_default)
                                <span class="absolute top-4 right-4 text-[10px] font-bold uppercase tracking-wider text-primary-700 bg-primary-100 px-2 py-0.5 rounded-full">
                                    {{ __('front.default') }}
                                </span>
                            @endif

                            {{-- Label --}}
                            @if($address->label)
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">{{ $address->label }}</p>
                            @endif

                            <p class="text-sm font-semibold text-gray-900">{{ $address->recipient_name }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $address->phone }}</p>
                            <p class="text-sm text-gray-600 mt-1 leading-relaxed">
                                {{ $address->address_line }},
                                @if($address->upazila) {{ $address->upazila }}, @endif
                                {{ $address->city }}, {{ $address->district }}
                                @if($address->zip_code) - {{ $address->zip_code }}@endif
                            </p>

                            {{-- Actions --}}
                            <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-100">
                                <button onclick="openEditModal({{ $address->id }}, {{ json_encode($address) }})"
                                        class="text-xs text-gray-500 hover:text-primary-600 font-medium transition-colors">
                                    {{ __('front.edit') }}
                                </button>

                                @if(! $address->is_default)
                                    <form method="POST" action="{{ route('account.addresses.default', $address) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-gray-500 hover:text-primary-600 font-medium transition-colors">
                                            {{ __('front.set_as_default') }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('account.addresses.destroy', $address) }}"
                                          onsubmit="return confirm('{{ __('front.confirm_delete_address') }}')" class="inline ml-auto">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-medium transition-colors">
                                            {{ __('front.delete') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ── Address Modal ── --}}
<div id="address-modal" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeAddressModal()"></div>

    {{-- Panel --}}
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 id="modal-title" class="text-base font-bold text-gray-900">{{ __('front.add_address') }}</h3>
                <button onclick="closeAddressModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="address-form" method="POST" action="{{ route('account.addresses.store') }}" class="px-6 py-5 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('front.address_label') }} <span class="text-gray-400 font-normal">({{ __('front.optional') }})</span></label>
                        <input type="text" name="label" id="f-label" maxlength="50"
                               placeholder="{{ __('front.address_label_placeholder') }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('front.recipient_name') }} *</label>
                        <input type="text" name="recipient_name" id="f-name" required maxlength="191"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('front.phone') }} *</label>
                        <input type="text" name="phone" id="f-phone" required maxlength="20"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('front.address_line') }} *</label>
                        <input type="text" name="address_line" id="f-address" required
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('front.district') }} *</label>
                        <select name="district" id="f-district" required
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all bg-white">
                            <option value="">— {{ __('front.select_district') }} —</option>
                            @foreach(\App\Models\ShippingZoneDistrict::orderBy('district_name')->pluck('district_name')->unique()->values() as $d)
                                <option value="{{ $d }}">{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('front.city') }} *</label>
                        <input type="text" name="city" id="f-city" required maxlength="100"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('front.upazila') }} <span class="text-gray-400 font-normal">({{ __('front.optional') }})</span></label>
                        <input type="text" name="upazila" id="f-upazila" maxlength="100"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">{{ __('front.zip_code') }} <span class="text-gray-400 font-normal">({{ __('front.optional') }})</span></label>
                        <input type="text" name="zip_code" id="f-zip" maxlength="10"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:border-primary-400 focus:ring-2 focus:ring-primary-100 focus:outline-none transition-all">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_default" value="0">
                            <input type="checkbox" name="is_default" id="f-default" value="1"
                                   class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                            <span class="text-sm text-gray-700">{{ __('front.set_as_default') }}</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeAddressModal()"
                            class="flex-1 py-2.5 text-sm font-semibold border-2 border-gray-200 text-gray-600 hover:border-gray-300 rounded-xl transition-colors">
                        {{ __('front.cancel') }}
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 text-sm font-semibold bg-primary-600 hover:bg-primary-700 text-white rounded-xl transition-colors">
                        {{ __('front.save_address') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const modal       = document.getElementById('address-modal');
const form        = document.getElementById('address-form');
const methodInput = document.getElementById('form-method');
const modalTitle  = document.getElementById('modal-title');

const addTitle  = @json(__('front.add_address'));
const editTitle = @json(__('front.edit_address'));
const storeUrl  = @json(route('account.addresses.store'));

function openAddressModal() {
    modalTitle.textContent  = addTitle;
    form.action             = storeUrl;
    methodInput.value       = 'POST';
    form.reset();
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function openEditModal(id, addr) {
    modalTitle.textContent  = editTitle;
    form.action             = storeUrl.replace('/account/addresses', '/account/addresses/' + id);
    methodInput.value       = 'PUT';

    document.getElementById('f-label').value   = addr.label   || '';
    document.getElementById('f-name').value    = addr.recipient_name;
    document.getElementById('f-phone').value   = addr.phone;
    document.getElementById('f-address').value = addr.address_line;
    document.getElementById('f-district').value= addr.district;
    document.getElementById('f-city').value    = addr.city;
    document.getElementById('f-upazila').value = addr.upazila  || '';
    document.getElementById('f-zip').value     = addr.zip_code || '';
    document.getElementById('f-default').checked = addr.is_default == 1;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddressModal() {
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAddressModal(); });
</script>
@endpush
