@extends('layouts.app')

@section('body')

    @php
        // Visual theme only, not authorization.
        $dashboardTheme = trim(
            $__env->yieldContent(
                'dashboard-theme',
                'organizer'
            )
        );

        $sidebarColor = $dashboardTheme === 'admin'
            ? 'bg-ink'
            : 'bg-forest';
        $dashboardUser = auth()->user();

    @endphp

    <div data-dashboard-layout class="min-h-screen bg-canvas text-ink">

        {{-- Accessibility: Skip Navigation --}}
        <a href="#dashboard-main" class="sr-only focus:not-sr-only
                           focus:fixed focus:right-4 focus:top-4
                           focus:z-50 focus:rounded-control
                           focus:bg-brand focus:px-4 focus:py-2
                           focus:text-white">
            الانتقال إلى محتوى لوحة التحكم
        </a>

        <div class="min-h-screen lg:flex">

            {{-- Desktop Sidebar --}}
            <aside id="dashboard-sidebar" aria-label="القائمة الجانبية" class="hidden text-white
                               lg:sticky lg:top-0 lg:flex
                               lg:h-screen lg:w-64
                               lg:shrink-0 lg:flex-col
                               lg:overflow-y-auto
                               {{ $sidebarColor }}">

                {{-- Dashboard Brand --}}
                <div class="border-b border-white/15
                                   px-5 py-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="الانتقال إلى الصفحة الرئيسية">
                        <x-application-logo class="block size-11 shrink-0" aria-hidden="true" />

                        <div>
                            <p class="font-display
                                               text-lg font-bold">
                                يلا جو
                            </p>

                            <p class="text-xs text-white/70" lang="en" dir="ltr">
                                YallaJO
                            </p>
                    </div>
                    </a>

                    <p class="mt-4 text-xs
                                       text-white/70">
                        @yield(
                            'dashboard-name',
                            'بوابة التحكم'
                        )
                    </p>
                </div>

                {{-- Role-Based Navigation --}}
                <nav class="flex-1 space-y-1
                                   px-3 py-6" aria-label="قائمة لوحة التحكم">
                    @include('partials.dashboard.sidebar', [
                        'dashboardTheme' => $dashboardTheme
                    ])
                </nav>

                {{-- Sidebar Footer --}}
                <div class="border-t border-white/15
                                   px-4 py-5">
                    @include('partials.dashboard.user-area', [
                        'dashboardUser' => $dashboardUser
                    ])
                </div>

            </aside>

            {{-- Mobile Sidebar --}}
            @include('partials.dashboard.mobile-sidebar', [
                'dashboardTheme' => $dashboardTheme,
                'dashboardUser' => $dashboardUser
            ])

            {{-- Dashboard Main Area --}}
            <div class="flex min-h-screen
                               min-w-0 flex-1 flex-col">

                {{-- Dashboard Header --}}
                @include('partials.dashboard.header', [
                    'dashboardUser' => $dashboardUser
                ])

                {{-- Dashboard Page Content --}}
                <main id="dashboard-main" class="w-full flex-1
                                   px-4 py-6
                                   sm:px-6 lg:px-8" tabindex="-1">

                    <div class="mx-auto w-full
                                       max-w-[1360px]">
                        @yield('content')
                    </div>

                </main>

            </div>

        </div>

    </div>

@endsection
