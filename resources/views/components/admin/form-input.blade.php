@props(['name', 'label', 'value' => null, 'type' => 'text', 'required' => false, 'multiline' => false])

@php
    $inputValue = $type === 'password' ? '' : old($name, $value);
    $inputValue = is_scalar($inputValue) ? $inputValue : '';
    $inputClasses = 'block w-full rounded-control border border-outline bg-surface px-4 py-3 text-sm focus:border-brand focus:ring-brand';
@endphp

<div class="space-y-2">
    <label for="{{ $name }}" class="block text-sm font-semibold text-ink">{{ $label }}</label>
    @if ($multiline)
        <textarea id="{{ $name }}" name="{{ $name }}" rows="4" @required($required)
            @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
            {{ $attributes->class($inputClasses) }}>{{ $inputValue }}</textarea>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ $inputValue }}" @required($required)
            @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
            {{ $attributes->class($inputClasses) }}>
    @endif
    @error($name)
        <p id="{{ $name }}-error" class="text-sm text-danger">{{ $message }}</p>
    @enderror
</div>
