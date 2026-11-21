<x-app-layout>
    <main class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-8">
            @if (session('success'))
                <div class="mb-5 rounded-lg bg-emerald-100 p-3 text-sm text-emerald-900" role="status">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">
                    <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            <nav class="mb-6 flex flex-wrap gap-4 text-sm font-bold">
                <a href="{{ route('adventure-schedules.index') }}" class="text-orange-700 hover:text-orange-900">العودة إلى المواعيد</a>
                <a href="{{ route('adventure-schedules.edit', $schedule) }}" class="text-orange-700 hover:text-orange-900">تعديل الموعد</a>
            </nav>

            <h1 class="text-2xl font-black text-slate-900">{{ $schedule->adventure->title }}</h1>
            <p class="mt-1 text-sm text-slate-500">تفاصيل الموعد وبرنامج أيام المغامرة.</p>

            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <p class="rounded-lg bg-slate-50 p-4"><strong>رقم المغامرة:</strong> {{ $schedule->adventure_id }}</p>
                <p class="rounded-lg bg-slate-50 p-4"><strong>رقم الدليل:</strong> {{ $schedule->guide_id }}</p>
                <p class="rounded-lg bg-slate-50 p-4"><strong>السعة:</strong> {{ $schedule->capacity }}</p>
                <p class="rounded-lg bg-slate-50 p-4"><strong>يبدأ في:</strong> {{ $schedule->starts_at?->format('Y-m-d H:i') ?? '—' }}</p>
                <p class="rounded-lg bg-slate-50 p-4"><strong>ينتهي في:</strong> {{ $schedule->ends_at?->format('Y-m-d H:i') ?? '—' }}</p>
                <p class="rounded-lg bg-slate-50 p-4"><strong>الحالة:</strong> {{ $schedule->status }}</p>
            </div>
        </section>

        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-8">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-xl font-black text-slate-900">برنامج أيام المغامرة</h2>
                <a href="{{ route('adventure-schedules.create', ['phase' => 'days', 'schedule_id' => $schedule->id]) }}" class="rounded-lg bg-orange-700 px-4 py-2 text-sm font-bold text-white hover:bg-orange-800">إضافة أيام</a>
            </div>

            @if ($schedule->adventure->days->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-[900px] divide-y divide-slate-200 text-right">
                        <thead class="bg-slate-50 text-xs font-bold text-slate-600">
                            <tr><th class="px-4 py-3">اليوم</th><th class="px-4 py-3">العنوان</th><th class="px-4 py-3">الوصف</th><th class="px-4 py-3">حفظ</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach ($schedule->adventure->days as $day)
                                @php($dayFormId = 'day-form-' . $day->id)
                                <tr>
                                    <td class="px-3 py-3">
                                        <input form="{{ $dayFormId }}" type="number" min="1" name="day_number" value="{{ $day->day_number }}" required class="w-24 rounded-lg border-slate-300 text-sm">
                                    </td>
                                    <td class="px-3 py-3">
                                        <input form="{{ $dayFormId }}" type="text" maxlength="255" name="title" value="{{ $day->title }}" required class="w-48 rounded-lg border-slate-300 text-sm">
                                    </td>
                                    <td class="px-3 py-3">
                                        <textarea form="{{ $dayFormId }}" name="description" rows="2" class="w-72 rounded-lg border-slate-300 text-sm">{{ $day->description }}</textarea>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3">
                                        <form id="{{ $dayFormId }}" action="{{ route('adventure-schedules.days.update', [$schedule, $day]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="rounded-lg bg-emerald-700 px-3 py-2 text-xs font-bold text-white hover:bg-emerald-800">حفظ التعديلات</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="rounded-lg bg-slate-50 p-5 text-sm text-slate-500">لم تتم إضافة أيام لهذه المغامرة بعد.</p>
            @endif
        </section>
    </main>
</x-app-layout>
