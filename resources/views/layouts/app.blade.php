
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
        @yield('title', 'YallaJO | مغامرات الأردن')
    </title>

    <meta
        name="description"
        content="@yield('meta_description', 'اكتشف المغامرات والأنشطة في الأردن')"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')
</head>

<body class="min-h-screen antialiased">

    @yield('body')

    @stack('scripts')

</body>
</html>
