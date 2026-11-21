@extends('layouts.site')

@section('title', 'اكتشف مغامرات الأردن | YallaJO')
@section('meta_description', 'احجز رحلات هايكنج وتخييم وغوص وتجارب ريفية في مختلف مناطق الأردن.')

@section('content')
    <section class="landing-hero relative isolate min-h-[560px] overflow-hidden bg-ink text-white" aria-labelledby="hero-title">
        <div class="landing-topography" aria-hidden="true"></div>
        <img
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCyNMFQwtfryBGvkUx_KyP8NtN2rqpr7hbzgwob3Q8PXMjKxlX74mrKWxazvPmWr4zg05dDaeCdcKEYjY93G-9-C_hujFUJOY0ij5yBmb_M1QF35TrgMZltoYsYV-2zZoZrgg7ONHVkvUK4OGLyWtNHlVCA8xSqDLu4KKagoJWQ1k6KAzxKRFwfOVYktOeXJnpGfOhWMhlO3CP_ddtg54Exy9U8EVV43ZWTCivGr4s"
            alt=""
            aria-hidden="true"
            fetchpriority="high"
            class="absolute inset-0 -z-20 h-full w-full object-cover">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-ink/85 via-ink/60 to-ink/95"></div>

        <div class="mx-auto flex min-h-[560px] max-w-[1360px] flex-col items-center justify-center px-4 py-14 text-center sm:px-6 lg:px-12">
            <p class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/15 px-5 py-2 text-sm text-white backdrop-blur-sm">
                <span class="text-orange-200" aria-hidden="true">✦</span>
                بوابتك لتجارب ومغامرات الطبيعة في الأردن
            </p>

            <h1 id="hero-title" class="max-w-4xl font-display text-4xl font-extrabold leading-[1.18] tracking-tight drop-shadow-lg md:text-5xl lg:text-6xl">
                اكتشف مسارات الأردن<br>
                <span class="mt-1 inline-block text-[#f1b84b]">ومجتمرات المغامرة</span>
            </h1>

            <p class="mt-5 max-w-2xl text-base leading-8 text-white/90 md:text-lg">
                احجز رحلات هايكنج، تخييم، غطس وتجارب ريفية،
                واكتشف مغامرات جديدة مع مرشدين ومجموعات محلية موثوقة.
            </p>

            <form action="{{ route('adventuresAll.index') }}" method="GET"
                  class="mt-7 grid w-full max-w-5xl gap-2 rounded-card bg-surface p-2 text-right text-ink shadow-2xl md:grid-cols-2 lg:grid-cols-4">
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

                <button type="submit" class="min-h-20 rounded-xl bg-brand px-6 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-brand-dark">
                    ابحث عن مغامرة
                </button>
            </form>

            <div class="mt-5 flex flex-wrap items-center justify-center gap-3 text-sm text-white/90" aria-label="أنشطة رائجة">
                <span class="font-bold text-orange-200">رائج الآن:</span>
                <span class="rounded-full border border-white/25 bg-white/10 px-3 py-1.5">مسار وادي الموجب</span>
                <span class="rounded-full border border-white/25 bg-white/10 px-3 py-1.5">تخييم وادي رم</span>
                <span class="rounded-full border border-white/25 bg-white/10 px-3 py-1.5">غوص العقبة</span>
            </div>

        </div>
    </section>

    <section class="relative z-20 -mt-1 bg-surface px-4 py-7 sm:px-6 lg:px-12" aria-label="أنواع المغامرات">
        <div class="mx-auto grid max-w-[1360px] grid-cols-2 gap-3 md:grid-cols-4">
            <a href="{{ route('adventuresAll.index') }}" class="landing-value-card landing-category-card flex items-center gap-4 p-5 transition hover:-translate-y-1 hover:border-brand/30">
                <span class="grid size-12 shrink-0 place-items-center rounded-full bg-brand/10 text-xl text-brand" aria-hidden="true">⌁</span>
                <span>
                    <strong class="block font-display text-base text-ink">مسارات ومشي</strong>
                    <span class="mt-1 block text-xs text-muted">اكتشف الطبيعة</span>
                </span>
            </a>
            <a href="{{ route('adventuresAll.index') }}" class="landing-value-card landing-category-card flex items-center gap-4 p-5 transition hover:-translate-y-1 hover:border-brand/30">
                <span class="grid size-12 shrink-0 place-items-center rounded-full bg-forest/10 text-xl text-forest" aria-hidden="true">△</span>
                <span>
                    <strong class="block font-display text-base text-ink">تخييم ورصد نجوم</strong>
                    <span class="mt-1 block text-xs text-muted">ليالي تحت السماء</span>
                </span>
            </a>
            <a href="{{ route('adventuresAll.index') }}" class="landing-value-card landing-category-card flex items-center gap-4 p-5 transition hover:-translate-y-1 hover:border-brand/30">
                <span class="grid size-12 shrink-0 place-items-center rounded-full bg-accent/10 text-xl text-accent" aria-hidden="true">≋</span>
                <span>
                    <strong class="block font-display text-base text-ink">غوص ومياه</strong>
                    <span class="mt-1 block text-xs text-muted">عروض البحر الأحمر</span>
                </span>
            </a>
            <a href="{{ route('adventuresAll.index') }}" class="landing-value-card landing-category-card flex items-center gap-4 p-5 transition hover:-translate-y-1 hover:border-brand/30">
                <span class="grid size-12 shrink-0 place-items-center rounded-full bg-[#f2e5d7] text-xl text-[#7b5a35]" aria-hidden="true">♧</span>
                <span>
                    <strong class="block font-display text-base text-ink">تجارب ريفية</strong>
                    <span class="mt-1 block text-xs text-muted">ذوق الثقافة المحلية</span>
                </span>
            </a>
        </div>
    </section>

    @include('partials.home.featured-adventures')

    <section class="border-y border-outline/20 bg-canvas py-12 md:py-16" aria-labelledby="why-yallajo-title">
        <div class="mx-auto max-w-[1360px] px-4 sm:px-6 lg:px-12">
            <div class="mb-8 text-center">
                <p class="landing-eyebrow justify-center before:hidden">لماذا يلا جو؟</p>
                <h2 id="why-yallajo-title" class="mt-3 font-display text-3xl font-bold text-ink">
                    لماذا تختار يلا جو لمغامرتك؟
                </h2>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                <article class="landing-value-card p-6 text-right">
                    <span class="grid size-12 place-items-center rounded-full bg-brand/10 text-xl text-brand" aria-hidden="true">✓</span>
                    <h3 class="mt-5 font-display text-xl font-bold text-ink">مرشدون موثوقون</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">مرشدون ومنظمون معتمدون يرافقون رحلتك ويقدّمون تجارب آمنة ومهنية.</p>
                </article>
                <article class="landing-value-card p-6 text-right">
                    <span class="grid size-12 place-items-center rounded-full bg-forest/10 text-xl text-forest" aria-hidden="true">♧</span>
                    <h3 class="mt-5 font-display text-xl font-bold text-ink">تنوع للتجارب</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">اختر من مسارات المشي والتخييم والغوص والفعاليات الثقافية والريفية.</p>
                </article>
                <article class="landing-value-card p-6 text-right">
                    <span class="grid size-12 place-items-center rounded-full bg-accent/10 text-xl text-accent" aria-hidden="true">♡</span>
                    <h3 class="mt-5 font-display text-xl font-bold text-ink">مجتمع مغامرين</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">انضم إلى تجارب تشبه اهتماماتك واكتشف زوايا الأردن مع محبي المغامرة.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-surface py-14 md:py-16" aria-labelledby="destinations-title">
        <div class="mx-auto max-w-[1360px] px-4 sm:px-6 lg:px-12">
            <div class="mb-8 flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-bold text-brand">وجهات مميزة</p>
                    <h2 id="destinations-title" class="landing-section-title mt-2 font-display text-3xl font-bold text-ink">
                        استكشف وجهات الأردن الساحرة
                    </h2>
                </div>
                <a href="{{ route('adventuresAll.index') }}" class="hidden text-sm font-bold text-brand hover:underline sm:inline">عرض كل الوجهات</a>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                <a href="{{ route('adventuresAll.index') }}" class="landing-destination-card">
                    <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=85" alt="غابات ومروج عجلون" loading="lazy">
                    <span class="absolute bottom-5 right-5 z-10 text-white">
                        <strong class="block font-display text-xl">عجلون وغابات الشمال</strong>
                        <span class="mt-1 block text-xs text-white/75">هايكنج ومسارات طبيعية</span>
                    </span>
                </a>
                <a href="{{ route('adventuresAll.index') }}" class="landing-destination-card">
                    <img src="https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=900&q=85" alt="كثبان وادي رم" loading="lazy">
                    <span class="absolute bottom-5 right-5 z-10 text-white">
                        <strong class="block font-display text-xl">وادي رم والتخييم</strong>
                        <span class="mt-1 block text-xs text-white/75">تخييم ومغامرات صحراوية</span>
                    </span>
                </a>
                <a href="{{ route('adventuresAll.index') }}" class="landing-destination-card">
                    <img src="https://images.unsplash.com/photo-1518837695005-2083093ee35b?auto=format&fit=crop&w=900&q=85" alt="شاطئ ومياه العقبة" loading="lazy">
                    <span class="absolute bottom-5 right-5 z-10 text-white">
                        <strong class="block font-display text-xl">العقبة والبحر الأحمر</strong>
                        <span class="mt-1 block text-xs text-white/75">غوص ورياضات بحرية</span>
                    </span>
                </a>
            </div>
        </div>
    </section>

    <section class="bg-canvas px-4 py-10 sm:px-6 lg:px-8" aria-label="بدء مغامرة أو الانضمام كمنظم">
        <div class="landing-cta mx-auto grid max-w-[1360px] gap-6 rounded-card bg-forest px-7 py-9 text-white md:grid-cols-[1fr_auto] md:items-center sm:px-10">
            <div>
                <p class="text-sm font-bold text-[#f1b84b]">هل أنت منظّم مغامرات؟</p>
                <h2 class="mt-2 max-w-2xl font-display text-2xl font-bold leading-snug sm:text-3xl">شارك تجاربك مع مجتمع من محبي الاستكشاف في الأردن</h2>
                <p class="mt-3 text-sm leading-7 text-white/75">أنشئ ملف منظّمك وأضف مغامراتك وابدأ استقبال الحجوزات بعد الموافقة.</p>
            </div>
            @guest
                <a href="{{ route('register', ['role' => 'Activity_Organizer']) }}" class="inline-flex min-h-12 items-center justify-center rounded-control bg-white px-6 py-3 text-sm font-bold text-forest transition hover:bg-[#eef3ef]">ابدأ كمنظّم</a>
            @else
                <a href="{{ auth()->user()->role === 'Activity_Organizer' ? route('organizers.index') : route('profile.edit') }}" class="inline-flex min-h-12 items-center justify-center rounded-control bg-white px-6 py-3 text-sm font-bold text-forest">إدارة حسابك</a>
            @endguest
        </div>
    </section>

@endsection
