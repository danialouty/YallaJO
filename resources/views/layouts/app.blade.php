
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
        <div class="min-h-screen bg-gray-100 font-sans">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    @else
        @yield('body')
    @endif

    @include('components.chatbot')

    @stack('scripts')

</body>
</html>
