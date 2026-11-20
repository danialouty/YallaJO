@extends('layouts.site')

@section('title', 'اكتشف مغامرات الأردن | YallaJO')

@section(
    'meta_description',
    'اكتشف مسارات الأردن والمغامرات والأنشطة السياحية مع يلا جو.'
)

@section('content')

    {{-- Hero Section --}}
    <section class="relative isolate min-h-[620px]
                   overflow-hidden bg-ink text-white" aria-labelledby="hero-title">

        {{-- Background Image --}}
        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCyNMFQwtfryBGvkUx_KyP8NtN2rqpr7hbzgwob3Q8PXMjKxlX74mrKWxazvPmWr4zg05dDaeCdcKEYjY93G-9-C_hujFUJOY0ij5yBmb_M1QF35TrgMZltoYsYV-2zZoZrgg7ONHVkvUK4OGLyWtNHlVCA8xSqDLu4KKagoJWQ1k6KAzxKRFwfOVYktOeXJnpGfOhWMhlO3CP_ddtg54Exy9U8EVV43ZWTCivGr4s"
            alt="" aria-hidden="true" fetchpriority="high" class="absolute inset-0 -z-20
                       h-full w-full object-cover">

        {{-- Dark Overlay --}}
        <div class="absolute inset-0 -z-10
                       bg-gradient-to-b
                       from-ink/85 via-ink/60 to-ink/95"></div>

        {{-- Hero Content --}}
        <div class="mx-auto flex min-h-[620px]
                       max-w-[1360px] flex-col
                       items-center justify-center
                       px-4 py-20 text-center
                       sm:px-6 lg:px-12">

            {{-- Tagline --}}
            <p class="mb-6 inline-flex items-center
                           gap-2 rounded-full
                           border border-white/20
                           bg-white/15 px-5 py-2
                           text-sm text-white
                           backdrop-blur-sm">
                <span class="text-orange-200" aria-hidden="true">
                    ✦
                </span>

                بوابتك لتجارب ومغامرات الطبيعة في الأردن
            </p>

            {{-- Heading --}}
            <h1 id="hero-title" class="max-w-4xl font-display
                           text-4xl font-extrabold
                           leading-tight tracking-tight
                           drop-shadow-lg
                           md:text-6xl lg:text-7xl">
                اكتشف مسارات الأردن

                <span class="mt-2 block text-orange-200">
                    ومجتمعات المغامرة
                </span>
            </h1>

            {{-- Description --}}
            <p class="mt-7 max-w-2xl
                           text-base leading-8 text-white/90
                           md:text-lg">
                احجز رحلات هايكنج، تخييم، غطس،
                وتجارب ريفية في مختلف مناطق الأردن،
                واكتشف أنشطة جديدة مع مجموعات
                تشاركك حب المغامرة.
            </p>

            {{-- Adventure Search & Filters --}}
            <x-adventure.search-bar id-prefix="home-search" class="mt-10" />

            {{-- Trending Activities --}}
            <div class="mt-4 flex flex-wrap
                           items-center justify-center
                           gap-3 text-sm text-white/90" aria-label="أنشطة رائجة">
                <span class="font-bold text-orange-200">
                    رائج الآن:
                </span>

                <span class="rounded-full border
                               border-white/25
                               bg-white/10 px-3 py-1.5">
                    مسار وادي الموجب
                </span>

                <span class="rounded-full border
                               border-white/25
                               bg-white/10 px-3 py-1.5">
                    تخييم وادي رم
                </span>

                <span class="rounded-full border
                               border-white/25
                               bg-white/10 px-3 py-1.5">
                    قطف زيتون عجلون
                </span>

                <span class="rounded-full border
                               border-white/25
                               bg-white/10 px-3 py-1.5">
                    غطس العقبة
                </span>
            </div>

        </div>

    </section>
    {{-- Featured Adventures Section --}}
    @include('partials.home.featured-adventures')

@endsection