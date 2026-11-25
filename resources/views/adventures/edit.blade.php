<x-app-layout>
    <main class="mx-auto max-w-5xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-8">
            <header class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h1 class="text-2xl font-black text-slate-900">تعديل المغامرة</h1>
                <a class="text-sm font-bold text-brand hover:text-brand-dark" href="{{ route('adventures.index') }}">العودة إلى المغامرات</a>
            </header>
            @if (session('success'))
                <div class="mb-5 rounded-lg bg-emerald-100 p-3 text-sm text-emerald-900" role="status">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">
                    <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('adventures.update', $adventure) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="text-sm font-bold text-slate-700">اسم المغامرة<input class="mt-1 w-full rounded-lg border-slate-300 text-sm" id="title" name="title" value="{{ old('title', $adventure->title) }}" required></label>
                    <label class="text-sm font-bold text-slate-700 sm:col-span-2">الوصف<textarea class="mt-1 w-full rounded-lg border-slate-300 text-sm" id="description" name="description" rows="4" required>{{ old('description', $adventure->description) }}</textarea></label>
                    <label class="text-sm font-bold text-slate-700">الصعوبة
                        <select class="mt-1 w-full rounded-lg border-slate-300 text-sm" id="difficulty_level" name="difficulty_level" required>
                            @foreach (['Easy', 'Medium', 'Hard'] as $value)
                                <option value="{{ $value }}" @selected(old('difficulty_level', $adventure->difficulty_level) === $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-bold text-slate-700">السعة<input class="mt-1 w-full rounded-lg border-slate-300 text-sm" id="capacity" type="number" name="capacity" value="{{ old('capacity', $adventure->capacity) }}" required></label>
                    <label class="text-sm font-bold text-slate-700">التصنيف
                        <select class="mt-1 w-full rounded-lg border-slate-300 text-sm" id="category" name="category" required>
                            @foreach (['Hiking', 'Diving', 'Camping', 'Cyciling', 'Zaitoon day out', 'kayaking', 'climbing', 'Sky Diving', 'Blimping', 'Toursim'] as $value)
                                <option value="{{ $value }}" @selected(old('category', $adventure->category) === $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-bold text-slate-700">الموقع ونقطة التجمع<input class="mt-1 w-full rounded-lg border-slate-300 text-sm" id="location" name="location" value="{{ old('location', $adventure->location) }}" required></label>
                    <label class="text-sm font-bold text-slate-700">المدة بالساعات<input class="mt-1 w-full rounded-lg border-slate-300 text-sm" id="duration_hours" type="number" step="0.01" name="duration_hours" value="{{ old('duration_hours', $adventure->duration_hours) }}" required></label>
                    <label class="text-sm font-bold text-slate-700">السعر<input class="mt-1 w-full rounded-lg border-slate-300 text-sm" id="price" type="number" step="0.01" name="price" value="{{ old('price', $adventure->price) }}" required></label>
                    <label class="text-sm font-bold text-slate-700">الحالة
                        <select class="mt-1 w-full rounded-lg border-slate-300 text-sm" id="status" name="status" required>
                            @foreach (['Active', 'Suspended'] as $value)
                                <option value="{{ $value }}" @selected(old('status', $adventure->status) === $value)>{{ $value }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-bold text-slate-700 sm:col-span-2">المتطلبات<textarea class="mt-1 w-full rounded-lg border-slate-300 text-sm" id="requirements" name="requirements" rows="3" required>{{ old('requirements', $adventure->requirements) }}</textarea></label>
                </div>
                <div class="mt-6 flex flex-wrap gap-3">
                    <button class="rounded-lg bg-brand px-5 py-2.5 text-sm font-bold text-white hover:bg-brand-dark" type="submit">حفظ التعديلات</button>
                    <a class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-200" href="{{ route('adventures.index') }}">إلغاء</a>
                </div>
            </form>
        </section>

        <section class="rounded-2xl bg-white p-5 shadow-sm sm:p-8">
            <h2 class="mb-4 text-xl font-black text-slate-900">صور المغامرة</h2>
            <div class="mb-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($adventure->images as $image)
                    <form action="{{ route('images.update', $image) }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3 rounded-xl border border-slate-200 p-3">
                        @csrf @method('PUT')
                        <input type="hidden" name="_form_type" value="edit">
                        <input type="hidden" name="_adventure_id" value="{{ $adventure->id }}">
                        <img src="{{ $image->url }}" alt="{{ $adventure->title }}" class="h-16 w-16 rounded-lg object-cover">
                        <label class="min-w-0 flex-1 text-xs font-bold">استبدال الصورة<input class="mt-1 block w-full text-xs" type="file" name="image_path" accept="image/jpeg,image/png,image/webp" required></label>
                        <button class="rounded-lg bg-brand px-3 py-2 text-xs font-bold text-white hover:bg-brand-dark" type="submit">تحديث</button>
                    </form>
                @empty
                    <p class="text-sm text-slate-500">لا توجد صور لهذه المغامرة بعد.</p>
                @endforelse
            </div>
            <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-3 sm:grid-cols-[1fr_auto] sm:items-end">
                @csrf
                <input type="hidden" name="adventure_id" value="{{ $adventure->id }}">
                <input type="hidden" name="_form_type" value="edit">
                <input type="hidden" name="_adventure_id" value="{{ $adventure->id }}">
                <label class="text-sm font-bold">إضافة صور<input class="mt-1 block w-full rounded-lg border border-slate-300 p-2 text-sm" id="image_files" type="file" name="image_files[]" accept="image/jpeg,image/png,image/webp" multiple required></label>
                <button class="rounded-lg bg-brand px-4 py-2.5 text-sm font-bold text-white hover:bg-brand-dark" type="submit">رفع الصور</button>
            </form>
        </section>
    </main>
</x-app-layout>
