@extends('layouts.site')

@section('title', 'عن يلا جو | YallaJO')
@section('meta_description', 'تعرف على YallaJO، منصتك الأردنية لاستكشاف المغامرات والتجارب المحلية والمرشدين المعتمدين.')

@section('content')
    <section class="about-hero flex items-center text-white" aria-labelledby="about-title">
        <div class="mx-auto w-full max-w-[1360px] px-4 sm:px-6 lg:px-12">
            <div class="max-w-3xl">
                <p class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-bold backdrop-blur-sm">
                    <span class="text-[#f1b84b]" aria-hidden="true">✦</span>
                    منصة مغامرات أردنية من القلب
                </p>
                <h1 id="about-title" class="font-display text-4xl font-extrabold leading-[1.15] sm:text-6xl">
                    نقرّبك من أروع طبيعة
                    <span class="mt-2 block text-[#f1b84b]">وحكايات الأردن</span>
                </h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-white/90 sm:text-lg">
                    يلا جو تجمع المغامرين والمنظمين والمرشدين المحليين في مكان واحد، لتخطيط مغامرات أصيلة وبأسلوب آمن وشفاف.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('adventuresAll.index') }}"
                       class="min-h-12 inline-flex items-center justify-center rounded-control bg-brand px-6 py-3 text-sm font-bold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-brand-dark">
                        استكشف المغامرات
                    </a>
                    <a href="#about-values"
                       class="min-h-12 inline-flex items-center justify-center rounded-control border border-white/35 bg-white/10 px-6 py-3 text-sm font-bold text-white backdrop-blur-sm transition hover:bg-white/20">
                        اعرف حكايتنا
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="border-b border-outline/30 bg-surface" aria-label="أرقام يلا جو">
        <div class="mx-auto grid max-w-[1360px] grid-cols-2 gap-px bg-outline/20 sm:grid-cols-4">
            <div class="bg-surface px-5 py-7 text-center">
                <p class="font-display text-3xl font-bold text-brand">+50</p>
                <p class="mt-2 text-xs text-muted sm:text-sm">مسار ومغامرة</p>
            </div>
            <div class="bg-surface px-5 py-7 text-center">
                <p class="font-display text-3xl font-bold text-forest">12</p>
                <p class="mt-2 text-xs text-muted sm:text-sm">محافظة أردنية</p>
            </div>
            <div class="bg-surface px-5 py-7 text-center">
                <p class="font-display text-3xl font-bold text-accent">100%</p>
                <p class="mt-2 text-xs text-muted sm:text-sm">خبرات محلية</p>
            </div>
            <div class="bg-surface px-5 py-7 text-center">
                <p class="font-display text-3xl font-bold text-ink">24/7</p>
                <p class="mt-2 text-xs text-muted sm:text-sm">تنسيق وحجز</p>
            </div>
        </div>
    </section>

    <section id="about-values" class="bg-canvas py-0">
        <div class="mx-auto w-full max-w-[1360px]">
            <div class="about-story-grid grid overflow-hidden lg:grid-cols-2">
                <div class="about-story-image relative">
                    <img src="https://panorama.solutions/sites/default/files/mjmwt_syhy_.jpg"
                         alt="مجموعة من المغامرين في محمية غابات عجلون"
                         loading="lazy">
                    <div class="about-story-image-fade" aria-hidden="true"></div>
                </div>

                <div class="about-story-copy flex flex-col justify-center p-6 sm:p-10 lg:p-12">
                    <p class="text-sm font-bold text-brand">قصتنا</p>
                    <h2 class="mt-3 max-w-xl font-display text-3xl font-bold leading-snug text-ink sm:text-4xl">
                        مغامرة تبدأ من المكان وأهلها
                    </h2>
                    <p class="mt-5 text-base leading-8 text-muted">
                        وُلدت YallaJO لتقرب الزائرين والأردنيين من الطبيعة والثقافة المحلية. نربط المجتمع بالمغامرين والمنظمين والمرشدين، ونمنح كل تجربة تفاصيل واضحة، بداية من الوصف والسعر وحتى حالة الحجز.
                    </p>
                    <div class="mt-7 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-control border border-outline/35 bg-surface p-4">
                            <strong class="font-display text-ink">تجارب من مصدرها</strong>
                            <p class="mt-2 text-sm leading-7 text-muted">تنظيم يقوده أهل المكان والمرشدون المحليون.</p>
                        </div>
                        <div class="rounded-control border border-outline/35 bg-surface p-4">
                            <strong class="font-display text-ink">اختيار آمن وواضح</strong>
                            <p class="mt-2 text-sm leading-7 text-muted">معلومات موثوقة تسهّل قرارك قبل الحجز.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-surface py-16" aria-labelledby="about-values-title">
        <div class="mx-auto max-w-[1360px] px-4 sm:px-6 lg:px-12">
            <div class="mb-8 text-center">
                <p class="text-sm font-bold text-brand">ما الذي يميزنا؟</p>
                <h2 id="about-values-title" class="mt-2 font-display text-3xl font-bold text-ink">مغامرة متعمدة، وصورة أوضح</h2>
            </div>

            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                <article class="about-value-card p-6">
                    <span class="grid size-12 place-items-center rounded-full bg-brand/10 text-xl text-brand" aria-hidden="true">⛰</span>
                    <h3 class="mt-5 font-display text-lg font-bold text-ink">تجارب أصيلة</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">مسارات تنقل ثقافة المكان وطبيعته كما يعيشها أهله.</p>
                </article>
                <article class="about-value-card p-6">
                    <span class="grid size-12 place-items-center rounded-full bg-forest/10 text-xl text-forest" aria-hidden="true">✓</span>
                    <h3 class="mt-5 font-display text-lg font-bold text-ink">ثقة ووضوح</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">السعر والسعة والحالة والمرشد أمامك قبل تأكيد الحجز.</p>
                </article>
                <article class="about-value-card p-6">
                    <span class="grid size-12 place-items-center rounded-full bg-accent/10 text-xl text-accent" aria-hidden="true">♧</span>
                    <h3 class="mt-5 font-display text-lg font-bold text-ink">تنوع لكل مستوى</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">من المشي والتخييم إلى الغوص والفعاليات الثقافية والريفية.</p>
                </article>
                <article class="about-value-card p-6">
                    <span class="grid size-12 place-items-center rounded-full bg-brand/10 text-xl text-brand" aria-hidden="true">♡</span>
                    <h3 class="mt-5 font-display text-lg font-bold text-ink">مجتمع متحرك</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">تواصل مع مجتمعات تشاركك الاهتمام والمغامرة.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-canvas pb-16" aria-labelledby="journey-title">
        <div class="mx-auto max-w-[1360px] px-4 sm:px-6 lg:px-12">
            <div class="mb-8 max-w-2xl">
                <p class="text-sm font-bold text-brand">رحلتك معنا</p>
                <h2 id="journey-title" class="mt-2 font-display text-3xl font-bold text-ink">من الاكتشاف إلى المغامرة</h2>
            </div>
            <div class="grid gap-4 md:grid-cols-3">
                <article class="about-step p-6">
                    <span class="font-display text-4xl font-bold text-brand/20">01</span>
                    <h3 class="mt-3 font-display text-xl font-bold text-ink">اكتشف المغامرة</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">تصفح الأنشطة حسب الموقع والفئة والمدة والسعر.</p>
                </article>
                <article class="about-step p-6">
                    <span class="font-display text-4xl font-bold text-forest/20">02</span>
                    <h3 class="mt-3 font-display text-xl font-bold text-ink">احجز مقعدك</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">اختر عدد المشاركين وأرسل الحجز بوضوح وسهولة.</p>
                </article>
                <article class="about-step p-6">
                    <span class="font-display text-4xl font-bold text-accent/20">03</span>
                    <h3 class="mt-3 font-display text-xl font-bold text-ink">عيش التجربة</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">تابع الحجز، اذهب للمغامرة، ثم شارك تجربتك.</p>
                </article>
            </div>

            <div class="about-cta mt-8 rounded-card px-6 py-9 text-white sm:px-10 md:flex md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-bold text-[#f1b84b]">جاهز لرحلتك القادمة؟</p>
                    <h2 class="mt-2 font-display text-2xl font-bold sm:text-3xl">اكتشف مغامرات الأقرب إليك</h2>
                </div>
                <a href="{{ route('adventuresAll.index') }}"
                   class="mt-6 inline-flex min-h-12 items-center justify-center rounded-control bg-brand px-6 py-3 text-sm font-bold text-white transition hover:bg-brand-dark md:mt-0">
                    تصفح المغامرات
                </a>
            </div>
        </div>
    </section>
@endsection
