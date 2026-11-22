@extends('layouts.site')

@section('title', 'المجتمعات | يلا جو')

@section('content')
    <main class="bg-canvas">
        <section class="communities-hero border-b border-white/10 text-white">
            <div class="mx-auto max-w-[1360px] px-4 py-12 sm:px-6 sm:py-16 lg:px-12">
                <div class="max-w-2xl">
                    <p class="text-sm font-bold text-[#f1b84b]">اتصل بالمغامرين القريبين منك</p>
                    <h1 class="mt-2 font-display text-3xl font-extrabold sm:text-5xl">مجتمعات المغامرة</h1>
                    <p class="mt-4 text-sm leading-8 text-white/90 sm:text-base">
                        انضم إلى مجموعة حسب منطقتك أو نوع نشاطك، وتحدث مع المغامرين الذين يشاركونك الاهتمامات.
                    </p>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-[1360px] px-4 py-8 sm:px-6 lg:px-12">
            <form method="GET" action="{{ route('communities.index') }}" class="mb-8 grid items-end gap-3 rounded-card border border-outline/40 bg-surface p-3 shadow-card md:grid-cols-[1.4fr_1fr_1fr_auto]">
                <label class="min-w-0">
                    <span class="mb-2 block text-xs font-bold text-muted">البحث</span>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="اسم المجتمع أو المنطقة..."
                           class="block min-h-12 w-full rounded-control border-outline/50 text-sm text-ink placeholder:text-muted/60 focus:border-brand focus:ring-brand">
                </label>
                <label>
                    <span class="mb-2 block text-xs font-bold text-muted">المنطقة</span>
                    <select name="region" class="block min-h-12 w-full rounded-control border-outline/50 text-sm text-ink focus:border-brand focus:ring-brand">
                        <option value="">كل المناطق</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region }}" @selected(request('region') === $region)>{{ $region }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <span class="mb-2 block text-xs font-bold text-muted">نوع النشاط</span>
                    <select name="activity_style" class="block min-h-12 w-full rounded-control border-outline/50 text-sm text-ink focus:border-brand focus:ring-brand">
                        <option value="">كل الأنشطة</option>
                        @foreach ($activityStyles as $activityStyle)
                            <option value="{{ $activityStyle }}" @selected(request('activity_style') === $activityStyle)>{{ $activityStyle }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="flex gap-2">
                    <button type="submit" class="min-h-12 flex-1 rounded-control bg-brand px-5 text-sm font-bold text-white transition hover:bg-brand-dark">تصفية</button>
                    @if(request()->hasAny(['search', 'region', 'activity_style']))
                        <a href="{{ route('communities.index') }}" class="grid min-h-12 min-w-12 place-items-center rounded-control border border-outline/50 px-3 text-lg text-muted transition hover:border-brand/40 hover:bg-brand/5" title="مسح التصفية" aria-label="مسح التصفية">×</a>
                    @endif
                </div>
            </form>

            <div class="mb-5 flex items-end justify-between">
                <div>
                    <p class="text-xs font-bold text-brand">المجتمعات المتاحة</p>
                    <h2 class="mt-1 font-display text-2xl font-bold text-ink">اعثر على مجموعة تشبه اهتماماتك</h2>
                </div>
                <p class="hidden text-sm text-muted sm:block">{{ $communities->total() }} مجتمع</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($communities as $community)
                    <article class="community-card">
                        <div class="community-cover">
                            @if ($community->cover_image)
                                <img src="{{ $community->cover_image }}" alt="{{ $community->name }}" class="h-full w-full object-cover">
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-full bg-forest/10 px-3 py-1 text-xs font-bold text-forest">{{ $community->region }}</span>
                                <span class="rounded-full bg-brand/10 px-3 py-1 text-xs font-bold text-brand">{{ $community->activity_style }}</span>
                            </div>
                            <h3 class="mt-4 font-display text-xl font-bold text-ink">{{ $community->name }}</h3>
                            <p class="mt-2 line-clamp-3 min-h-16 text-sm leading-7 text-muted">{{ $community->description }}</p>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-xs font-semibold text-muted">{{ $community->members_count }} عضو</span>
                                <a href="{{ route('communities.show', $community) }}" class="inline-flex min-h-11 items-center rounded-control bg-brand px-4 text-sm font-bold text-white transition hover:bg-brand-dark">استكشف المجتمع</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-card border border-dashed border-outline/60 bg-surface px-6 py-14 text-center">
                        <span class="mx-auto grid size-14 place-items-center rounded-full bg-brand/10 text-2xl text-brand" aria-hidden="true">⌕</span>
                        <p class="mt-4 font-display text-lg font-bold text-ink">لا توجد مجتمعات مطابقة لبحثك</p>
                        <a href="{{ route('communities.index') }}" class="mt-4 inline-flex min-h-11 items-center rounded-control bg-brand px-5 text-sm font-bold text-white">عرض جميع المجتمعات</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">{{ $communities->links() }}</div>
        </section>
    </main>
@endsection
