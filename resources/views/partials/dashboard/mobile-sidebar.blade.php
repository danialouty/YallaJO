@php
    $dashboardTheme = $dashboardTheme ?? 'organizer';
    $dashboardUser = $dashboardUser ?? null;

    $mobileSidebarColor = $dashboardTheme === 'admin'
        ? 'bg-ink'
        : 'bg-forest';
@endphp

<dialog
    id="dashboard-mobile-menu"
    aria-labelledby="dashboard-mobile-menu-title"
    aria-modal="true"
    class="fixed inset-y-0 right-0 left-auto
           m-0 h-dvh max-h-dvh
           w-[min(20rem,90vw)] max-w-none
           overflow-hidden border-0 p-0
           text-white shadow-2xl
           backdrop:bg-ink/70
           lg:hidden
           {{ $mobileSidebarColor }}"
>
    <div class="flex h-full flex-col">

        {{-- Mobile Sidebar Header --}}
        <div
            class="flex shrink-0 items-center
                   justify-between gap-3
                   border-b border-white/15
                   px-5 py-5"
        >

            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="الانتقال إلى الصفحة الرئيسية">
                <x-application-logo class="block size-11 shrink-0" aria-hidden="true" />
                <div>
                    <h2 id="dashboard-mobile-menu-title" class="font-display text-xl font-bold">يلا جو</h2>
                    <p class="mt-1 text-xs text-white/70" lang="en" dir="ltr">YallaJO Dashboard</p>
                </div>
            </a>

            {{-- Close Button --}}
            <button
                type="button"
                data-dashboard-menu-close
                autofocus
                aria-label="إغلاق القائمة الجانبية"
                class="flex size-10 shrink-0
                       items-center justify-center
                       rounded-control
                       bg-white/10 text-white
                       transition-colors
                       hover:bg-white/20
                       focus-visible:outline-2
                       focus-visible:outline-white"
            >
                <span
                    aria-hidden="true"
                    class="text-2xl leading-none"
                >
                    &times;
                </span>
            </button>

        </div>


        {{-- Reuse Existing Sidebar Navigation --}}
        <nav
            class="min-h-0 flex-1
                   overflow-y-auto px-3 py-6"
            aria-label="قائمة لوحة التحكم للهاتف"
        >

            @include('partials.dashboard.sidebar', [
                'dashboardTheme' => $dashboardTheme
            ])

        </nav>


        {{-- Reuse Existing User Area --}}
        <div
            class="shrink-0 border-t
                   border-white/15 px-4 py-5"
        >

            @include('partials.dashboard.user-area', [
                'dashboardUser' => $dashboardUser
            ])

        </div>

    </div>
</dialog>