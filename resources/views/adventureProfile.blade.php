<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $adventure->title }} | يلا جو</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('home') }}" class="logo">يلا جو</a>
        <a href="{{ route('adventuresAll.index') }}" class="back-link">← العودة إلى المغامرات</a>
    </nav>

    <main class="container" data-adventure-booking
          data-price="{{ $adventure->price }}"
          data-capacity="{{ $adventure->capacity }}">
        <section class="gallery" aria-label="صور المغامرة">
            <div class="gallery-left">
                @foreach($adventure->images->skip(1)->take(2) as $image)
                    <img src="{{ asset('images/' . $image->image_path) }}" alt="{{ $adventure->title }}">
                @endforeach
            </div>
            <div class="gallery-main">
                @if($adventure->images->isNotEmpty())
                    <img src="{{ asset('images/' . $adventure->images->first()->image_path) }}" alt="{{ $adventure->title }}">
                @else
                    <img src="https://images.unsplash.com/photo-1470252649378-9c29740c9fa8?w=1200" alt="{{ $adventure->title }}">
                @endif
            </div>
        </section>

        <div class="details-layout">
            <aside>
                <div class="card booking-card">
                    <div class="booking-price">
                        <span>السعر للشخص</span>
                        <strong>{{ number_format($adventure->price, 2) }} <small>د.أ</small></strong>
                    </div>

                    @if(session('error'))
                        <p class="booking-note text-red-600">{{ session('error') }}</p>
                    @endif
                    @foreach ($errors->all() as $error)
                        <p class="booking-note text-red-600" role="alert">{{ $error }}</p>
                    @endforeach

                    @if ($adventure->status !== 'Active' || $adventure->capacity < 1)
                        <p class="booking-note">الحجز غير متاح لهذه المغامرة حاليًا.</p>
                    @elseif (auth()->check() && auth()->user()->role !== 'Adventurer')
                        <p class="booking-note">الحجز متاح لحسابات المغامرين.</p>
                    @else
                    <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="adventure_id" value="{{ $adventure->id }}">

                        <label for="bookingDate" class="booking-label">تاريخ الرحلة</label>
                        <input id="bookingDate" type="date" name="booking_date" class="booking-input"
                               min="{{ date('Y-m-d') }}" required>

                        <label class="booking-label">عدد الأشخاص</label>
                        <div class="people-control">
                            <button type="button" id="increase" aria-label="زيادة عدد الأشخاص">+</button>
                            <strong id="peopleCount">1</strong>
                            <button type="button" id="decrease" aria-label="تقليل عدد الأشخاص">−</button>
                        </div>
                        <input type="hidden" name="participants_count" id="peopleInput" value="1">

                        <div class="total">
                            <span>الإجمالي التقريبي</span>
                            <span class="total-price">
                                <span id="totalPrice">{{ number_format($adventure->price, 2) }}</span> د.أ
                            </span>
                        </div>

                        <button type="submit" class="book-button">
                            تأكيد الحجز الآن
                        </button>
                        <p class="booking-note">سيتم إنشاء الحجز والدفع المعلق تلقائيًا بعد التأكيد.</p>
                    </form>
                    @endif
                </div>

                <div class="card benefits">
                    <p>🟢 <strong>تجربة محلية أصيلة</strong><br>اكتشف الثقافة والمجتمع المحلي.</p>
                    <p>🟠 <strong>منظم موثوق</strong><br>تواصل مع منظم المغامرة قبل الحجز.</p>
                </div>
            </aside>

            <section class="main-content">
                <div class="card">
                    <div class="adventure-header">
                        <div>
                            <div class="badges">
                                <span class="badge badge-green">✦ تجربة محلية</span>
                                <span class="badge badge-orange">{{ $adventure->category }}</span>
                            </div>
                            <h1>{{ $adventure->title }}</h1>
                            <p class="location">📍 {{ $adventure->location }}، الأردن</p>
                            <a href="{{ route('reviews.index', ['adventure' => $adventure]) }}" class="reviews-link">
                                عرض تقييمات المغامرة
                            </a>
                        </div>
                        <span class="badge rating">⭐ تجربة مميزة</span>
                    </div>
                </div>

                <div class="card organizer">
                    <div class="organizer-info">
                        <img class="organizer-avatar"
                             src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=160"
                             alt="منظم المغامرة">
                        <div>
                            <h3>{{ $adventure->organizer_name ?? 'منظم الرحلة' }}</h3>
                            <p>منظم المغامرة · الأردن</p>
                        </div>
                    </div>
                    <a href="#" class="contact-button">تواصل مع المنظم</a>
                </div>

                <div class="features">
                    <div class="feature"><div class="feature-icon">⏱</div><small>مدة المغامرة</small><strong>{{ $adventure->duration_hours }} ساعات</strong></div>
                    <div class="feature"><div class="feature-icon">🥾</div><small>مستوى الصعوبة</small><strong>{{ $adventure->difficulty_level ?? 'متوسط' }}</strong></div>
                    <div class="feature"><div class="feature-icon">👥</div><small>السعة القصوى</small><strong>{{ $adventure->capacity }} شخص</strong></div>
                    <div class="feature"><div class="feature-icon">📍</div><small>الموقع</small><strong>{{ $adventure->location }}</strong></div>
                </div>

                <div class="card">
                    <h2 class="section-title">🌿 عن التجربة</h2>
                    <p class="description">{{ $adventure->description }}</p>
                </div>

                <div class="card">
                    <h2 class="section-title">♧ برنامج الرحلة خطوة بخطوة</h2>
                    <div class="timeline">
                        <div class="timeline-item"><span class="timeline-time">08:00 صباحاً</span><h3>التجمع والانطلاق</h3><p>الالتقاء بالمجموعة والانطلاق إلى موقع المغامرة.</p></div>
                        <div class="timeline-item"><span class="timeline-time">09:30 صباحاً</span><h3>الوصول والتعرف على المنطقة</h3><p>استكشاف المكان والتعرف على طبيعته وثقافته المحلية.</p></div>
                        <div class="timeline-item"><span class="timeline-time">10:15 صباحاً</span><h3>بدء النشاط الرئيسي</h3><p>الاستمتاع بالنشاط الأساسي والتجربة المقدمة من المنظم.</p></div>
                        <div class="timeline-item"><span class="timeline-time">12:00 ظهراً</span><h3>وجبة محلية واستراحة</h3><p>الاستمتاع بوجبة محلية وأخذ استراحة مع المجموعة.</p></div>
                        <div class="timeline-item"><span class="timeline-time">02:00 ظهراً</span><h3>نهاية الرحلة والعودة</h3><p>اختتام التجربة والعودة من موقع المغامرة.</p></div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
