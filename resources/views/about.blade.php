@extends('layouts.site')

@section('title', 'عن يلا جو | YallaJO')
@section('meta_description', 'تعرف على YallaJO، منصتك الأردنية لاستكشاف المغامرات والتجارب المحلية والمرشدين المعتمدين.')

@section('content')
    <section class="relative isolate overflow-hidden bg-forest text-white">
        <div class="absolute inset-0 opacity-10" aria-hidden="true">
            <div class="absolute -right-20 -top-32 size-96 rounded-full border-[70px] border-white/20"></div>
            <div class="absolute -bottom-24 left-1/4 size-72 rounded-full bg-brand/30 blur-3xl"></div>
        </div>

        <div class="relative mx-auto max-w-[1360px] px-4 py-16 sm:px-6 lg:px-12 lg:py-24">
            <div class="max-w-3xl">
                <p class="mb-5 inline-flex rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold backdrop-blur-sm">
                    ✦ منصة مغامرات أردنية من القلب
                </p>
                <h1 class="font-display text-4xl font-extrabold leading-tight sm:text-6xl">
                    نقرّبك من أروع الطبيعة في الأردن
                </h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-white/85 sm:text-lg">
                    يلا جو تجمع المغامرين، المرشدين، والمنظمين المحليين في مكان واحد لتجعل اكتشاف الأردن وحجز التجارب رحلة سهلة وآمنة ومليئة بالحكايات.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('adventuresAll.index') }}"
                       class="min-h-12 inline-flex items-center justify-center rounded-control bg-brand px-6 py-3 text-sm font-bold text-white transition hover:bg-brand-dark">
                        استكشف المغامرات
                    </a>
                    <a href="#about-values"
                       class="min-h-12 inline-flex items-center justify-center rounded-control border border-white/30 bg-white/10 px-6 py-3 text-sm font-bold text-white backdrop-blur-sm transition hover:bg-white/20">
                        تعرف علينا
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="border-b border-outline/30 bg-surface">
        <div class="mx-auto grid max-w-[1360px] grid-cols-2 gap-px bg-outline/20 sm:grid-cols-4">
            <div class="bg-surface px-5 py-8 text-center">
                <p class="font-display text-3xl font-bold text-brand">+50</p>
                <p class="mt-2 text-xs text-muted sm:text-sm">مسار ومغامرة</p>
            </div>
            <div class="bg-surface px-5 py-8 text-center">
                <p class="font-display text-3xl font-bold text-forest">12</p>
                <p class="mt-2 text-xs text-muted sm:text-sm">محافظة أردنية</p>
            </div>
            <div class="bg-surface px-5 py-8 text-center">
                <p class="font-display text-3xl font-bold text-accent">100%</p>
                <p class="mt-2 text-xs text-muted sm:text-sm">خبرات محلية</p>
            </div>
            <div class="bg-surface px-5 py-8 text-center">
                <p class="font-display text-3xl font-bold text-ink">24/7</p>
                <p class="mt-2 text-xs text-muted sm:text-sm">تنسيق وحجز</p>
            </div>
        </div>
    </section>

    <section id="about-values" class="bg-canvas py-16 md:py-20">
        <div class="mx-auto max-w-[1360px] px-4 sm:px-6 lg:px-12">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                <div>
                    <p class="text-sm font-bold text-brand">قصتنا</p>
                    <h2 class="mt-3 max-w-xl font-display text-3xl font-bold leading-snug text-ink sm:text-4xl">
                        مغامرة تبدأ من مكان وأهلها
                    </h2>
                    <p class="mt-5 text-base leading-8 text-muted">
                        وُلدت YallaJO لتمنح الزائرين والأردنيين تجربة سفر أقرب للثقافة والمكان، ولتربط المنظمين والمرشدين بالمغامرين بوضوح وشفافية.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <article class="rounded-card border border-outline/30 bg-surface p-6 shadow-card">
                        <span class="grid size-11 place-items-center rounded-control bg-brand/10 text-xl" aria-hidden="true">⛰</span>
                        <h3 class="mt-5 font-display text-xl font-bold text-ink">تجارب أصيلة</h3>
                        <p class="mt-3 text-sm leading-7 text-muted">مسارات ومغامرات يقودها أهل المكان ويكشفون زوايا الطبيعة والثقافة الأردنية.</p>
                    </article>
                    <article class="rounded-card border border-outline/30 bg-surface p-6 shadow-card">
                        <span class="grid size-11 place-items-center rounded-control bg-forest/10 text-xl" aria-hidden="true">✓</span>
                        <h3 class="mt-5 font-display text-xl font-bold text-ink">ثقة ووضوح</h3>
                        <p class="mt-3 text-sm leading-7 text-muted">معلومات واضحة عن السعر والسعة والحالة والمرشد قبل تأكيد الحجز.</p>
                    </article>
                    <article class="rounded-card border border-outline/30 bg-surface p-6 shadow-card">
                        <span class="grid size-11 place-items-center rounded-control bg-accent/10 text-xl" aria-hidden="true">♧</span>
                        <h3 class="mt-5 font-display text-xl font-bold text-ink">تنوع لكل مستوى</h3>
                        <p class="mt-3 text-sm leading-7 text-muted">من المشي والتخييم حتى الغوص والفعاليات الثقافية المناسبة للمبتدئين والمحترفين.</p>
                    </article>
                    <article class="rounded-card border border-outline/30 bg-surface p-6 shadow-card">
                        <span class="grid size-11 place-items-center rounded-control bg-brand/10 text-xl" aria-hidden="true">♡</span>
                        <h3 class="mt-5 font-display text-xl font-bold text-ink">مجتمع متحرك</h3>
                        <p class="mt-3 text-sm leading-7 text-muted">حجوزات وتقييمات ومعلومات تساعدك تخطط رحلتك وتشجع تجارب اجتماعية آمنة.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-surface py-16">
        <div class="mx-auto max-w-[1360px] px-4 sm:px-6 lg:px-12">
            <div class="mb-8 max-w-2xl">
                <p class="text-sm font-bold text-brand">رحلتك معنا</p>
                <h2 class="mt-3 font-display text-3xl font-bold text-ink">من الاكتشاف إلى المغامرة</h2>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <article class="relative rounded-card border border-outline/30 p-6">
                    <span class="font-display text-5xl font-bold text-brand/15">01</span>
                    <h3 class="mt-3 font-display text-xl font-bold text-ink">اكتشف المغامرة</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">تصفح الأنشطة حسب الموقع والفئة والمدة والسعر.</p>
                </article>
                <article class="relative rounded-card border border-outline/30 p-6">
                    <span class="font-display text-5xl font-bold text-forest/15">02</span>
                    <h3 class="mt-3 font-display text-xl font-bold text-ink">احجز مقاعدك</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">اختر عدد المشاركين وتفاصيل الرحلة، ثم أرسل حجزك بوضوح.</p>
                </article>
                <article class="relative rounded-card border border-outline/30 p-6">
                    <span class="font-display text-5xl font-bold text-accent/15">03</span>
                    <h3 class="mt-3 font-display text-xl font-bold text-ink">عيش التجربة</h3>
                    <p class="mt-3 text-sm leading-7 text-muted">تابع حالة الحجز، اذهب للمغامرة، وبعدها شارك تقييمك.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-canvas pb-16">
        <div class="mx-auto max-w-[1360px] px-4 sm:px-6 lg:px-12">
            <div class="rounded-card bg-ink px-6 py-10 text-white sm:px-10 md:flex md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold text-orange-200">جاهز لرحلتك القادمة؟</p>
                    <h2 class="mt-2 font-display text-3xl font-bold">اكتشف مغامرات الأقرب إليك</h2>
                </div>
                <a href="{{ route('adventuresAll.index') }}"
                   class="mt-6 inline-flex min-h-12 items-center justify-center rounded-control bg-brand px-6 py-3 text-sm font-bold text-white transition hover:bg-brand-dark md:mt-0">
                    تصفح المغامرات
                </a>
            </div>
        </div>
    </section>
@endsection
