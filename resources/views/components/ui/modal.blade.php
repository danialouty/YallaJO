
@props([
    'name',
    'title',
    'size' => 'md',
    'errorBag' => 'default',
])

@php
    $sizeClasses = match ($size) {
        'sm' => 'max-w-md',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        default => 'max-w-lg',
    };

    $hasErrors = isset($errors)
        && $errors->getBag($errorBag)->any();

    $shouldReopen = request()->hasSession()
        && $hasErrors
        && old('_modal') === $name;
@endphp

<dialog
    id="{{ $name }}"
    aria-labelledby="{{ $name }}-title"
    aria-modal="true"

    @if ($shouldReopen)
        data-modal-reopen
    @endif

    {{ $attributes->class([
        'fixed inset-0 m-auto',
        'w-[calc(100%-2rem)]',
        'max-h-[calc(100dvh-2rem)]',
        'overflow-y-auto',
        'rounded-card',
        'border border-outline/30',
        'bg-surface text-ink',
        'p-0 shadow-2xl',
        'backdrop:bg-ink/60',
        'backdrop:backdrop-blur-sm',
        $sizeClasses,
    ]) }}
>
    {{-- Modal Header --}}
    <div
        class="flex items-center justify-between gap-4
               border-b border-outline/30
               px-5 py-4 sm:px-6"
    >
        <h2
            id="{{ $name }}-title"
            class="font-display text-xl font-bold"
        >
            {{ $title }}
        </h2>

        <button
            type="button"
            data-modal-close
            aria-label="إغلاق النافذة"
            class="flex size-10 shrink-0 items-center
                   justify-center rounded-control
                   text-muted transition-colors
                   hover:bg-brand/10 hover:text-brand
                   focus-visible:outline-2
                   focus-visible:outline-brand"
        >
            <span aria-hidden="true" class="text-2xl">
                &times;
            </span>
        </button>
    </div>

    {{-- Modal Body --}}
    <div class="px-5 py-5 sm:px-6">
        {{ $slot }}
    </div>
</dialog>
