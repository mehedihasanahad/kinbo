@props(['for', 'confirmFor' => null])

<div id="pw-rules-{{ $for }}" style="display:none" class="mt-2.5 p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-1.5">
    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide mb-2">Password requirements</p>

    <div data-rule="length" class="flex items-center gap-2">
        <svg data-unmet class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/></svg>
        <svg data-met class="w-3.5 h-3.5 text-emerald-500 shrink-0" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        <span data-text class="text-xs text-gray-400">At least 8 characters</span>
    </div>

    <div data-rule="letter" class="flex items-center gap-2">
        <svg data-unmet class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/></svg>
        <svg data-met class="w-3.5 h-3.5 text-emerald-500 shrink-0" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        <span data-text class="text-xs text-gray-400">Contains a letter</span>
    </div>

    <div data-rule="number" class="flex items-center gap-2">
        <svg data-unmet class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/></svg>
        <svg data-met class="w-3.5 h-3.5 text-emerald-500 shrink-0" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        <span data-text class="text-xs text-gray-400">Contains a number</span>
    </div>

    @if($confirmFor)
    <div data-rule="match" class="flex items-center gap-2">
        <svg data-unmet class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/></svg>
        <svg data-met class="w-3.5 h-3.5 text-emerald-500 shrink-0" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        <span data-text class="text-xs text-gray-400">Passwords match</span>
    </div>
    @endif
</div>

<script>
(function () {
    var wrapper = document.getElementById('pw-rules-{{ $for }}');
    var pwInput = document.getElementById('{{ $for }}');
    @if($confirmFor)
    var confirmInput = document.getElementById('{{ $confirmFor }}');
    @endif

    function setRule(rule, passed) {
        var item = wrapper.querySelector('[data-rule="' + rule + '"]');
        if (!item) return;
        item.querySelector('[data-unmet]').style.display = passed ? 'none'   : '';
        item.querySelector('[data-met]').style.display   = passed ? ''       : 'none';
        item.querySelector('[data-text]').className      = 'text-xs ' + (passed ? 'text-emerald-600 font-medium' : 'text-gray-400');
    }

    function update() {
        var val = pwInput ? pwInput.value : '';
        wrapper.style.display = val.length > 0 ? '' : 'none';
        setRule('length', val.length >= 8);
        setRule('letter', /[a-zA-Z]/.test(val));
        setRule('number', /[0-9]/.test(val));
        @if($confirmFor)
        var confirmVal = confirmInput ? confirmInput.value : '';
        setRule('match', val.length > 0 && confirmVal === val);
        @endif
    }

    if (pwInput) pwInput.addEventListener('input', update);
    @if($confirmFor)
    if (confirmInput) confirmInput.addEventListener('input', update);
    @endif
})();
</script>
