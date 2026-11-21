@props(['backRoute', 'backLabel' => 'العودة إلى القائمة', 'title', 'fields'])

<div class="space-y-6 pb-8">
    <x-admin.feedback />
    <a href="{{ route($backRoute) }}" class="inline-flex min-h-11 items-center rounded-control font-semibold text-brand underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">{{ $backLabel }}</a>

    <x-ui.card padding="lg">
        <h2 class="mb-5 text-xl font-bold [overflow-wrap:anywhere]"><bdi>{{ $title }}</bdi></h2>
        <dl class="grid gap-5 sm:grid-cols-2">
            @foreach ($fields as $label => $value)
                <div class="min-w-0">
                    <dt class="text-sm text-muted">{{ $label }}</dt>
                    <dd dir="auto" class="whitespace-pre-wrap font-medium [overflow-wrap:anywhere]">{{ filled($value) ? $value : 'غير مسجّل' }}</dd>
                </div>
            @endforeach
        </dl>
    </x-ui.card>

    {{ $slot }}
</div>
