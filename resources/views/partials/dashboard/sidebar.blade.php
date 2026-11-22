@php
    /*
     * Dashboard appearance only.
     * Authorization must be handled by the backend.
     */

    $dashboardTheme = $dashboardTheme ?? 'organizer';

    $organizerMenu = [
        [
            'label' => 'لوحة التحكم',
            'route' => 'organizer.dashboard',
            'icon' => 'dashboard',
        ],
        [
            'label' => 'المرشدون',
            'route' => 'guides.index',
            'icon' => 'guides',
        ],
        [
            'label' => 'المغامرات',
            'route' => 'adventures.index',
            'icon' => 'adventures',
        ],
        [
            'label' => 'الحجوزات',
            'route' => 'bookings.index',
            'active' => 'bookings.*',
            'icon' => 'bookings',
        ],
        [
            'label' => 'مواعيد المغامرات',
            'route' => 'adventure-schedules.index',
            'icon' => 'bookings',
        ],

    ];

    $adminMenu = [
        [
            'label' => 'نظرة عامة على النظام',
            'route' => 'admin.dashboard',
            'icon' => 'dashboard',
        ],
        [
            'label' => 'إدارة المنظّمين',
            'route' => 'admin.organizers.index',
            'active' => 'admin.organizers.*',
            'icon' => 'approval',
        ],
        [
            'label' => 'إدارة المستخدمين والأدوار',
            'route' => 'admin.users.index',
            'active' => 'admin.users.*',
            'icon' => 'community',
        ],
        [
            'label' => 'المغامرات والتصنيفات',
            'route' => 'admin.adventures.index',
            'active' => 'admin.adventures.*',
            'icon' => 'adventures',
        ],
        [
            'label' => 'الحجوزات والعمولات',
            'route' => 'admin.bookings.index',
            'active' => 'admin.bookings.*',
            'icon' => 'payments',
        ],
        [
            'label' => 'المدفوعات',
            'route' => 'admin.payments.index',
            'active' => 'admin.payments.*',
            'icon' => 'payments',
        ],
        [
            'label' => 'التقييمات',
            'route' => 'admin.reviews.index',
            'active' => 'admin.reviews.*',
            'icon' => 'reports',
        ],
        [
            'label' => 'التقارير والشكاوى',
            'route' => 'admin.reports.index',
            'icon' => 'reports',
        ],
    ];

    $menu = $dashboardTheme === 'admin'
        ? $adminMenu
        : $organizerMenu;

    /*
     * SVG icons shared by both dashboards.
     */
    $icons = [
        'dashboard' =>
            'M3 3h7v7H3z M14 3h7v7h-7z M3 14h7v7H3z M14 14h7v7h-7z',

        'adventures' =>
            'M3 20 9 9l4 7 3-4 5 8H3Z',

        'guides' =>
            'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2 M9 11a4 4 0 1 0 0-8a4 4 0 0 0 0 8 M19 8v6 M16 11h6',

        'add' =>
            'M12 5v14 M5 12h14',

        'bookings' =>
            'M8 3v4 M16 3v4 M4 10h16 M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z',

        'community' =>
            'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2 M9 11a4 4 0 1 0 0-8a4 4 0 0 0 0 8 M22 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75',

        'payments' =>
            'M3 7h18v13H3z M3 11h18 M7 16h4',

        'approval' =>
            'M12 3a9 9 0 1 0 0 18a9 9 0 0 0 0-18 M8 12l3 3 5-6',

        'reports' =>
            'M6 3h9l4 4v14H6z M14 3v5h5 M9 13h6 M9 17h6',
    ];
@endphp

{{-- Sidebar Navigation Heading --}}
<p
    class="mb-4 px-3 text-xs font-semibold
           tracking-wide text-white/60"
>
    القائمة الرئيسية
</p>

{{-- Sidebar Navigation Items --}}
<ul class="space-y-2">

    @foreach ($menu as $item)

        @php
            $routeExists = Route::has($item['route']);

            $isActive = $routeExists
                && request()->routeIs($item['active'] ?? $item['route']);

            $itemClasses = '
                flex min-h-11 items-center gap-3
                rounded-control px-3 py-2.5
                text-sm transition-colors
            ';

            $stateClasses = $isActive
                ? 'bg-white/20 font-bold text-white'
                : 'text-white/85 hover:bg-white/10 hover:text-white';
        @endphp

        <li>

            @if ($routeExists)

                <a
                    href="{{ route($item['route']) }}"

                    @if ($isActive)
                        aria-current="page"
                    @endif

                    class="{{ $itemClasses }} {{ $stateClasses }}"
                >

                    <svg
                        class="size-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path
                            d="{{ $icons[$item['icon']] }}"
                        />
                    </svg>

                    <span class="flex-1">
                        {{ $item['label'] }}
                    </span>

                </a>

            @else

                <span
                    class="flex min-h-11 items-center
                           gap-3 rounded-control
                           px-3 py-2.5 text-sm
                           text-white/55"
                    aria-disabled="true"
                    title="الصفحة قيد التطوير"
                >

                    <svg
                        class="size-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path
                            d="{{ $icons[$item['icon']] }}"
                        />
                    </svg>

                    <span class="min-w-0 flex-1">
                        {{ $item['label'] }}
                    </span>

                    <span class="text-[10px] text-white/45">
                        قريبًا
                    </span>

                </span>

            @endif

        </li>

    @endforeach

</ul>
