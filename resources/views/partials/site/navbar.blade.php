
@php
    $navigation = [
        [
            'label' => 'استكشف المغامرات',
            'route' => 'adventures.index',
        ],
        [
            'label' => 'المجتمعات',
            'route' => 'communities.index',
        ],
        [
            'label' => 'نظّم رحلة',
            'route' => 'organizers.create',
        ],
        [
            'label' => 'عن يلا جو',
            'route' => 'about',
        ],
    ];
@endphp

<header
    class="sticky top-0 z-50 border-b border-outline/30
           bg-surface/95 shadow-sm backdrop-blur-md"
>
    <div
        class="mx-auto flex h-20 max-w-[1360px]
               items-center justify-between gap-4
               px-4 sm:px-6 lg:px-12"
    >

        {{-- Brand --}}
        <div class="flex shrink-0 items-center gap-2">

            <span
                class="flex size-10 items-center justify-center
                       rounded-full bg-brand font-display
                       text-xl font-bold text-white"
                aria-hidden="true"
            >
                ي
            </span>

            <div class="flex flex-col leading-tight">
                <span class="font-display text-lg font-bold text-ink">
                    يلا جو
                </span>

                <span
                    lang="en"
                    dir="ltr"
                    class="text-xs text-muted"
                >
                    YallaJO
                </span>
            </div>
        </div>

        {{-- Desktop Navigation --}}
        <nav
            class="hidden items-center gap-2 lg:flex"
            aria-label="القائمة الرئيسية"
        >
            @foreach ($navigation as $item)

                @if (Route::has($item['route']))
                    <a
                        href="{{ route($item['route']) }}"
                        @if (request()->routeIs($item['route']))
                            aria-current="page"
                        @endif
                        class="rounded-control px-3 py-2 text-sm
                               transition-colors
                               {{ request()->routeIs($item['route'])
                                    ? 'bg-brand/10 font-bold text-brand'
                                    : 'text-ink hover:bg-brand/5 hover:text-brand' }}"
                    >
                        {{ $item['label'] }}
                    </a>
                @else
                    <span
                        class="cursor-not-allowed px-3 py-2
                               text-sm text-muted/60"
                        aria-disabled="true"
                        title="الصفحة قيد التطوير"
                    >
                        {{ $item['label'] }}
                    </span>
                @endif

            @endforeach
        </nav>

        {{-- Desktop Account Actions --}}
        <div class="hidden items-center gap-3 lg:flex">

            <span
                class="rounded-full border border-outline/40
                       px-3 py-1 text-xs text-muted"
                title="اللغة الإنجليزية غير مفعلة بعد"
            >
                EN
            </span>

            @guest
                @if (Route::has('login'))
                    <a
                        href="{{ route('login') }}"
                        class="text-sm text-ink hover:text-brand"
                    >
                        تسجيل الدخول
                    </a>
                @else
                    <span class="text-sm text-muted/60">
                        تسجيل الدخول
                    </span>
                @endif

                @if (Route::has('register'))
                    <a
                        href="{{ route('register') }}"
                        class="rounded-control bg-brand px-4 py-2
                               text-sm font-semibold text-white
                               transition-colors hover:bg-brand-dark"
                    >
                        حساب جديد
                    </a>
                @else
                    <span
                        class="rounded-control bg-brand/50
                               px-4 py-2 text-sm text-white"
                    >
                        حساب جديد
                    </span>
                @endif
            @endguest

            @auth
                <span class="text-sm font-semibold text-ink">
                    {{ auth()->user()->name }}
                </span>

                @if (
                    auth()->user()->role === 'Adventurer'
                    && Route::has('adventurer.show')
                )
                    <a
                        href="{{ route('adventurer.show') }}"
                        class="rounded-control bg-brand px-4 py-2
                               text-sm font-semibold text-white"
                    >
                        ملفي الشخصي
                    </a>
                @endif
            @endauth

        </div>

        {{-- Mobile Navigation --}}
        <details class="relative lg:hidden">

            <summary
                class="flex cursor-pointer list-none
                       items-center gap-2 rounded-control
                       border border-outline/40 px-3 py-2
                       text-sm text-ink"
                aria-label="فتح قائمة التنقل"
            >
                <span aria-hidden="true">☰</span>
                <span>القائمة</span>
            </summary>

            <nav
                class="absolute left-0 top-full z-50 mt-3
                       flex w-72 max-w-[90vw] flex-col
                       gap-1 rounded-card border
                       border-outline/30 bg-surface p-4
                       shadow-card"
                aria-label="القائمة الرئيسية للهاتف"
            >
                @foreach ($navigation as $item)

                    @if (Route::has($item['route']))
                        <a
                            href="{{ route($item['route']) }}"
                            class="rounded-control px-3 py-2
                                   text-sm text-ink hover:bg-brand/10"
                        >
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span
                            class="px-3 py-2 text-sm text-muted/60"
                            aria-disabled="true"
                        >
                            {{ $item['label'] }}
                        </span>
                    @endif

                @endforeach

                <div class="my-2 border-t border-outline/30"></div>

                @guest
                    @if (Route::has('login'))
                        <a
                            href="{{ route('login') }}"
                            class="px-3 py-2 text-sm text-ink"
                        >
                            تسجيل الدخول
                        </a>
                    @else
                        <span class="px-3 py-2 text-sm text-muted/60">
                            تسجيل الدخول
                        </span>
                    @endif

                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="rounded-control bg-brand px-3 py-2
                                   text-center text-sm text-white"
                        >
                            حساب جديد
                        </a>
                    @else
                        <span class="px-3 py-2 text-sm text-muted/60">
                            حساب جديد
                        </span>
                    @endif
                @endguest

                @auth
                    <span class="px-3 py-2 font-semibold text-ink">
                        {{ auth()->user()->name }}
                    </span>

                    @if (
                        auth()->user()->role === 'Adventurer'
                        && Route::has('adventurer.show')
                    )
                        <a
                            href="{{ route('adventurer.show') }}"
                            class="rounded-control bg-brand px-3 py-2
                                   text-center text-sm text-white"
                        >
                            ملفي الشخصي
                        </a>
                    @endif
                @endauth

            </nav>
        </details>

    </div>
</header>
