@props(['indexRoute', 'records', 'filters', 'searchLabel', 'selects', 'headings', 'searchType' => 'search', 'createRoute' => null, 'blockedCreate' => null])

<div class="space-y-6 pb-8">
    <x-admin.feedback />
    @if ($createRoute)
        <x-admin.action-link :href="route($createRoute)">إضافة سجل</x-admin.action-link>
    @elseif ($blockedCreate)
        <p class="text-sm text-muted">{{ $blockedCreate }}</p>
    @endif
    <x-ui.card padding="lg">
        <form method="GET" action="{{ route($indexRoute) }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <x-ui.input name="search" :label="$searchLabel" :type="$searchType"
                :value="$filters['search'] ?? null" min="1" maxlength="100" />

            @foreach ($selects as $field => [$label, $options])
                <div class="space-y-2">
                    <label for="admin-filter-{{ $field }}" class="block text-sm font-semibold">{{ $label }}</label>
                    <select id="admin-filter-{{ $field }}" name="{{ $field }}"
                        @error($field) aria-invalid="true" aria-describedby="admin-filter-{{ $field }}-error" @enderror
                        class="block w-full rounded-control border border-outline bg-surface py-3 ps-4 pe-10 text-sm rtl:bg-position-[left_0.5rem_center] focus:border-brand focus:ring-brand">
                        <option value="">الكل</option>
                        @foreach ($options as $value => $option)
                            <option value="{{ $value }}" @selected((string) ($filters[$field] ?? '') === (string) $value)>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error($field)
                        <p id="admin-filter-{{ $field }}-error" role="alert" class="text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div class="col-span-full flex flex-wrap items-center gap-4">
                <x-ui.button type="submit">تطبيق الفلاتر</x-ui.button>
                <a href="{{ route($indexRoute) }}" class="rounded-control text-sm font-semibold text-brand underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-brand">مسح الفلاتر</a>
            </div>
        </form>
    </x-ui.card>

    <x-ui.card padding="none">
        @if ($records->isEmpty())
            <p class="p-6 text-muted">لا توجد سجلات مطابقة في هذه الصفحة.</p>
        @else
            <div class="overflow-x-auto rounded-card">
                <table class="w-full text-sm [&_td]:px-4 [&_td]:py-3">
                    <caption class="sr-only">نتائج القائمة الإدارية</caption>
                    <thead class="bg-canvas text-ink">
                        <tr>
                            @foreach ($headings as $heading)
                                <th scope="col" class="whitespace-nowrap px-4 py-3 text-start">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline/40">{{ $slot }}</tbody>
                </table>
            </div>
        @endif
    </x-ui.card>

    {{ $records->links() }}
</div>
