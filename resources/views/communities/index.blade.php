@extends('layouts.site')

@section('title', 'المجتمعات | يلا جو')

@section('content')
    <main class="bg-slate-50">
        <section class="border-b border-slate-200 bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-900 text-white">
            <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <p class="text-sm font-bold text-emerald-200">اتصل بالمغامرين القريبين منك</p>
                <h1 class="mt-2 text-3xl font-black sm:text-4xl">مجتمعات المغامرة</h1>
                <p class="mt-3 max-w-2xl leading-8 text-emerald-100">
                    انضم إلى مجموعة حسب منطقتك أو نوع نشاطك، وتحدث مع المغامرين الذين يشاركونك الاهتمامات.
                </p>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('communities.index') }}" class="mb-8 grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-3">
                <label class="text-xs font-bold text-slate-600">
                    البحث
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="اسم المجتمع أو المنطقة" class="mt-1 block w-full rounded-lg border-slate-300 text-sm text-slate-900">
                </label>
                <label class="text-xs font-bold text-slate-600">
                    المنطقة
                    <select name="region" class="mt-1 block w-full rounded-lg border-slate-300 text-sm text-slate-900">
                        <option value="">كل المناطق</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region }}" @selected(request('region') === $region)>{{ $region }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-xs font-bold text-slate-600">
                    نوع النشاط
                    <select name="activity_style" class="mt-1 block w-full rounded-lg border-slate-300 text-sm text-slate-900">
                        <option value="">كل الأنشطة</option>
                        @foreach ($activityStyles as $activityStyle)
                            <option value="{{ $activityStyle }}" @selected(request('activity_style') === $activityStyle)>{{ $activityStyle }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="md:col-span-3 flex gap-2">
                    <button type="submit" class="rounded-lg bg-orange-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-orange-800">تصفية</button>
                    <a href="{{ route('communities.index') }}" class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50">مسح</a>
                </div>
            </form>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($communities as $community)
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="h-36 bg-gradient-to-br from-emerald-600 to-orange-500">
                            @if ($community->cover_image)
                                <img src="{{ $community->cover_image }}" alt="{{ $community->name }}" class="h-full w-full object-cover">
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex flex-wrap gap-2 text-xs font-bold">
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-emerald-800">{{ $community->region }}</span>
                                <span class="rounded-full bg-orange-100 px-3 py-1 text-orange-800">{{ $community->activity_style }}</span>
                            </div>
                            <h2 class="mt-4 text-xl font-black text-slate-900">{{ $community->name }}</h2>
                            <p class="mt-2 line-clamp-3 min-h-16 text-sm leading-7 text-slate-600">{{ $community->description }}</p>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-xs text-slate-500">{{ $community->members_count }} عضو</span>
                                <a href="{{ route('communities.show', $community) }}" class="rounded-lg bg-emerald-800 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-900">استكشف المجتمع</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                        <p class="font-bold text-slate-700">لا توجد مجتمعات مطابقة لبحثك.</p>
                        <a href="{{ route('communities.index') }}" class="mt-3 inline-block text-sm font-bold text-orange-700">عرض جميع المجتمعات</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">{{ $communities->links() }}</div>
        </section>
    </main>
@endsection
