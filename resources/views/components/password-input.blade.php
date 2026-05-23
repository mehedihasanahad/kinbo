@props([
    'name',
    'id'           => null,
    'autocomplete' => 'off',
    'hasError'     => false,
])

@php $inputId = $id ?? $name; $btnId = 'pwbtn-' . $inputId; @endphp

<div class="relative">
    <input type="password"
           id="{{ $inputId }}"
           name="{{ $name }}"
           autocomplete="{{ $autocomplete }}"
           class="w-full rounded-xl border px-4 py-2.5 pr-11 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent {{ $hasError ? 'border-red-400' : 'border-gray-200' }}">

    <button type="button" id="{{ $btnId }}"
            onclick="(function(b){var i=document.getElementById('{{ $inputId }}');var show=i.type==='text';i.type=show?'password':'text';b.querySelector('.pw-eye').style.display=show?'block':'none';b.querySelector('.pw-eye-slash').style.display=show?'none':'block';})(this)"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">

        {{-- Eye icon: shown by default (password hidden) --}}
        <svg class="pw-eye w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>

        {{-- Eye-slash icon: hidden by default --}}
        <svg class="pw-eye-slash w-5 h-5" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
        </svg>
    </button>
</div>
