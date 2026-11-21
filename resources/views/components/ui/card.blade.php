
@props([
    'variant' => 'default',
    'padding' => 'md',
])

@php
    $baseClasses = '
        rounded-card
        transition-shadow
        duration-200
    ';

    $variantClasses = match ($variant) {
        'outline' => '
            border border-outline/60
            bg-surface
            shadow-none
        ',

        'soft' => '
            border border-brand/10
            bg-brand/5
            shadow-none
        ',

        default => '
            border border-ink/5
            bg-surface
            shadow-card
        ',
    };

    $paddingClasses = match ($padding) {
        'none' => 'p-0',
        'sm' => 'p-4',
        'lg' => 'p-8',
        default => 'p-6',
    };
@endphp

<div
    {{ $attributes->class([
        $baseClasses,
        $variantClasses,
        $paddingClasses,
    ]) }}
>
    {{ $slot }}
</div>
