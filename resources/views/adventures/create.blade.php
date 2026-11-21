<x-app-layout>
    <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-white p-5 shadow-sm sm:p-8">
            <header class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-2xl font-black text-slate-900">إضافة مغامرة</h1>
                <a class="text-sm font-bold text-orange-700 hover:text-orange-900"
                    href="{{ route('adventures.index') }}">العودة إلى المغامرات</a>
            </header>

            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">
                    <ul class="list-inside list-disc">@foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('adventures.store') }}" method="POST">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="text-sm font-bold text-slate-700">اسم المغامرة<input
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700"
                            name="title" value="{{ old('title') }}" required></label>
                    <label class="text-sm font-bold text-slate-700 sm:col-span-2">الوصف<textarea
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700"
                            name="description" rows="4" required>{{ old('description') }}</textarea></label>
                    <label class="text-sm font-bold text-slate-700">الصعوبة
                        <select
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700"
                            name="difficulty_level" required>
                            @foreach (['Easy' => 'سهل', 'Medium' => 'متوسط', 'Hard' => 'صعب'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('difficulty_level') === $value)>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-bold text-slate-700">السعة<input
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700"
                            type="number" name="capacity" value="{{ old('capacity') }}" required></label>
                    <label class="text-sm font-bold text-slate-700">التصنيف
                        <select
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700"
                            name="category" required>
                            @foreach (['Hiking', 'Diving', 'Camping', 'Cyciling', 'Zaitoon day out', 'kayaking', 'climbing', 'Sky Diving', 'Blimping', 'Toursim'] as $category)
                                <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-bold text-slate-700">الموقع<input
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700"
                            name="location" value="{{ old('location') }}" required></label>
                    <label class="text-sm font-bold text-slate-700">المدة بالساعات<input
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700"
                            type="number" step="0.01" name="duration_hours" value="{{ old('duration_hours') }}"
                            required></label>
                    <label class="text-sm font-bold text-slate-700">السعر<input
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700"
                            type="number" step="0.01" name="price" value="{{ old('price') }}" required></label>

                    <label class="text-sm font-bold text-slate-700 sm:col-span-2">المتطلبات<textarea
                            class="mt-1 w-full rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700"
                            name="requirements" rows="3" required>{{ old('requirements') }}</textarea></label>
                </div>
                <div class="mt-6 flex flex-wrap gap-3">
                    <button
                        class="rounded-lg bg-orange-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-orange-800"
                        type="submit">حفظ المغامرة</button>
                    <a class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-200"
                        href="{{ route('adventures.index') }}">إلغاء</a>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>
