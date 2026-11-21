<x-app-layout>
    <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-8">
            <header class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-black text-slate-900">تعديل الموعد</h1>
                    <p class="mt-1 text-sm text-slate-500">{{ $schedule->adventure->title }}</p>
                </div>
                <a href="{{ route('adventure-schedules.show', $schedule) }}" class="text-sm font-bold text-orange-700 hover:text-orange-900">العودة إلى الموعد</a>
            </header>

            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">
                    <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('adventure-schedules.update', $schedule) }}" method="POST" class="grid gap-4 sm:grid-cols-2">
                @csrf
                @method('PUT')
                <label class="text-sm font-bold text-slate-700 sm:col-span-2">المغامرة
                    <select name="adventure_id" required class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                        @foreach ($adventures as $adventure)
                            <option value="{{ $adventure->id }}" @selected((string) old('adventure_id', $schedule->adventure_id) === (string) $adventure->id)>{{ $adventure->title }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm font-bold text-slate-700 sm:col-span-2">رقم الدليل
                    <input type="number" min="1" name="guide_id" value="{{ old('guide_id', $schedule->guide_id) }}" required class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                </label>
                <label class="text-sm font-bold text-slate-700">يبدأ في
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $schedule->starts_at?->format('Y-m-d\\TH:i')) }}" required class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                </label>
                <label class="text-sm font-bold text-slate-700">ينتهي في
                    <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $schedule->ends_at?->format('Y-m-d\\TH:i')) }}" required class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                </label>
                <label class="text-sm font-bold text-slate-700">السعة
                    <input type="number" min="1" name="capacity" value="{{ old('capacity', $schedule->capacity) }}" required class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                </label>
                <label class="text-sm font-bold text-slate-700">الحالة
                    <select name="status" required class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                        <option value="Active" @selected(old('status', $schedule->status) === 'Active')>Active</option>
                        <option value="Suspended" @selected(old('status', $schedule->status) === 'Suspended')>Suspended</option>
                    </select>
                </label>
                <div class="space-y-4 sm:col-span-2">
                    <h2 class="text-lg font-black text-slate-900">برنامج أيام المغامرة</h2>
                    @forelse ($schedule->adventure->days as $day)
                        <fieldset class="rounded-xl border border-slate-200 p-4">
                            <legend class="px-2 text-sm font-bold text-slate-700">اليوم {{ $day->day_number }}</legend>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label class="text-sm font-semibold text-slate-700">رقم اليوم
                                    <input type="number" min="1" name="days[{{ $day->id }}][day_number]" value="{{ old('days.'.$day->id.'.day_number', $day->day_number) }}" required class="mt-1 w-full rounded-lg border-slate-300 text-sm">
                                </label>
                                <label class="text-sm font-semibold text-slate-700">العنوان
                                    <input type="text" maxlength="255" name="days[{{ $day->id }}][title]" value="{{ old('days.'.$day->id.'.title', $day->title) }}" required class="mt-1 w-full rounded-lg border-slate-300 text-sm">
                                </label>
                                <label class="text-sm font-semibold text-slate-700 sm:col-span-2">الوصف
                                    <textarea name="days[{{ $day->id }}][description]" rows="3" class="mt-1 w-full rounded-lg border-slate-300 text-sm">{{ old('days.'.$day->id.'.description', $day->description) }}</textarea>
                                </label>
                            </div>
                        </fieldset>
                    @empty
                        <p class="rounded-lg bg-slate-50 p-4 text-sm text-slate-500">لا توجد أيام مسجلة لهذه المغامرة حتى الآن.</p>
                    @endforelse
                </div>
                <div class="flex flex-wrap gap-3 sm:col-span-2">
                    <button type="submit" class="rounded-lg bg-orange-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-orange-800">حفظ التعديلات</button>
                    <a href="{{ route('adventure-schedules.index') }}" class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-200">إلغاء</a>
                </div>
            </form>
        </section>
    </main>
</x-app-layout>
