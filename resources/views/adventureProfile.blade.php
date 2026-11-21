<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $adventure->title }} | يلا جو</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans text-slate-800">
    <nav class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="text-2xl font-black text-emerald-900">يلا جو</a>
            <a href="{{ route('adventuresAll.index') }}" class="text-sm font-bold text-orange-700 hover:text-orange-900">← العودة إلى المغامرات</a>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <section class="grid h-56 grid-cols-3 gap-2 overflow-hidden rounded-2xl sm:h-80">
            <div class="col-span-1 grid grid-rows-2 gap-2">
                @forelse($adventure->images->skip(1)->take(2) as $image)
                    <img class="h-full w-full object-cover" src="{{ asset('images/' . $image->image_path) }}" alt="{{ $adventure->title }}">
                @empty
                    <div class="row-span-2 bg-slate-200"></div>
                @endforelse
            </div>
            <div class="col-span-2 min-w-0">
                @if($adventure->images->isNotEmpty())
                    <img class="h-full w-full object-cover" src="{{ asset('images/' . $adventure->images->first()->image_path) }}" alt="{{ $adventure->title }}">
                @else
                    <img class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1470252649378-9c29740c9fa8?w=1000" alt="Adventure">
                @endif
            </div>
        </section>

        <div class="mt-6 grid items-start gap-6 lg:grid-cols-[320px_minmax(0,1fr)]">
            <aside class="space-y-4 lg:sticky lg:top-5">
                <div class="rounded-2xl bg-white p-5 shadow-sm">
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <span class="text-sm text-slate-500">السعر للشخص</span>
                        <strong class="text-2xl font-black text-orange-700">{{ number_format($adventure->price, 2) }} <small class="text-xs">د.أ</small></strong>
                    </div>
                    <form action="#" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="adventure_id" value="{{ $adventure->id }}">
                        <label for="bookingDate" class="mb-2 block text-sm font-bold">تاريخ الرحلة</label>
                        <input id="bookingDate" type="date" name="booking_date" min="{{ date('Y-m-d') }}" required class="w-full rounded-lg border-slate-300 bg-slate-50 text-sm focus:border-emerald-700 focus:ring-emerald-700">

                        <span class="mb-2 mt-5 block text-sm font-bold">عدد الأشخاص</span>
                        <div class="flex items-center justify-between rounded-lg bg-slate-100 p-2">
                            <button type="button" id="increase" class="grid h-9 w-9 place-items-center rounded-md bg-white text-xl font-bold text-emerald-900 hover:bg-emerald-50" aria-label="زيادة عدد الأشخاص">+</button>
                            <strong id="peopleCount" class="text-lg">2</strong>
                            <button type="button" id="decrease" class="grid h-9 w-9 place-items-center rounded-md bg-white text-xl font-bold text-emerald-900 hover:bg-emerald-50" aria-label="تقليل عدد الأشخاص">−</button>
                        </div>
                        <input type="hidden" name="people" id="peopleInput" value="2">

                        <div class="mt-5 flex items-center justify-between border-t border-slate-200 pt-4 text-sm font-bold">
                            <span>الإجمالي التقريبي</span>
                            <span class="text-xl text-orange-700"><span id="totalPrice">{{ number_format($adventure->price * 2, 2) }}</span> د.أ</span>
                        </div>
                        <button type="button" class="mt-5 w-full rounded-lg bg-orange-700 px-4 py-3 font-extrabold text-white hover:bg-orange-800" onclick="alert('واجهة الحجز جاهزة، اربطها بمسار الحجز عند تفعيله.')">🔒 تأكيد الحجز الآن</button>
                        <p class="mt-3 text-center text-xs text-slate-500">السعر النهائي يعتمد على تفاصيل الحجز</p>
                    </form>
                </div>
                <div class="space-y-3 rounded-2xl bg-white p-5 text-sm shadow-sm">
                    <p>🟢 <strong>تجربة محلية أصيلة</strong><br><span class="text-slate-500">اكتشف الثقافة والمجتمع المحلي.</span></p>
                    <p>🟠 <strong>منظم موثوق</strong><br><span class="text-slate-500">تواصل مع منظم المغامرة قبل الحجز.</span></p>
                </div>
            </aside>

            <section class="space-y-4">
                <div class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="mb-3 flex flex-wrap gap-2">
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-900">✦ تجربة محلية</span>
                                <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-800">{{ $adventure->category }}</span>
                            </div>
                            <h1 class="text-2xl font-black leading-relaxed text-slate-900">{{ $adventure->title }}</h1>
                            <p class="mt-1 text-sm text-slate-500">📍 {{ $adventure->location }}، الأردن</p>
                        </div>
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-sm font-bold text-amber-700">⭐ تجربة مميزة</span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <img class="h-12 w-12 rounded-full bg-slate-200 object-cover" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100" alt="منظم المغامرة">
                        <div>
                            <h2 class="font-bold">{{ $adventure->organizer_name ?? 'منظم الرحلة' }}</h2>
                            <p class="mt-1 text-xs text-slate-500">منظم المغامرة · الأردن</p>
                        </div>
                    </div>
                    <a href="#" class="rounded-lg bg-indigo-50 px-4 py-2 text-xs font-bold text-emerald-900 hover:bg-indigo-100">تواصل مع المنظم</a>
                </div>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <div class="rounded-xl bg-white p-4 text-center shadow-sm"><span class="mx-auto mb-2 grid h-9 w-9 place-items-center rounded-lg bg-emerald-100">⏱</span><small class="text-xs text-slate-500">مدة المغامرة</small><strong class="mt-1 block text-sm">{{ $adventure->duration_hours }} ساعات</strong></div>
                    <div class="rounded-xl bg-white p-4 text-center shadow-sm"><span class="mx-auto mb-2 grid h-9 w-9 place-items-center rounded-lg bg-emerald-100">🥾</span><small class="text-xs text-slate-500">مستوى الصعوبة</small><strong class="mt-1 block text-sm">{{ $adventure->difficulty_level ?? 'متوسط' }}</strong></div>
                    <div class="rounded-xl bg-white p-4 text-center shadow-sm"><span class="mx-auto mb-2 grid h-9 w-9 place-items-center rounded-lg bg-emerald-100">👥</span><small class="text-xs text-slate-500">السعة القصوى</small><strong class="mt-1 block text-sm">{{ $adventure->capacity }} شخص</strong></div>
                    <div class="rounded-xl bg-white p-4 text-center shadow-sm"><span class="mx-auto mb-2 grid h-9 w-9 place-items-center rounded-lg bg-emerald-100">📍</span><small class="text-xs text-slate-500">الموقع</small><strong class="mt-1 block text-sm">{{ $adventure->location }}</strong></div>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
                    <h2 class="mb-3 text-lg font-black">🌿 عن التجربة</h2>
                    <p class="whitespace-pre-line text-sm leading-8 text-slate-600">{{ $adventure->description }}</p>
                    @if($adventure->requirements)
                        <h3 class="mb-2 mt-5 font-bold">المتطلبات</h3>
                        <p class="whitespace-pre-line text-sm leading-7 text-slate-600">{{ $adventure->requirements }}</p>
                    @endif
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm sm:p-6">
                    <h2 class="mb-5 text-lg font-black">♧ برنامج الرحلة خطوة بخطوة</h2>
                    <div class="space-y-5 border-r-2 border-emerald-100 pr-5">
                        @foreach([
                            ['08:00 صباحاً', 'التجمع والانطلاق', 'الالتقاء بالمجموعة والانطلاق إلى موقع المغامرة.'],
                            ['09:30 صباحاً', 'الوصول والتعرف على المنطقة', 'استكشاف المكان والتعرف على طبيعته وثقافته المحلية.'],
                            ['10:15 صباحاً', 'بدء النشاط الرئيسي', 'الاستمتاع بالنشاط الأساسي والتجربة التي يقدمها المنظم.'],
                            ['12:00 ظهراً', 'وجبة محلية واستراحة', 'الاستمتاع بوجبة محلية وأخذ استراحة مع المجموعة.'],
                            ['02:00 ظهراً', 'نهاية الرحلة والعودة', 'اختتام التجربة والعودة من موقع المغامرة.'],
                        ] as [$time, $title, $description])
                            <div class="relative">
                                <span class="absolute -right-[1.65rem] top-1 h-3 w-3 rounded-full border-2 border-white bg-emerald-800 ring-2 ring-emerald-100"></span>
                                <span class="text-xs font-bold text-orange-700">{{ $time }}</span>
                                <h3 class="mt-1 font-bold">{{ $title }}</h3>
                                <p class="mt-1 text-sm leading-6 text-slate-500">{{ $description }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
        const price = Number(@json($adventure->price));
        const capacity = Number(@json($adventure->capacity));
        let people = 2;
        const peopleCount = document.getElementById('peopleCount');
        const peopleInput = document.getElementById('peopleInput');
        const totalPrice = document.getElementById('totalPrice');

        function updateBooking() {
            peopleCount.textContent = people;
            peopleInput.value = people;
            totalPrice.textContent = (price * people).toFixed(2);
        }

        document.getElementById('increase').addEventListener('click', function () {
            if (people < capacity) {
                people++;
                updateBooking();
            }
        });

        document.getElementById('decrease').addEventListener('click', function () {
            if (people > 1) {
                people--;
                updateBooking();
            }
        });

        updateBooking();
    </script>
</body>
</html>
