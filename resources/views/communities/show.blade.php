@extends('layouts.site')

@section('title', $community->name . ' | يلا جو')

@section('content')
    <main class="bg-slate-50">
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
                <a href="{{ route('communities.index') }}" class="text-sm font-bold text-slate-500 hover:text-emerald-800">← جميع المجتمعات</a>
                <div class="mt-5 flex flex-wrap items-start justify-between gap-5">
                    <div>
                        <div class="flex flex-wrap gap-2 text-xs font-bold">
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-emerald-800">{{ $community->region }}</span>
                            <span class="rounded-full bg-orange-100 px-3 py-1 text-orange-800">{{ $community->activity_style }}</span>
                        </div>
                        <h1 class="mt-3 text-3xl font-black text-slate-900">{{ $community->name }}</h1>
                        <p class="mt-3 max-w-3xl leading-8 text-slate-600">{{ $community->description }}</p>
                        <p class="mt-3 text-sm text-slate-500">{{ $community->members_count }} عضو</p>
                    </div>

                    @auth
                        @if ($isMember)
                            <form method="POST" action="{{ route('communities.memberships.destroy', [$community, $membership]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50">مغادرة</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('communities.memberships.store', $community) }}">
                                @csrf
                                <button type="submit" class="rounded-lg bg-emerald-800 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-900">انضم إلى المجتمع</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg bg-emerald-800 px-5 py-2.5 text-sm font-bold text-white">سجّل للدخول والانضمام</a>
                    @endauth
                </div>
            </div>
        </section>

        @session('success')
            <div class="mx-auto mt-6 max-w-5xl px-4 sm:px-6">
                <div class="rounded-lg bg-emerald-100 px-4 py-3 text-sm font-semibold text-emerald-900">{{ $value }}</div>
            </div>
        @endsession

        @if ($errors->any())
            <div class="mx-auto mt-6 max-w-5xl px-4 sm:px-6">
                <div class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800">
                    @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            </div>
        @endif

        <div class="mx-auto grid max-w-5xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[1fr_280px]">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-xl font-black text-slate-900">دردشة المجتمع</h2>

                @if ($isMember)
                    <form method="POST" action="{{ route('communities.messages.store', $community) }}" class="mt-4">
                        @csrf
                        <div class="flex gap-3">
                            <input type="text" name="body" required maxlength="2000" placeholder="اكتب رسالة…" class="min-w-0 flex-1 rounded-lg border-slate-300 text-sm">
                            <button type="submit" class="rounded-lg bg-orange-700 px-5 py-2.5 text-sm font-bold text-white">إرسال</button>
                        </div>
                    </form>
                @endif

                <div class="mt-5 divide-y divide-slate-100">
                    @forelse ($community->messages as $message)
                        <article class="py-4 first:pt-0">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-bold text-slate-800">{{ $message->user->name }}</p>
                                <time class="text-xs text-slate-400">{{ $message->created_at->diffForHumans() }}</time>
                            </div>
                            <p class="mt-2 whitespace-pre-wrap text-sm leading-7 text-slate-600">{{ $message->body }}</p>
                        </article>
                    @empty
                        <p class="py-10 text-center text-sm text-slate-500">
                            {{ $isMember ? 'ابدأ المحادثة بكتابة أول رسالة.' : 'انضم للمجتمع لقراءة المحادثة والمشاركة.' }}
                        </p>
                    @endforelse
                </div>
            </section>

            <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="font-black text-slate-900">الأعضاء</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($community->members->take(20) as $member)
                        <div class="flex items-center gap-3">
                            <div class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-emerald-100 font-black text-emerald-800">{{ mb_substr($member->name, 0, 1) }}</div>
                            <span class="text-sm font-semibold text-slate-700">{{ $member->name }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">لا يوجد أعضاء بعد.</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </main>
@endsection
