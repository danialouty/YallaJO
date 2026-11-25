@extends('layouts.site')

@section('title', 'اكتشف مغامرات الأردن | YallaJO')
@section('meta_description', 'احجز رحلات هايكنج وتخييم وغوص وتجارب ريفية في مختلف مناطق الأردن.')

@section('content')
    <section class="landing-hero landing-reveal relative isolate min-h-[540px] overflow-hidden bg-ink text-white lg:min-h-[680px]" aria-labelledby="hero-title">
        <div class="landing-topography" aria-hidden="true"></div>
        <img
            src="https://www.nomade-aventure.com/Content/Images/ImgProduits/JOR/10032/645135_Panorama_lg.ori.jpg"
            alt=""
            aria-hidden="true"
            fetchpriority="high"
            class="absolute inset-0 -z-20 h-full w-full object-cover object-center">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-ink/85 via-ink/60 to-ink/95"></div>

        <div class="mx-auto flex min-h-[540px] max-w-[1360px] flex-col items-center justify-center px-4 py-12 text-center sm:px-6 lg:min-h-[680px] lg:px-12 lg:py-16">
            <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/15 px-4 py-1.5 text-xs text-white backdrop-blur-sm">
                <span class="text-orange-200" aria-hidden="true">✦</span>
                بوابتك لتجارب ومغامرات الطبيعة في الأردن
            </p>

            <h1 id="hero-title" class="max-w-4xl font-display text-3xl font-extrabold leading-[1.16] tracking-tight drop-shadow-lg md:text-5xl">
                اكتشف مسارات الأردن<br>
                <span class="mt-1 inline-block text-[#f1b84b]">مجتمعات المغامرة</span>
            </h1>

            <p class="mt-4 max-w-2xl text-sm leading-7 text-white/90 md:text-base">
                احجز رحلات هايكنج، تخييم، غطس وتجارب ريفية،
                واكتشف مغامرات جديدة مع مرشدين ومجموعات محلية موثوقة.
            </p>

            <div class="landing-hero-actions" aria-label="إجراءات استكشاف يلا جو">
                <a href="{{ route('adventuresAll.index') }}" class="landing-action inline-flex min-h-11 items-center justify-center rounded-control bg-brand px-5 py-2.5 text-sm font-bold text-white transition hover:bg-brand-dark">استكشف المغامرات</a>
                <a href="{{ route('communities.index') }}" class="landing-action inline-flex min-h-11 items-center justify-center rounded-control border border-white/45 bg-white/10 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-white/20">اكتشف المجتمعات</a>
            </div>

            <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs text-white/90" aria-label="أنشطة رائجة">
                <span class="font-bold text-orange-200">رائج الآن:</span>
                <span class="rounded-full border border-white/25 bg-white/10 px-3 py-1.5">مسار وادي الموجب</span>
                <span class="rounded-full border border-white/25 bg-white/10 px-3 py-1.5">تخييم وادي رم</span>
                <span class="rounded-full border border-white/25 bg-white/10 px-3 py-1.5">غوص العقبة</span>
            </div>

        </div>
    </section>

    @include('partials.home.featured-adventures')

    <section class="landing-reveal border-y border-outline/20 bg-canvas py-10 md:py-12" aria-labelledby="why-yallajo-title">
        <div class="mx-auto max-w-[1360px] px-4 sm:px-6 lg:px-12">
            <div class="mb-6 text-center">
                <p class="landing-eyebrow justify-center before:hidden">لماذا يلا جو؟</p>
                <h2 id="why-yallajo-title" class="mt-2 font-display text-2xl font-bold text-ink md:text-3xl">
                    لماذا تختار يلا جو لمغامرتك؟
                </h2>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <article class="landing-value-card min-h-0 p-5 text-right">
                    <span class="grid size-10 place-items-center rounded-full bg-brand/10 text-lg text-brand" aria-hidden="true">✓</span>
                    <h3 class="mt-3 font-display text-lg font-bold text-ink">مرشدون موثوقون</h3>
                    <p class="mt-2 text-xs leading-6 text-muted">مرشدون ومنظمون معتمدون يرافقون رحلتك ويقدّمون تجارب آمنة ومهنية.</p>
                </article>
                <article class="landing-value-card min-h-0 p-5 text-right">
                    <span class="grid size-10 place-items-center rounded-full bg-forest/10 text-lg text-forest" aria-hidden="true">♧</span>
                    <h3 class="mt-3 font-display text-lg font-bold text-ink">تنوع للتجارب</h3>
                    <p class="mt-2 text-xs leading-6 text-muted">اختر من مسارات المشي والتخييم والغوص والفعاليات الثقافية والريفية.</p>
                </article>
                <article class="landing-value-card min-h-0 p-5 text-right">
                    <span class="grid size-10 place-items-center rounded-full bg-accent/10 text-lg text-accent" aria-hidden="true">♡</span>
                    <h3 class="mt-3 font-display text-lg font-bold text-ink">مجتمع مغامرين</h3>
                    <p class="mt-2 text-xs leading-6 text-muted">انضم إلى تجارب تشبه اهتماماتك واكتشف زوايا الأردن مع محبي المغامرة.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="landing-reveal bg-canvas py-10 md:py-12" aria-labelledby="communities-title">
        <div class="mx-auto max-w-[1360px] px-4 sm:px-6 lg:px-12">
            <div class="mb-6 flex items-end justify-between gap-4">
                <div>
                    <p class="landing-eyebrow">مجتمعات المغامرة</p>
                    <h2 id="communities-title" class="mt-2 font-display text-2xl font-bold text-ink md:text-3xl">
                        مجتمعات أنشطة لتشاركك
                    </h2>
                    <p class="mt-2 max-w-2xl text-sm leading-7 text-muted">لا تستكشف وحدك؛ انضم لمجموعة تناسب اهتماماتك واطلع على رحلات وعروض المجتمع.</p>
                </div>
                <a href="{{ route('communities.index') }}" class="landing-action hidden min-h-11 items-center rounded-control bg-brand px-4 text-sm font-bold text-white sm:inline-flex">استكشف المجتمعات</a>
            </div>

            <div class="grid gap-5 md:grid-cols-3">
                @forelse ($communities as $community)
                    <article class="landing-community-card">
                        <div class="landing-community-cover">
                            <img src="{{ $community->cover_image ?: 'https://photos.altai-travel.com/1280x0/jordanie-wadi-rum-11445.jpg' }}"
                                 alt="{{ $community->name }}" loading="lazy">
                        </div>
                        <div class="p-5">
                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-full bg-forest/10 px-3 py-1 text-xs font-bold text-forest">{{ $community->region }}</span>
                                <span class="rounded-full bg-brand/10 px-3 py-1 text-xs font-bold text-brand">{{ $community->activity_style }}</span>
                            </div>
                            <h3 class="mt-4 font-display text-lg font-bold leading-7 text-ink">{{ $community->name }}</h3>
                            <p class="mt-2 line-clamp-2 min-h-12 text-sm leading-6 text-muted">{{ $community->description }}</p>
                            <div class="mt-4 flex items-center justify-between border-t border-outline/30 pt-4">
                                <span class="text-xs font-bold text-muted">{{ $community->members_count }} عضو</span>
                                <a href="{{ route('communities.show', $community) }}" class="landing-action min-h-10 inline-flex items-center rounded-control bg-brand px-4 text-sm font-bold text-white hover:bg-brand-dark">انضم للمجتمع</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-card border border-dashed border-outline/50 bg-surface py-10 text-center text-sm text-muted">
                        لا توجد مجتمعات متاحة حاليًا.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="landing-reveal bg-surface px-4 pb-10 sm:px-6 lg:px-8" aria-label="بدء مغامرة أو الانضمام كمنظم">
        <div class="landing-cta mx-auto grid max-w-[1360px] gap-4 rounded-card bg-ink px-7 py-7 text-white md:grid-cols-[1fr_auto] md:items-center sm:px-10">
            <div>
                <p class="text-sm font-bold text-[#f1b84b]">هل تنظم تجارب؟</p>
                <h2 class="mt-1 max-w-3xl font-display text-xl font-bold leading-snug sm:text-2xl">هل تنظم رحلة محلية وممتعة؟ اعرض تجربتك الآن</h2>
                <p class="mt-2 text-xs leading-6 text-white/75">انضم كمنظّم، أضف مغامراتك، واعرف المسافرين المهتمين بالأردن عبر منصة YallaJO.</p>
            </div>
            @guest
                <a href="{{ route('register', ['role' => 'Activity_Organizer']) }}" class="landing-action inline-flex min-h-11 items-center justify-center rounded-control bg-brand px-5 py-2.5 text-sm font-bold text-white transition hover:bg-brand-dark">ابدأ كمنظّم</a>
            @else
                <a href="{{ auth()->user()->role === 'Activity_Organizer' ? route('organizers.index') : route('profile.edit') }}" class="landing-action inline-flex min-h-11 items-center justify-center rounded-control bg-brand px-5 py-2.5 text-sm font-bold text-white">إدارة حسابك</a>
            @endguest
        </div>
    </section>

@endsection
