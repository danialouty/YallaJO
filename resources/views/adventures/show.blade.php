<x-app-layout>
    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <article class="rounded-2xl bg-white p-5 shadow-sm sm:p-8">
            <nav class="mb-6 flex flex-wrap gap-4 text-sm font-bold">
                <a class="text-brand hover:text-brand-dark" href="{{ route('adventures.index') }}">العودة إلى المغامرات</a>
                <a class="text-brand hover:text-brand-dark" href="{{ route('adventures.edit', $adventure) }}">تعديل المغامرة</a>
            </nav>
            <h1 class="text-3xl font-black text-slate-900">{{ $adventure->title }}</h1>
            <p class="mt-3 leading-7 text-slate-600">{{ $adventure->description }}</p>
            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <p class="rounded-lg bg-slate-50 p-3"><strong>رقم المنظم:</strong> {{ $adventure->activity_organizer_id }}</p>
                <p class="rounded-lg bg-slate-50 p-3"><strong>الصعوبة:</strong> {{ $adventure->difficulty_level }}</p>
                <p class="rounded-lg bg-slate-50 p-3"><strong>السعة:</strong> {{ $adventure->capacity }}</p>
                <p class="rounded-lg bg-slate-50 p-3"><strong>التصنيف:</strong> {{ $adventure->category }}</p>
                <p class="rounded-lg bg-slate-50 p-3"><strong>الموقع:</strong> {{ $adventure->location }}</p>
                <p class="rounded-lg bg-slate-50 p-3"><strong>المدة:</strong> {{ $adventure->duration_hours }} ساعة</p>
                <p class="rounded-lg bg-slate-50 p-3"><strong>السعر:</strong> {{ $adventure->price }}</p>
                <p class="rounded-lg bg-slate-50 p-3"><strong>الحالة:</strong> {{ $adventure->status }}</p>
                <p class="rounded-lg bg-slate-50 p-3"><strong>المتطلبات:</strong> {{ $adventure->requirements }}</p>
            </div>

            <section class="mt-8">
                <h2 class="mb-4 text-xl font-black">الصور</h2>
                @if ($adventure->images->isNotEmpty())
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($adventure->images as $image)
                            @if ($image->image_path)
                                <img class="h-48 w-full rounded-xl object-cover" src="{{ asset('images/' . $image->image_path) }}" alt="{{ $adventure->title }}">
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500">لا توجد صور مسجلة.</p>
                @endif
            </section>
        </article>
    </main>
</x-app-layout>
