<x-app-layout>
    <main class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <header class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-900">مواعيد المغامرات</h1>
                <p class="mt-1 text-sm text-slate-500">استعرض المواعيد أو صفّها حسب المغامرة.</p>
            </div>
            <a href="{{ route('adventure-schedules.create') }}" class="rounded-lg bg-orange-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-orange-800">＋ إضافة موعد</a>
            @if (session('success'))
                <div class="w-full rounded-lg bg-emerald-100 px-4 py-3 text-sm font-semibold text-emerald-900" role="status">{{ session('success') }}</div>
            @endif
        </header>

        <form action="{{ route('adventure-schedules.index') }}" method="GET" class="flex flex-col gap-3 rounded-2xl bg-white p-5 shadow-sm sm:flex-row sm:items-end">
            <label for="adventure_id" class="flex-1 text-sm font-bold text-slate-700">
                تصفية حسب المغامرة
                <select id="adventure_id" name="adventure_id" class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                    <option value="">كل المغامرات</option>
                    @foreach ($adventures as $adventure)
                        <option value="{{ $adventure->id }}" @selected((string) request('adventure_id') === (string) $adventure->id)>{{ $adventure->title }}</option>
                    @endforeach
                </select>
            </label>
            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-emerald-800 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-900">تصفية</button>
                <a href="{{ route('adventure-schedules.index') }}" class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-200">مسح</a>
            </div>
        </form>

        <div class="overflow-x-auto rounded-2xl bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-right">
                <thead class="bg-slate-50 text-xs font-bold text-slate-600">
                    <tr>
                        <th class="px-5 py-4">المغامرة</th>
                        <th class="px-5 py-4">الدليل</th>
                        <th class="px-5 py-4">البداية</th>
                        <th class="px-5 py-4">النهاية</th>
                        <th class="px-5 py-4">السعة</th>
                        <th class="px-5 py-4">الحالة</th>
                        <th class="px-5 py-4">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($schedules as $schedule)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-900">{{ $schedule->adventure->title }}</td>
                            <td class="whitespace-nowrap px-5 py-4">{{ $schedule->guide->activityOrganizer->user->name }}</td>
                            <td class="whitespace-nowrap px-5 py-4">{{ $schedule->starts_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-5 py-4">{{ $schedule->ends_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td class="whitespace-nowrap px-5 py-4">{{ $schedule->capacity }}</td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $schedule->status === 'Active' ? 'bg-emerald-100 text-emerald-900' : 'bg-amber-100 text-amber-900' }}">{{ $schedule->status }}</span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('adventure-schedules.show', $schedule) }}" class="rounded-md bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200">عرض</a>
                                    <a href="{{ route('adventure-schedules.edit', $schedule) }}" class="rounded-md bg-orange-100 px-3 py-2 text-xs font-bold text-orange-800 hover:bg-orange-200">تعديل</a>
                                    <form action="{{ route('adventure-schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذا الموعد؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md bg-red-100 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-200">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-slate-500">لا توجد مواعيد لهذه المغامرة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $schedules->links() }}</div>
    </main>
</x-app-layout>
