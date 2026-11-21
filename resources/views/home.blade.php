<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>يلا جو | اكتشف الأردن</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans text-slate-800">
    <main>
        <section class="relative isolate flex min-h-[520px] flex-col justify-between overflow-hidden px-5 py-8 text-white sm:px-10 lg:px-[5%]">
            <img class="absolute inset-0 -z-20 h-full w-full object-cover" src="https://images.unsplash.com/photo-1547234935-80c7145ec969?q=85&w=2000" alt="">
            <div class="absolute inset-0 -z-10 bg-gradient-to-t from-slate-950/90 via-slate-900/65 to-slate-900/40"></div>
            <nav class="mx-auto flex w-full max-w-7xl items-center justify-between">
                <a href="{{ route('home') }}" class="text-2xl font-black">يلا جو</a>
                <a href="{{ route('adventuresAll.index') }}" class="rounded-lg border border-white/50 px-4 py-2 text-sm font-bold transition hover:bg-white hover:text-slate-900">جميع المغامرات</a>
            </nav>

            <div class="mx-auto my-12 w-full max-w-4xl text-center">
                <span class="inline-block rounded-full border border-white/30 bg-white/15 px-4 py-2 text-xs font-semibold backdrop-blur">✦ بوابتك لتجارب ومغامرات الطبيعة في الأردن</span>
                <h1 class="mt-5 text-3xl font-black leading-relaxed sm:text-5xl">
                    اكتشف مسارات الأردن<br><span class="text-orange-200">ومجتمعات المغامرة</span>
                </h1>
                <p class="mx-auto mt-4 max-w-2xl text-sm leading-8 text-slate-200 sm:text-base">
                    احجز رحلتك، اكتشف تجارب جديدة، وخوض مغامرات فريدة مع مجموعات محلية ومرشدين محترفين في قلب الطبيعة الأردنية.
                </p>

                <form action="{{ route('adventuresAll.index') }}" method="GET" class="mt-8 grid gap-2 rounded-2xl bg-white p-3 text-right text-slate-800 shadow-xl md:grid-cols-2 lg:grid-cols-5">
                    <label class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600">
                        📍 الوجهة
                        <select name="location" class="mt-1 block w-full border-0 bg-transparent text-sm text-slate-800 focus:ring-0">
                            <option value="">اختر الوجهة</option>
                            <option value="عمان">عمان</option>
                            <option value="وادي رم">وادي رم</option>
                            <option value="البتراء">البتراء</option>
                            <option value="العقبة">العقبة</option>
                            <option value="عجلون">عجلون</option>
                        </select>
                    </label>
                    <label class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600">
                        🧭 نوع النشاط
                        <select name="category" class="mt-1 block w-full border-0 bg-transparent text-sm text-slate-800 focus:ring-0">
                            <option value="">جميع النشاطات</option>
                            <option value="Hiking">المشي والمسارات</option>
                            <option value="Camping">التخييم</option>
                            <option value="Diving">الغوص</option>
                            <option value="adventure">مغامرات</option>
                        </select>
                    </label>
                    <label class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600">
                        📅 تاريخ الرحلة
                        <input type="date" name="date" class="mt-1 block w-full border-0 bg-transparent text-sm text-slate-800 focus:ring-0">
                    </label>
                    <label class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-bold text-slate-600">
                        👥 عدد الأشخاص
                        <select name="people" class="mt-1 block w-full border-0 bg-transparent text-sm text-slate-800 focus:ring-0">
                            <option value="1">شخص واحد</option>
                            <option value="2">شخصان</option>
                            <option value="3">3 أشخاص</option>
                            <option value="4">4 أشخاص</option>
                            <option value="5">5 أشخاص أو أكثر</option>
                        </select>
                    </label>
                    <button type="submit" class="rounded-xl bg-orange-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-orange-800">🔍 ابحث الآن</button>
                </form>

                <div class="mt-5 flex flex-wrap justify-center gap-2 text-xs text-slate-100">
                    <span class="rounded-full bg-white/15 px-3 py-1.5">🥾 مسارات مشي</span>
                    <span class="rounded-full bg-white/15 px-3 py-1.5">🏕️ تخييم ومبيت</span>
                    <span class="rounded-full bg-white/15 px-3 py-1.5">🚵 مغامرات</span>
                    <span class="rounded-full bg-white/15 px-3 py-1.5">🌊 تجارب مائية</span>
                </div>
            </div>

            <div class="mx-auto flex w-full max-w-7xl flex-wrap justify-between gap-3 text-xs font-semibold text-emerald-100">
                <span>♧ تجارب محلية أصيلة</span>
                <span>✦ اكتشف أجمل مناطق الأردن</span>
                <span>♡ مغامرات تناسب الجميع</span>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <span class="inline-block rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">✧ ابدأ رحلتك القادمة</span>
                    <h2 class="mt-2 text-2xl font-black text-slate-900">مغامرات وتجارب لأول مرة</h2>
                    <p class="mt-2 text-sm text-slate-500">تجارب مميزة، ومسارات مختارة بعناية لتعيش أجمل لحظات المغامرة.</p>
                </div>
                <a href="{{ route('adventuresAll.index') }}" class="font-bold text-orange-700 hover:text-orange-900">عرض كل المغامرات ←</a>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @forelse($adventures as $adventure)
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative h-44">
                            <img class="h-full w-full object-cover" src="{{ $adventure->images->isNotEmpty() ? asset('images/' . $adventure->images->first()->image_path) : 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=600' }}" alt="{{ $adventure->title }}">
                            <span class="absolute right-3 top-3 rounded-full bg-emerald-800 px-3 py-1 text-xs font-bold text-white">{{ $adventure->category }}</span>
                            <span class="absolute left-3 top-3 grid h-8 w-8 place-items-center rounded-full bg-white/90 text-slate-600">♡</span>
                        </div>
                        <div class="p-4">
                            <p class="text-xs text-slate-500">📍 {{ $adventure->location }}</p>
                            <h3 class="mt-2 min-h-12 font-extrabold leading-6 text-slate-900">{{ $adventure->title }}</h3>
                            <div class="mt-3 flex justify-between text-xs text-slate-500">
                                <span>⏱ {{ $adventure->duration_hours }} ساعات</span>
                                <span>👥 {{ $adventure->capacity }} مقاعد</span>
                            </div>
                            <div class="mt-4 flex items-end justify-between border-t border-slate-100 pt-3">
                                <a href="{{ route('adventures.profile', $adventure) }}" class="rounded-lg bg-orange-700 px-3 py-2 text-xs font-bold text-white hover:bg-orange-800">احجز الآن</a>
                                <div class="text-left">
                                    <p class="text-[11px] text-slate-500">السعر للشخص</p>
                                    <p class="font-black text-orange-700">{{ number_format($adventure->price, 0) }} د.أ</p>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl bg-white p-10 text-center text-slate-500">لا توجد مغامرات متاحة حالياً.</div>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>
