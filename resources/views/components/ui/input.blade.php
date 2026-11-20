
@props([
    'name' => null,
    'label' => null,
    'type' => 'text',
    'value' => null,
    'id' => null,
    'hint' => null,
    'required' => false,
    'bag' => 'default',
])

@php
    $inputId = $id ?? str_replace(
        ['.', '[', ']'],
        '-',
        $name ?? ''
    );

    $errorMessage = isset($errors)
        ? $errors->getBag($bag)->first($name)
        : null;

    $canShowValue = !in_array(
        $type,
        ['password', 'file'],
        true
    );

    $inputValue = $canShowValue
        ? (request()->hasSession() ? old($name, $value) : $value)
        : null;

    $describedBy = trim(
        ($hint ? $inputId . '-hint' : '') . ' ' .
        ($errorMessage ? $inputId . '-error' : '')
    );

    $baseClasses = '
        block w-full rounded-control
        border bg-surface px-4 py-3
        text-sm text-ink
        placeholder:text-muted/60
        transition-colors
        focus:outline-none
        focus:ring-2
        disabled:cursor-not-allowed
        disabled:bg-canvas
        disabled:opacity-60
    ';

    $stateClasses = $errorMessage
        ? 'border-danger focus:border-danger focus:ring-danger/20'
        : 'border-outline/60 focus:border-brand focus:ring-brand/20';
@endphp

<div class="w-full space-y-2">

    @if ($label)
        <label
            for="{{ $inputId }}"
            class="block text-sm font-semibold text-ink"
        >
            {{ $label }}

            @if ($required)
                <span class="text-danger" aria-hidden="true">
                    *
                </span>
            @endif
        </label>
    @endif

    <input
        id="{{ $inputId }}"
        name="{{ $name }}"
        type="{{ $type }}"

        @if ($canShowValue)
            value="{{ $inputValue }}"
        @endif

        @required($required)

        @if ($errorMessage)
            aria-invalid="true"
        @endif

        @if ($describedBy !== '')
            aria-describedby="{{ $describedBy }}"
        @endif

        {{ $attributes->class([
            $baseClasses,
            $stateClasses,
        ]) }}
    >

    @if ($hint)
        <p
            id="{{ $inputId }}-hint"
            class="text-xs text-muted"
        >
            {{ $hint }}
        </p>
    @endif

    @if ($errorMessage)
        <p
            id="{{ $inputId }}-error"
            class="text-xs text-danger"
            role="alert"
        >
            {{ $errorMessage }}
        </p>
    @endif

</div>
