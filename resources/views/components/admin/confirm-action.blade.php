@props(['action', 'method', 'label', 'warning', 'confirmation' => false, 'requirePassword' => false])

<details class="rounded-control border border-danger/30 p-3">
    <summary class="cursor-pointer rounded-control font-semibold text-danger focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-danger">{{ $label }}</summary>
    <form action="{{ $action }}" method="POST" class="mt-3 space-y-3">
        @csrf
        @method($method)
        @if ($confirmation)
            <input type="hidden" name="confirm_delete" value="1">
        @endif
        <p class="text-sm text-ink">{{ $warning }}</p>
        <div class="flex flex-wrap items-center gap-3">
            <x-ui.button type="submit" variant="outline">تأكيد {{ $label }}</x-ui.button>
            <x-admin.action-link :href="url()->current()">إلغاء</x-admin.action-link>
        </div>
    </form>
</details>
