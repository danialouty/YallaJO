
@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'disabled' => false,
])

@php
    $baseClasses = '
        inline-flex items-center justify-center gap-2
        rounded-control font-semibold
        transition-colors
        focus-visible:outline-2
        focus-visible:outline-offset-2
        focus-visible:outline-brand
        disabled:cursor-not-allowed
        disabled:opacity-50
    ';

    $variantClasses = match ($variant) {
        'secondary' => '
            bg-forest text-white
            hover:bg-forest/90
        ',

        'outline' => '
            border border-outline
            bg-transparent text-ink
            hover:bg-brand/5
        ',

        default => '
            bg-brand text-white
            hover:bg-brand-dark
        ',
    };

    $sizeClasses = match ($size) {
        'sm' => 'min-h-10 px-3 py-2 text-sm',
        'lg' => 'min-h-12 px-6 py-3 text-base',
        default => 'min-h-11 px-4 py-2.5 text-sm',
    };
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->class([
        $baseClasses,
        $variantClasses,
        $sizeClasses,
    ]) }}
    @disabled($disabled)
>
    {{ $slot }}
</button>
