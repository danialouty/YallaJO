
@extends('layouts.app')

@section('body')

    @php
        /*
         * Display-only navigation.
         * No authentication logic is changed here.
         */
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
        data-auth-layout
        class="grid min-h-dvh bg-canvas
               lg:grid-cols-2"
    >

        {{-- Accessibility --}}
        <a
            href="#auth-content"
            class="sr-only focus:not-sr-only
                   focus:fixed focus:right-4
                   focus:top-4 focus:z-50
                   focus:rounded-control
                   focus:bg-brand focus:px-4
                   focus:py-2 focus:text-white"
        >
            الانتقال إلى نموذج الحساب
        </a>


        {{-- Right Side: YallaJO Branding --}}
        <aside
            class="relative hidden overflow-hidden
                   bg-forest text-white lg:flex
                   lg:min-h-dvh lg:flex-col
                   lg:justify-between lg:p-12"
            aria-label="عن يلا جو"
        >

            {{-- Decorative Shapes --}}
            <div
                class="pointer-events-none absolute
                       -left-20 -top-20 size-72
                       rounded-full border
                       border-white/15"
                aria-hidden="true"
            ></div>

            <div
                class="pointer-events-none absolute
                       bottom-12 right-12 size-44
                       rounded-full bg-white/5"
                aria-hidden="true"
            ></div>


            {{-- Branding --}}
            <div class="relative z-10 flex items-center gap-3">

                <span
                    class="flex size-12 items-center
                           justify-center rounded-card
                           bg-brand font-display
                           text-2xl font-bold"
                    aria-hidden="true"
                >
                    ي
                </span>

                <div>
                    <p class="font-display text-2xl font-bold">
                        يلا جو
                    </p>

                    <p
                        class="text-sm text-white/70"
                        lang="en"
                        dir="ltr"
                    >
                        YallaJO
                    </p>
                </div>

            </div>


            {{-- Branding Content --}}
            <div class="relative z-10 max-w-xl">

                <p
                    class="mb-6 inline-flex
                           rounded-full border
                           border-white/25
                           bg-white/10
                           px-4 py-2 text-sm"
                >
                    اكتشف جمال الأردن
                </p>

                <h2
                    class="font-display text-4xl
                           font-extrabold leading-tight
                           xl:text-5xl"
                >
                    مغامرتك القادمة

                    <span class="mt-3 block text-orange-200">
                        تبدأ من هنا
                    </span>
                </h2>

                <p
                    class="mt-6 max-w-md
                           text-base leading-9
                           text-white/85"
                >
                    اكتشف المغامرات والأنشطة
                    والتجارب المميزة في الأردن،
                    وابدأ رحلتك مع يلا جو.
                </p>

            </div>


            {{-- Branding Footer --}}
            <div class="relative z-10">
                <p class="text-sm text-white/65">
                    © {{ now()->year }} YallaJO
                </p>
            </div>

        </aside>


        {{-- Left Side: Authentication Content --}}
        <div
            class="flex min-h-dvh min-w-0
                   flex-col bg-canvas"
        >

            {{-- Top Bar --}}
            <header
                class="flex items-center
                       justify-between gap-4
                       px-5 py-5 sm:px-10"
            >

                <div class="flex items-center gap-2 lg:hidden">

                    <span
                        class="flex size-9 items-center
                               justify-center
                               rounded-control bg-brand
                               font-display font-bold
                               text-white"
                        aria-hidden="true"
                    >
                        ي
                    </span>

                    <span class="font-display font-bold text-ink">
                        يلا جو
                    </span>

                </div>

                <div class="mr-auto">

                    @if ($homeRoute)

                        <a
                            href="{{ route($homeRoute) }}"
                            class="text-sm font-semibold
                                   text-brand hover:underline
                                   focus-visible:outline-2
                                   focus-visible:outline-brand"
                        >
                            العودة إلى الموقع
                        </a>

                    @else

                        <span class="text-sm text-muted">
                            YallaJO
                        </span>

                    @endif

                </div>

            </header>


            {{-- Authentication Form Area --}}
            <main
                id="auth-content"
                class="flex w-full flex-1
                       items-center justify-center
                       px-5 py-10 sm:px-10"
                tabindex="-1"
            >

                <div class="w-full max-w-md">

                    {{-- Page Heading --}}
                    <div class="mb-8 space-y-3">

                        <p
                            class="text-sm font-semibold
                                   text-brand"
                        >
                            YallaJO Account
                        </p>

                        <h1
                            id="auth-heading"
                            class="font-display
                                   text-3xl font-bold
                                   text-ink sm:text-4xl"
                        >
                            @yield(
                                'auth-heading',
                                'مرحبًا بك في يلا جو'
                            )
                        </h1>

                        <p
                            class="text-sm leading-7
                                   text-muted"
                        >
                            @yield(
                                'auth-description',
                                'أكمل بيانات حسابك للمتابعة.'
                            )
                        </p>

                    </div>


                    {{-- Page-Specific Form --}}
                    <section aria-labelledby="auth-heading">

                        @yield('content')

                    </section>


                    {{-- Optional Links Below Form --}}
                    @hasSection('auth-footer')

                        <div
                            class="mt-8 border-t
                                   border-outline/30 pt-6
                                   text-center text-sm
                                   text-muted"
                        >
                            @yield('auth-footer')
                        </div>

                    @endif

                </div>

            </main>


            {{-- Mobile / Form Footer --}}
            <footer
                class="px-5 py-5
                       text-center text-xs
                       text-muted sm:px-10"
            >
                © {{ now()->year }} يلا جو —
                جميع الحقوق محفوظة.
            </footer>

        </div>

    </div>

@endsection
