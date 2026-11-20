<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>يلا جو | YallaJo</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-[#F8F9FA] text-gray-800 antialiased">

    <div class="min-h-screen flex flex-col lg:flex-row">


        <div class="lg:w-5/12 bg-[#2D5041] text-white p-8 lg:p-12 flex flex-col justify-between relative overflow-hidden">
            <div>
                <a href="/" class="inline-flex items-center gap-2 text-2xl font-extrabold tracking-wide">
                    <span>يلا جو | YallaJo</span>
                </a>
                <span class="block mt-2 text-xs text-emerald-200">منصة المغامرات والسياحة البيئية في الأردن</span>
            </div>

            <div class="my-10 lg:my-0 space-y-5">
                <span class="inline-block px-3 py-1 bg-white/10 rounded-full text-xs font-semibold text-emerald-100">
                    بوابتك لتجارب الطبيعة في الأردن
                </span>
                <h1 class="text-3xl lg:text-4xl font-extrabold leading-snug">
                    اكتشف مسارات الأردن ومجتمعات المغامرة
                </h1>
                <p class="text-sm text-white/80 leading-relaxed">
                    سجّل رحلاتك، تخييمك الجبلي، وتجاربك الريفية في وادي رم، الموجب، وعجلون مع مرشدين معتمدين ومغامرين يشبهونك.
                </p>

                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-white/15 text-center">
                    <div class="bg-white/10 rounded-xl p-3">
                        <p class="font-extrabold text-lg text-white">+50</p>
                        <p class="text-[11px] text-emerald-100">مسار ومغامرة</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-3">
                        <p class="font-extrabold text-lg text-white">100%</p>
                        <p class="text-[11px] text-emerald-100">مرشدون معتمدون</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-3">
                        <p class="font-extrabold text-lg text-white">24/7</p>
                        <p class="text-[11px] text-emerald-100">حجز فوري وآمن</p>
                    </div>
                </div>
            </div>

            <div class="text-xs text-white/60">
                © {{ date('Y') }} يلا جو (YallaJo) - جميع الحقوق محفوظة
            </div>
        </div>


        <div class="flex-1 flex items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-100 p-7 sm:p-9">
                {{ $slot }}
            </div>
        </div>

    </div>

</body>
</html>