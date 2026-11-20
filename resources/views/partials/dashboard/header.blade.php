@php
    $dashboardUser = $dashboardUser ?? null;
@endphp

<header data-dashboard-header class="sticky top-0 z-40
           border-b border-outline/30
           bg-surface/95 backdrop-blur-md">
    <div class="flex min-h-20 flex-wrap
               items-center justify-between
               gap-4 px-4 py-3
               sm:px-6 lg:px-8">


        {{-- Mobile Sidebar Toggle --}}
        <button id="dashboard-mobile-menu-trigger" type="button" data-dashboard-menu-open
            aria-controls="dashboard-mobile-menu" aria-expanded="false" aria-haspopup="dialog"
            aria-label="فتح القائمة الجانبية" class="flex size-11 shrink-0
           items-center justify-center
           rounded-control border
           border-outline/40
           bg-surface text-ink
           transition-colors
           hover:bg-brand/5
           focus-visible:outline-2
           focus-visible:outline-brand
           lg:hidden">
            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" aria-hidden="true">
                <path d="M4 6h16 M4 12h16 M4 18h16" />
            </svg>
        </button>

        {{-- Page Information --}}
        <div class="min-w-0 flex-1">

            <p class="mb-1 text-xs text-muted">
                <span lang="en" dir="ltr">
                    YallaJO
                </span>

                <span aria-hidden="true"> / </span>

                @yield(
                    'dashboard-name',
                    'بوابة التحكم'
                )
            </p>

            <h1 class="font-display text-lg
                       font-bold text-ink
                       sm:text-xl">
                @yield(
                    'dashboard-title',
                    'لوحة التحكم'
                )
            </h1>

        </div>


        {{-- Header Actions & User --}}
        <div class="flex shrink-0
                   items-center gap-3">

            {{-- Optional Page Actions --}}
            @hasSection('dashboard-header-actions')

                <div class="flex items-center gap-2">

                    @yield('dashboard-header-actions')

                </div>

            @endif


            {{-- Authenticated User --}}
            @if ($dashboardUser)

                <div class="hidden max-w-40
                               text-left sm:block">
                    <p class="truncate text-sm
                                   font-semibold text-ink">
                        {{ $dashboardUser->name }}
                    </p>

                    <p class="text-xs text-muted">
                        حساب مسجّل الدخول
                    </p>
                </div>

                <span class="flex size-10 shrink-0
                               items-center justify-center
                               rounded-full bg-brand
                               font-display font-bold
                               text-white" aria-hidden="true">
                    {{ mb_substr(trim((string) $dashboardUser->name), 0, 1) }}
                </span>

            @else

                {{-- Local Preview / Guest --}}
                <span class="rounded-full
                               bg-canvas px-3 py-2
                               text-xs text-muted">
                    وضع المعاينة
                </span>

                <span class="flex size-10 shrink-0
                               items-center justify-center
                               rounded-full bg-outline/30
                               font-bold text-muted" aria-hidden="true">
                    ؟
                </span>

            @endif

        </div>

    </div>
</header>