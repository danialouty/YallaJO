@extends('layouts.site')

@section('title', $community->name . ' | يلا جو')

@section('content')
    <main class="min-h-screen bg-canvas">
        <section class="community-detail-cover" aria-label="صورة المجتمع">
            @if ($community->cover_image)
                <img src="{{ $community->cover_image }}" alt="" fetchpriority="high">
            @endif
            <div class="absolute bottom-0 right-0 z-10 w-full px-4 pb-7 text-white sm:px-6 lg:px-12">
                <div class="mx-auto max-w-[1360px]">
                    <p class="text-sm font-bold text-[#f1b84b]">{{ $community->activity_style }}</p>
                    <h1 class="mt-2 font-display text-3xl font-extrabold sm:text-4xl">{{ $community->name }}</h1>
                </div>
            </div>
        </section>

        <section class="border-b border-outline/30 bg-surface">
            <div class="mx-auto max-w-[1360px] px-4 py-7 sm:px-6 lg:px-12">
                <a href="{{ route('communities.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-muted transition hover:text-brand">
                    <span aria-hidden="true">→</span>
                    جميع المجتمعات
                </a>
                <div class="mt-5 flex flex-col items-start justify-between gap-5 md:flex-row md:items-center">
                    <div>
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full bg-forest/10 px-3 py-1 text-xs font-bold text-forest">{{ $community->region }}</span>
                            <span class="rounded-full bg-brand/10 px-3 py-1 text-xs font-bold text-brand">{{ $community->activity_style }}</span>
                        </div>
                        <p class="mt-3 max-w-3xl text-sm leading-8 text-muted">{{ $community->description }}</p>
                        <p class="mt-3 flex items-center gap-2 text-sm font-semibold text-muted">
                            <span class="size-2 rounded-full bg-forest" aria-hidden="true"></span>
                            {{ $community->members_count }} عضو
                        </p>
                    </div>

                    @auth
                        @if (auth()->user()->role === 'Adventurer')
                            @if ($isMember)
                                <form method="POST" action="{{ route('communities.memberships.destroy', [$community, $membership]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="min-h-12 rounded-control border border-outline/60 px-5 text-sm font-bold text-muted transition hover:border-danger/40 hover:bg-danger/5 hover:text-danger">مغادرة المجتمع</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('communities.memberships.store', $community) }}">
                                    @csrf
                                    <button type="submit" class="min-h-12 rounded-control bg-brand px-6 text-sm font-bold text-white transition hover:bg-brand-dark">انضم إلى المجتمع</button>
                                </form>
                            @endif
                        @else
                            <span class="rounded-control border border-outline/50 bg-canvas px-5 py-3 text-sm font-bold text-muted">متاح لمغامري يلا جو</span>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="min-h-12 inline-flex items-center rounded-control bg-brand px-6 text-sm font-bold text-white transition hover:bg-brand-dark">سجّل للدخول والانضمام</a>
                    @endauth
                </div>
            </div>
        </section>

        @session('success')
            <div class="mx-auto mt-5 max-w-[1360px] px-4 sm:px-6 lg:px-12">
                <div class="rounded-control border border-forest/20 bg-forest/10 px-4 py-3 text-sm font-semibold text-forest">{{ $value }}</div>
            </div>
        @endsession

        @if ($errors->any())
            <div class="mx-auto mt-5 max-w-[1360px] px-4 sm:px-6 lg:px-12">
                <div class="rounded-control border border-danger/20 bg-danger/5 px-4 py-3 text-sm text-danger">
                    @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            </div>
        @endif

        <div class="mx-auto grid max-w-[1360px] gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[minmax(0,1fr)_300px] lg:px-12">
            <section class="rounded-card border border-outline/35 bg-surface p-5 shadow-card sm:p-6">
                <div class="flex items-center justify-between border-b border-outline/30 pb-4">
                    <div>
                        <p class="text-xs font-bold text-brand">المحادثة</p>
                        <h2 class="mt-1 font-display text-xl font-bold text-ink">دردشة المجتمع</h2>
                    </div>
                    <span class="rounded-full bg-forest/10 px-3 py-1 text-xs font-bold text-forest">{{ $community->messages->count() }} رسالة</span>
                </div>

                @if ($isMember)
                    <form method="POST" action="{{ route('communities.messages.store', $community) }}" class="mt-5">
                        @csrf
                        <div class="flex items-end gap-3">
                            <label class="min-w-0 flex-1">
                                <span class="sr-only">اكتب رسالة للمجتمع</span>
                                <textarea name="body" required maxlength="2000" rows="2" placeholder="اكتب رسالة للمجتمع..."
                                          class="block w-full resize-none rounded-control border-outline/50 text-sm leading-7 text-ink placeholder:text-muted/60 focus:border-brand focus:ring-brand"></textarea>
                            </label>
                            <button type="submit" class="min-h-12 shrink-0 rounded-control bg-brand px-5 text-sm font-bold text-white transition hover:bg-brand-dark">إرسال</button>
                        </div>
                    </form>
                @endif

                <div class="mt-5 space-y-3">
                    @forelse ($community->messages as $message)
                        <article class="{{ $message->user_id === auth()->id() ? 'community-message community-message-own' : 'community-message' }} p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-bold text-ink">{{ $message->user->name }}</p>
                                <time class="shrink-0 text-xs text-muted/70">{{ $message->created_at->diffForHumans() }}</time>
                            </div>
                            <p class="mt-2 whitespace-pre-wrap text-sm leading-7 text-muted">{{ $message->body }}</p>
                        </article>
                    @empty
                        <div class="py-12 text-center">
                            <span class="mx-auto grid size-12 place-items-center rounded-full bg-forest/10 text-xl text-forest" aria-hidden="true">💬</span>
                            <p class="mt-3 text-sm font-semibold text-muted">
                                {{ $isMember ? 'ابدأ المحادثة بكتابة أول رسالة.' : 'انضم للمجتمع للمشاركة في المحادثة.' }}
                            </p>
                        </div>
                    @endforelse
                </div>

                @if (!$isMember)
                    @guest
                        <div class="mt-5 rounded-control border border-brand/15 bg-brand/5 px-4 py-4 text-center text-sm text-muted">
                            سجّل الدخول وانضم إلى المجتمع لتتمكن من إرسال الرسائل.
                            <a href="{{ route('login') }}" class="mr-1 font-bold text-brand hover:underline">تسجيل الدخول</a>
                        </div>
                    @endguest
                @endif
            </section>

            <aside class="h-fit rounded-card border border-outline/35 bg-surface p-5 shadow-card">
                <div class="flex items-center justify-between">
                    <h2 class="font-display text-lg font-bold text-ink">الأعضاء</h2>
                    <span class="rounded-full bg-canvas px-3 py-1 text-xs font-bold text-muted">{{ $community->members_count }}</span>
                </div>
                <div class="mt-4 max-h-[420px] space-y-3 overflow-y-auto">
                    @forelse ($community->members->take(20) as $member)
                        <div class="flex items-center gap-3">
                            <div class="grid size-10 shrink-0 place-items-center rounded-full bg-forest/10 font-display text-sm font-bold text-forest">{{ mb_substr($member->name, 0, 1) }}</div>
                            <span class="min-w-0 truncate text-sm font-semibold text-ink">{{ $member->name }}</span>
                        </div>
                    @empty
                        <div class="rounded-control bg-canvas px-4 py-6 text-center text-sm text-muted">كن أول من ينضم إلى المجتمع.</div>
                    @endforelse
                </div>
            </aside>
        </div>
    </main>
@endsection
