@extends('layouts.site')

@section('title', 'اكتشف مغامرات الأردن | YallaJO')
@section('meta_description', 'احجز رحلات هايكنج وتخييم وغوص وتجارب ريفية في مختلف مناطق الأردن.')

@section('content')
    <section class="landing-hero relative isolate min-h-[620px] overflow-hidden bg-ink text-white" aria-labelledby="hero-title">
        <div class="landing-topography" aria-hidden="true"></div>
        <img
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCyNMFQwtfryBGvkUx_KyP8NtN2rqpr7hbzgwob3Q8PXMjKxlX74mrKWxazvPmWr4zg05dDaeCdcKEYjY93G-9-C_hujFUJOY0ij5yBmb_M1QF35TrgMZltoYsYV-2zZoZrgg7ONHVkvUK4OGLyWtNHlVCA8xSqDLu4KKagoJWQ1k6KAzxKRFwfOVYktOeXJnpGfOhWMhlO3CP_ddtg54Exy9U8EVV43ZWTCivGr4s"
            alt=""
            aria-hidden="true"
            fetchpriority="high"
            class="absolute inset-0 -z-20 h-full w-full object-cover">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-ink/85 via-ink/60 to-ink/95"></div>

        <div class="mx-auto flex min-h-[620px] max-w-[1360px] flex-col items-center justify-center px-4 py-20 text-center sm:px-6 lg:px-12">
            <p class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/15 px-5 py-2 text-sm text-white backdrop-blur-sm">
                <span class="text-orange-200" aria-hidden="true">✦</span>
                بوابتك لتجارب ومغامرات الطبيعة في الأردن
            </p>

            <h1 id="hero-title" class="max-w-4xl font-display text-4xl font-extrabold leading-tight tracking-tight drop-shadow-lg md:text-6xl lg:text-7xl">
                اكتشف مسارات الأردن
                <span class="mt-2 block text-orange-200">ومجتمعات المغامرة</span>
            </h1>

            <p class="mt-7 max-w-2xl text-base leading-8 text-white/90 md:text-lg">
                احجز رحلات هايكنج، تخييم، غطس وتجارب ريفية،
                واكتشف مغامرات جديدة مع مرشدين ومجموعات محلية موثوقة.
            </p>

            <form action="{{ route('adventuresAll.index') }}" method="GET"
                  class="mt-10 grid w-full max-w-5xl gap-3 rounded-card bg-surface p-3 text-right text-ink shadow-2xl md:grid-cols-2 lg:grid-cols-4">
                <label class="flex min-h-20 items-center gap-3 rounded-xl bg-[#eef1ff] px-4 py-3">
                    <span aria-hidden="true" class="text-xl text-brand">⌖</span>
                    <span class="min-w-0 flex-1">
                        <span class="mb-1 block text-xs font-bold text-muted">الوجهة والمكان</span>
                        <input name="location" type="text" placeholder="الموجب، رم، عجلون"
                               class="w-full border-0 bg-transparent p-0 text-sm focus:outline-none focus:ring-0">
                    </span>
                </label>

                <label class="flex min-h-20 items-center gap-3 rounded-xl bg-[#eef1ff] px-4 py-3">
                    <span aria-hidden="true" class="text-xl text-forest">◉</span>
                    <span class="min-w-0 flex-1">
                        <span class="mb-1 block text-xs font-bold text-muted">نوع التجربة</span>
                        <select name="category" class="w-full border-0 bg-transparent p-0 text-sm focus:outline-none focus:ring-0">
                            <option value="">كل الفعاليات</option>
                            <option value="Hiking">هايكنج ومسارات</option>
                            <option value="Camping">تخييم ورصد نجوم</option>
                            <option value="Diving">غوص ورياضات بحرية</option>
                            <option value="Zaitoon day out">تجارب ريفية وحصاد</option>
                        </select>
                    </span>
                </label>

                <label class="flex min-h-20 items-center gap-3 rounded-xl bg-[#eef1ff] px-4 py-3">
                    <span aria-hidden="true" class="text-xl text-accent">▣</span>
                    <span class="min-w-0 flex-1">
                        <span class="mb-1 block text-xs font-bold text-muted">الموعد</span>
                        <input name="date" type="date" class="w-full border-0 bg-transparent p-0 text-sm focus:outline-none focus:ring-0">
                    </span>
                </label>

                <button type="submit" class="min-h-20 rounded-xl bg-brand px-6 text-sm font-bold text-white transition hover:bg-brand-dark">
                    ابحث عن مغامرة
                </button>
            </form>

            <div class="mt-5 flex flex-wrap items-center justify-center gap-3 text-sm text-white/90" aria-label="أنشطة رائجة">
                <span class="font-bold text-orange-200">رائج الآن:</span>
                <span class="rounded-full border border-white/25 bg-white/10 px-3 py-1.5">مسار وادي الموجب</span>
                <span class="rounded-full border border-white/25 bg-white/10 px-3 py-1.5">تخييم وادي رم</span>
                <span class="rounded-full border border-white/25 bg-white/10 px-3 py-1.5">غوص العقبة</span>
            </div>

            <div class="mt-8 grid w-full max-w-4xl grid-cols-3 gap-3">
                <div class="landing-stat">
                    <strong>+50</strong>
                    <span>مسار ومغامرة</span>
                </div>
                <div class="landing-stat">
                    <strong>12</strong>
                    <span>محافظة أردنية</span>
                </div>
                <div class="landing-stat">
                    <strong>24/7</strong>
                    <span>دعم وحجز</span>
                </div>
            </div>
        </div>
    </section>

    @include('partials.home.featured-adventures')

    <section class="bg-[#f5f7f4] px-4 py-16 sm:px-6 lg:px-8" aria-labelledby="organizer-join-title">
        <div class="mx-auto flex max-w-6xl flex-col items-start justify-between gap-6 rounded-3xl bg-forest px-6 py-8 text-white sm:px-10 sm:py-10 md:flex-row md:items-center">
            <div class="max-w-2xl">
                <p class="text-sm font-bold text-orange-200">لأصحاب التجارب المحلية</p>
                <h2 id="organizer-join-title" class="mt-2 text-2xl font-extrabold sm:text-3xl">سجّل كمنظّم مغامرات</h2>
                <p class="mt-3 text-sm leading-7 text-white/80">أنشئ حساب منظّم، أضف مغامراتك ومرشدك، وتابع الحجوزات بعد مراجعة طلبك والموافقة عليه.</p>
            </div>
            @guest
                <a href="{{ route('register', ['role' => 'Activity_Organizer']) }}" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-[#A94B2B] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#8E3D22]">ابدأ تسجيل المنظّم</a>
            @else
                @if (auth()->user()->role === 'Activity_Organizer')
                    <a href="{{ route('organizers.index') }}" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-[#A94B2B] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#8E3D22]">ملف المنظّم</a>
                @else
                    <p class="text-sm text-white/80">لتسجيل حساب منظّم جديد، سجّل الخروج أولًا ثم ابدأ طلبك.</p>
                @endif
            @endguest
        </div>
    </section>
@endsection
