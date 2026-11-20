
@extends('layouts.app')

@section('body')

    <div class="flex min-h-screen flex-col">

        <a
            href="#main-content"
            class="sr-only focus:not-sr-only
                   focus:fixed focus:right-4 focus:top-4
                   focus:z-50 focus:rounded-control
                   focus:bg-brand focus:px-4 focus:py-2
                   focus:text-white"
        >
            الانتقال إلى المحتوى الرئيسي
        </a>

        @include('partials.site.navbar')

        <main
            id="main-content"
            class="flex-1"
            tabindex="-1"
        >
            @yield('content')
        </main>

        @include('partials.site.footer')

    </div>

@endsection
