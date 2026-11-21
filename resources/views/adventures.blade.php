@extends('layouts.site')

@section('title', 'جميع المغامرات | YallaJO')
@section('meta_description', 'استكشف مغامرات الأردن واختر رحلتك حسب الموقع والنشاط والسعر.')

@section('content')
    <section class="adventures-hero border-b border-outline/30">
        <div class="adventures-hero-image" aria-hidden="true"></div>
        <div class="adventures-hero-overlay" aria-hidden="true"></div>
        <div class="mx-auto max-w-[1360px] px-4 py-14 text-white sm:px-6 lg:px-12 lg:py-20">
            <div class="relative max-w-3xl reveal-up">
                <p class="mb-3 text-sm font-bold text-orange-200">اكتشف الأردن بطريقتك</p>
                <h1 class="font-display text-4xl font-extrabold leading-tight drop-shadow-lg md:text-5xl">
                    مغامرات قريبة، وتجارب لا تُنسى
                </h1>
                <p class="mt-4 max-w-2xl text-base leading-8 text-white/85">
                    اختر المغامرة المناسبة لمستوى خبرتك، وقارِن الوجهات والأسعار والمدة قبل الحجز.
                </p>
                <div class="mt-7 flex flex-wrap gap-2">
                    <span class="adventures-hero-chip">🥾 هايكنج ومسارات</span>
                    <span class="adventures-hero-chip">🏕️ تخييم ومبيت</span>
                    <span class="adventures-hero-chip">🌊 غوص ومياه</span>
                    <span class="adventures-hero-chip">🫒 تجارب ريفية</span>
                </div>
            </div>
        </div>
    </section>

    <main class="bg-canvas">
        <div class="mx-auto max-w-[1360px] px-4 py-8 sm:px-6 lg:px-12">
            <form action="{{ route('adventuresAll.index') }}" method="GET"
                  class="adventures-filter grid gap-3 rounded-card border border-outline/30 bg-surface p-4 shadow-card md:grid-cols-2 lg:grid-cols-[1fr_220px_220px_auto]">
                <label class="block">
                    <span class="mb-2 block text-xs font-bold text-muted">ابحث باسم المغامرة</span>
                    <input type="search" name="search" value="{{ request('search') }}"
                           placeholder="مثال: وادي الموجب"
                           class="min-h-12 w-full rounded-control border border-outline/50 bg-white px-4 text-sm text-ink focus:border-brand focus:outline-none focus:ring-4 focus:ring-brand/10">
                </label>

                <label class="block">
                    <span class="mb-2 block text-xs font-bold text-muted">الموقع</span>
                    <select name="location"
                            class="min-h-12 w-full rounded-control border border-outline/50 bg-white px-4 text-sm text-ink focus:border-brand focus:outline-none focus:ring-4 focus:ring-brand/10">
                        <option value="">جميع المواقع</option>
                        @foreach($locations as $location)
                            <option value="{{ $location }}" @selected(request('location') == $location)>{{ $location }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="mb-2 block text-xs font-bold text-muted">نوع النشاط</span>
                    <select name="category"
                            class="min-h-12 w-full rounded-control border border-outline/50 bg-white px-4 text-sm text-ink focus:border-brand focus:outline-none focus:ring-4 focus:ring-brand/10">
                        <option value="">كل الأنشطة</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" @selected(request('category') == $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </label>

                <button type="submit"
                        class="mt-auto min-h-12 rounded-control bg-brand px-6 text-sm font-bold text-white transition hover:bg-brand-dark">
                    عرض النتائج
                </button>
            </form>

            <div class="mt-8 mb-5 flex items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-bold text-forest">المغامرات المتاحة</p>
                    <h2 class="mt-1 font-display text-2xl font-bold text-ink">اختر رحلتك القادمة</h2>
                </div>
                <span class="rounded-full bg-surface px-3 py-1.5 text-sm text-muted">
                    {{ $adventures->total() }} مغامرة
                </span>
            </div>

            <div class="adventure-grid grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($adventures as $adventure)
                    <article class="adventure-card reveal-card group flex h-full flex-col overflow-hidden rounded-card border border-outline/30 bg-surface shadow-card">
                        <a href="{{ route('adventures.profile', $adventure) }}" class="relative block aspect-[4/3] overflow-hidden bg-canvas">
                            <img
                                src="{{ $adventure->images->isNotEmpty() ? asset('images/' . $adventure->images->first()->image_path) : 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=900' }}"
                                alt="{{ $adventure->title }}"
                                loading="lazy"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            <span class="adventure-category absolute right-3 top-3 rounded-full px-3 py-1.5 text-xs font-bold text-white">
                                {{ $adventure->category }}
                            </span>
                        </a>

                        <div class="flex flex-1 flex-col p-5">
                            <p class="text-xs font-semibold text-muted">📍 {{ $adventure->location }}</p>
                            <h3 class="mt-2 min-h-14 font-display text-lg font-bold leading-7 text-ink">
                                {{ $adventure->title }}
                            </h3>

                            <div class="mt-4 grid grid-cols-2 gap-2 border-y border-outline/30 py-3 text-xs text-muted">
                                <span>⏱ {{ $adventure->duration_hours }} ساعات</span>
                                <span>👥 {{ $adventure->capacity }} مقاعد</span>
                            </div>

                            <div class="mt-auto flex items-end justify-between gap-3 pt-4">
                                <div>
                                    <p class="text-xs text-muted">السعر للشخص</p>
                                    <p class="mt-1 font-display text-xl font-bold text-brand">
                                        {{ number_format($adventure->price, 2) }}
                                        <span class="text-sm">د.أ</span>
                                    </p>
                                </div>
                                <a href="{{ route('adventures.profile', $adventure) }}"
                                   class="min-h-11 rounded-control bg-brand px-4 py-3 text-sm font-bold text-white transition hover:bg-brand-dark">
                                    عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-card border border-outline/30 bg-surface py-16 text-center">
                        <p class="font-display text-xl font-bold text-ink">لا توجد مغامرات مطابقة</p>
                        <p class="mt-2 text-sm text-muted">جرّب تغيير الموقع أو نوع النشاط.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">{{ $adventures->links() }}</div>
        </div>
    </main>
@endsection
