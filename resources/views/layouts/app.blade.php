
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', isset($slot) ? config('app.name', 'Laravel') : 'YallaJO | مغامرات الأردن')
    </title>

    <meta
        name="description"
        content="@yield('meta_description', 'اكتشف المغامرات والأنشطة في الأردن')"
    >

    @isset($slot)
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @endisset

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')
</head>

<body class="min-h-screen antialiased">

    @if (isset($slot))
        @php
            $organizerWorkspace = auth()->check()
                && auth()->user()->isApprovedOrganizer()
                && request()->routeIs('adventures.*', 'adventure-schedules.*', 'guides.*', 'guide-availabilities.*');
        @endphp
        @if ($organizerWorkspace)
            <div data-dashboard-layout class="min-h-screen bg-canvas text-ink">
                @include('partials.dashboard.header', ['dashboardUser' => auth()->user()])
                <div class="min-h-screen lg:flex">
                    <aside id="dashboard-sidebar" aria-label="القائمة الجانبية" class="hidden bg-forest text-white lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-64 lg:shrink-0 lg:flex-col lg:overflow-y-auto">
                        <div class="border-b border-white/15 px-5 py-6">
                            <p class="font-display text-lg font-bold">يلا جو</p>
                            <p class="mt-1 text-xs text-white/70" dir="ltr">YallaJO</p>
                            <p class="mt-4 text-xs text-white/70">مساحة المنظّم</p>
                        </div>
                        <nav class="flex-1 px-3 py-6" aria-label="قائمة لوحة التحكم">
                            @include('partials.dashboard.sidebar', ['dashboardTheme' => 'organizer'])
                        </nav>
                        <div class="border-t border-white/15 px-4 py-5">
                            @include('partials.dashboard.user-area', ['dashboardUser' => auth()->user()])
                        </div>
                    </aside>
                    @include('partials.dashboard.mobile-sidebar', ['dashboardTheme' => 'organizer', 'dashboardUser' => auth()->user()])
                    <main id="dashboard-main" class="min-w-0 flex-1" tabindex="-1">{{ $slot }}</main>
                </div>
            </div>
        @else
            <div class="min-h-screen bg-gray-100 font-sans">
                @include('layouts.navigation')
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">{{ $header }}</div>
                    </header>
                @endisset
                <main>{{ $slot }}</main>
            </div>
        @endif
    @else
        @yield('body')
    @endif

    @stack('scripts')

</body>
</html>
