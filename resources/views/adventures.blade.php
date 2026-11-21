<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>جميع المغامرات | يلا جو</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans text-slate-800">
    <nav class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="text-2xl font-black text-emerald-900">يلا جو</a>
            <a href="{{ route('home') }}" class="text-sm font-bold text-slate-700 hover:text-orange-700">العودة للرئيسية ←</a>
        </div>
    </nav>

    <header class="bg-emerald-900 px-4 py-10 text-white sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <h1 class="text-3xl font-black">اكتشف جميع المغامرات</h1>
            <p class="mt-2 text-sm text-emerald-100">استكشف أجمل التجارب والأنشطة السياحية في الأردن واختر مغامرتك القادمة.</p>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <form action="{{ route('adventuresAll.index') }}" method="GET" class="mb-8 grid gap-3 rounded-2xl bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-4">
            <input type="text" name="search" placeholder="ابحث عن مغامرة..." value="{{ request('search') }}" class="rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700">
            <select name="location" class="rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                <option value="">جميع المواقع</option>
                @foreach($locations as $location)
                    <option value="{{ $location }}" @selected(request('location') == $location)>{{ $location }}</option>
                @endforeach
            </select>
            <select name="category" class="rounded-lg border-slate-300 text-sm focus:border-emerald-700 focus:ring-emerald-700">
                <option value="">جميع التصنيفات</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" @selected(request('category') == $category)>{{ $category }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-orange-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-orange-800">بحث</button>
        </form>

        <div class="mb-5 flex items-center justify-between gap-3">
            <h2 class="text-xl font-extrabold text-slate-900">المغامرات المتاحة</h2>
            <span class="text-sm text-slate-500">{{ $adventures->total() }} مغامرة</span>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($adventures as $adventure)
                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-lg">
                    <div class="relative h-52">
                        <img class="h-full w-full object-cover" src="{{ $adventure->images->isNotEmpty() ? asset('images/' . $adventure->images->first()->image_path) : 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=600' }}" alt="{{ $adventure->title }}">
                        <span class="absolute right-3 top-3 rounded-full bg-emerald-900 px-3 py-1.5 text-xs font-bold text-white">{{ $adventure->category }}</span>
                    </div>
                    <div class="p-5">
                        <p class="text-xs text-slate-500">📍 {{ $adventure->location }}</p>
                        <h3 class="mt-2 text-lg font-extrabold text-slate-900">{{ $adventure->title }}</h3>
                        <div class="mt-4 flex justify-between border-b border-slate-100 pb-4 text-xs text-slate-500">
                            <span>⏱ {{ $adventure->duration_hours }} ساعات</span>
                            <span>👥 {{ $adventure->capacity }} مقاعد</span>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <a href="{{ route('adventures.profile', $adventure) }}" class="rounded-lg bg-orange-700 px-4 py-2 text-xs font-bold text-white hover:bg-orange-800">عرض التفاصيل</a>
                            <span class="text-lg font-black text-orange-700">{{ number_format($adventure->price, 2) }} د.أ</span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl bg-white px-5 py-12 text-center text-slate-500">
                    <h3 class="font-bold text-slate-800">لا توجد مغامرات حالياً</h3>
                    <p class="mt-2 text-sm">جرّب تغيير خيارات البحث أو التصنيف.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $adventures->links() }}</div>
    </main>

    <footer class="mt-8 bg-emerald-900 px-4 py-6 text-center text-sm text-white">© {{ date('Y') }} يلا جو - اكتشف الأردن بطريقة مختلفة</footer>
</body>
</html>
