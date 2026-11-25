<x-app-layout>
    <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-8">
            @if (!$schedule)
                <div class="mb-6">
                    <p class="text-xs font-bold uppercase tracking-wide text-emerald-800">المرحلة 1 من 2</p>
                    <h1 class="mt-1 text-2xl font-black text-slate-900">بيانات الموعد</h1>
                    <p class="mt-2 text-sm text-slate-500">أدخل بيانات الموعد أولاً، ثم أضف برنامج أيام المغامرة المرتبطة
                        بها.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">
                        <ul class="list-inside list-disc">@foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('adventure-schedules.store') }}" method="POST" class="grid gap-4 sm:grid-cols-2">
                    @csrf
                    <label class="text-sm font-bold text-slate-700 sm:col-span-2">
                        المغامرة
                        <select name="adventure_id" required
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-brand focus:ring-brand">
                            <option value="">اختر المغامرة</option>
                            @foreach ($adventures as $adventure)
                                <option value="{{ $adventure->id }}" @selected((string) old('adventure_id') === (string) $adventure->id)>{{ $adventure->title }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-bold text-slate-700">الدليل
                        <select
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-brand focus:ring-brand"
                            id="guide_id" name="guide_id" required>
                            <option value="">اختر الدليل</option>
                            @foreach ($guides as $guide)
                                <option value="{{ $guide->id }}" {{ old('guide_id') == $guide->id ? 'selected' : '' }}>
                                    {{ $guide->activityOrganizer->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-bold text-slate-700">يبدأ في
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" required
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-brand focus:ring-brand">
                    </label>
                    <label class="text-sm font-bold text-slate-700">ينتهي في
                        <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" required
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-brand focus:ring-brand">
                    </label>
                    <label class="text-sm font-bold text-slate-700">السعة
                        <input type="number" name="capacity" value="{{ old('capacity') }}" min="1" required
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-brand focus:ring-brand">
                    </label>

                    <div class="flex flex-wrap gap-3 sm:col-span-2">
                        <button type="submit"
                            class="rounded-lg bg-brand px-5 py-2.5 text-sm font-bold text-white hover:bg-brand-dark">حفظ
                            ومتابعة</button>
                        <a href="{{ route('adventure-schedules.index') }}"
                            class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-200">إلغاء</a>
                    </div>
                </form>
            @else
            <div class="mb-6">
                <p class="text-xs font-bold uppercase tracking-wide text-emerald-800">المرحلة 2 من 2</p>
                <h1 class="mt-1 text-2xl font-black text-slate-900">برنامج أيام المغامرة</h1>
                <p class="mt-2 text-sm text-slate-500">الموعد مرتبط بمغامرة <strong
                        class="text-slate-800">{{ $schedule->adventure->title }}</strong>. تم إنشاء {{ $dayCount }}
                    يومًا تلقائيًا من مدة الموعد؛ أدخل تفاصيل كل يوم.</p>
            </div>

            @if ($schedule->adventure->days->isNotEmpty())
                <div class="mb-6 overflow-x-auto rounded-xl border border-slate-200">
                    <h2 class="bg-slate-50 px-4 py-3 text-sm font-bold">الأيام المسجلة مسبقاً</h2>
                    <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                        <thead class="text-xs text-slate-500">
                            <tr>
                                <th class="px-4 py-2">اليوم</th>
                                <th class="px-4 py-2">العنوان</th>
                                <th class="px-4 py-2">الوصف</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($schedule->adventure->days as $day)
                                <tr>
                                    <td class="px-4 py-3">{{ $day->day_number }}</td>
                                    <td class="px-4 py-3 font-semibold">{{ $day->title }}</td>
                                    <td class="px-4 py-3 text-slate-600">{{ $day->description ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">
                    <ul class="list-inside list-disc">@foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('adventure-schedules.store') }}" method="POST">
                <div class="mb-5">
                    <button type="button" id="generate-itinerary"
                        data-endpoint="{{ route('adventure-schedules.days.generate', $schedule) }}"
                        class="rounded-lg bg-emerald-100 px-5 py-2.5 text-sm font-bold text-emerald-900 hover:bg-emerald-200">✦ أعد ملء الأيام بالذكاء الاصطناعي</button>
                    <p id="ai-status" class="mt-3 text-sm text-slate-600" role="status" aria-live="polite"></p>
                </div>
                @csrf
                <input type="hidden" name="phase" value="days">
                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                @php($oldDays = old('days', []))
                <div id="day-rows" class="space-y-4">
                    @for ($index = 0; $index < $dayCount; $index++)
                    @php($day = $oldDays[$index] ?? $schedule->adventure->days->firstWhere('day_number', $index + 1))
                    <fieldset class="day-row rounded-xl border border-slate-200 p-4">
                        <legend class="px-2 text-sm font-bold text-slate-700">اليوم {{ $index + 1 }}</legend>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <input type="hidden" name="days[{{ $index }}][day_number]" value="{{ $index + 1 }}">
                            <label class="text-sm font-semibold">العنوان
                                <input type="text" name="days[{{ $index }}][title]"
                                    value="{{ is_array($day) ? ($day['title'] ?? '') : ($day?->title ?? '') }}"
                                    maxlength="255" required data-ai-day-title class="mt-1 w-full rounded-lg border-slate-300 text-sm">
                            </label>
                            <label class="text-sm font-semibold sm:col-span-2">الوصف
                                <textarea name="days[{{ $index }}][description]" rows="2"
                                    data-ai-day-description class="mt-1 w-full rounded-lg border-slate-300 text-sm">{{ is_array($day) ? ($day['description'] ?? '') : ($day?->description ?? '') }}</textarea>
                            </label>
                        </div>
                    </fieldset>
                    @endfor
                </div>

                <div class="mt-5 flex flex-wrap gap-3">
                    <button type="submit"
                        class="rounded-lg bg-brand px-5 py-2.5 text-sm font-bold text-white hover:bg-brand-dark">حفظ
                        الأيام</button>
                    <a href="{{ route('adventure-schedules.show', $schedule) }}"
                        class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-200">إنهاء
                        بدون إضافة أيام</a>
                </div>
            </form>
            @endif
        </section>
    </main>

    @if ($schedule)
        <script>
            const generateButton = document.getElementById('generate-itinerary');
            const aiStatus = document.getElementById('ai-status');

            generateButton.addEventListener('click', async () => {
                generateButton.disabled = true;
                aiStatus.textContent = 'يتم إعداد اقتراح البرنامج بالذكاء الاصطناعي...';

                try {
                    const response = await fetch(generateButton.dataset.endpoint, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        },
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || 'تعذر توليد البرنامج.');

                    const rows = [...document.querySelectorAll('#day-rows .day-row')];
                    if (!Array.isArray(result.days) || result.days.length !== rows.length) {
                        throw new Error('تعذر إعداد عدد الأيام المتوقع.');
                    }

                    rows.forEach((row, index) => {
                        row.querySelector('[data-ai-day-title]').value = result.days[index].title;
                        row.querySelector('[data-ai-day-description]').value = result.days[index].description;
                    });
                    aiStatus.textContent = 'تم ملء الأيام بالاقتراح. راجع التفاصيل قبل حفظها.';
                } catch (error) {
                    aiStatus.textContent = error.message;
                } finally {
                    generateButton.disabled = false;
                }
            });
        </script>
    @endif

</x-app-layout>
