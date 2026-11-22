@props(['action', 'cancel', 'method' => 'POST'])

<x-ui.card padding="lg" class="mx-auto max-w-3xl">
    @if ($errors->any())
        <div role="alert" class="mb-5 rounded-control border border-danger/30 bg-danger/5 p-4 text-danger">
            <p class="font-semibold">راجع بيانات النموذج.</p>
            <ul class="list-inside list-disc text-sm">
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ $action }}" method="POST" class="space-y-5">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        {{ $slot }}

        <div class="flex flex-wrap items-center gap-4">
            <x-ui.button type="submit">حفظ</x-ui.button>
            <a href="{{ $cancel }}" class="rounded-control font-semibold text-brand underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">إلغاء</a>
        </div>
    </form>
</x-ui.card>
