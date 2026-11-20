
@php
    $dashboardUser = $dashboardUser ?? null;

    $roleLabel = match ($dashboardUser?->role) {
        'Admin' => 'مسؤول النظام',
        'Activity_Organizer' => 'منظّم أنشطة',
        'Adventurer' => 'مغامر',
        default => 'نوع الحساب غير محدد',
    };

    $homeRoute = Route::has('home')
        ? 'home'
        : (
            app()->environment('local')
            && Route::has('home.preview')
                ? 'home.preview'
                : null
        );
@endphp

<div
    data-dashboard-user-area
    class="space-y-4"
>

    {{-- Account Information --}}
    @if ($dashboardUser)

        <div class="flex items-start gap-3">

            <span
                class="flex size-10 shrink-0
                       items-center justify-center
                       rounded-full bg-brand
                       font-display font-bold
                       text-white"
                aria-hidden="true"
            >
                {{ mb_substr(trim((string) $dashboardUser->name), 0, 1) }}
            </span>

            <div class="min-w-0 flex-1">

                <p
                    class="truncate text-sm
                           font-semibold text-white"
                >
                    {{ $dashboardUser->name }}
                </p>

                <p
                    class="mt-1 text-xs
                           text-white/70"
                >
                    {{ $roleLabel }}
                </p>

                <p
                    dir="ltr"
                    class="mt-1 truncate text-left
                           text-xs text-white/60"
                >
                    {{ $dashboardUser->email }}
                </p>

            </div>

        </div>

    @else

        {{-- Preview Mode --}}
        <div class="flex items-center gap-3">

            <span
                class="flex size-10 shrink-0
                       items-center justify-center
                       rounded-full bg-white/15
                       font-bold text-white"
                aria-hidden="true"
            >
                ؟
            </span>

            <div>
                <p
                    class="text-sm font-semibold
                           text-white"
                >
                    وضع المعاينة
                </p>

                <p class="text-xs text-white/65">
                    لا يوجد حساب مسجّل الدخول
                </p>
            </div>

        </div>

    @endif


    {{-- Return To Website --}}
    @if ($homeRoute)

        <a
            href="{{ route($homeRoute) }}"
            class="flex min-h-10 items-center
                   gap-2 rounded-control
                   px-3 py-2 text-sm text-white/80
                   transition-colors
                   hover:bg-white/10
                   hover:text-white
                   focus-visible:outline-2
                   focus-visible:outline-white"
        >
            <span aria-hidden="true">
                ↗
            </span>

            <span>
                العودة إلى الموقع
            </span>
        </a>

    @else

        <p class="px-3 text-xs text-white/60">
            رابط العودة إلى الموقع قيد التجهيز
        </p>

    @endif


    {{-- Optional Extra Sidebar Content --}}
    @hasSection('dashboard-sidebar-footer')

        <div class="border-t border-white/15 pt-4">

            @yield('dashboard-sidebar-footer')

        </div>

    @endif

</div>
