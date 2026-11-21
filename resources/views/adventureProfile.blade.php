<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $adventure->title }} | يلا جو</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: #f7f7fc;
            color: #1E293B;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button,
        input {
            font-family: inherit;
        }

        .navbar {
            background: white;
            padding: 13px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 23px;
            color: #1B4332;
            font-weight: 900;
        }

        .back-link {
            color: #D95D39;
            font-size: 12px;
            font-weight: 700;
        }

        .container {
            max-width: 1250px;
            margin: auto;
            padding: 20px;
        }

        /* Gallery */

        .gallery {
            height: 350px;
            display: grid;
            grid-template-columns: 1fr 1.4fr;
            gap: 7px;
            overflow: hidden;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .gallery-left {
            display: grid;
            grid-template-rows: 1fr 1fr;
            gap: 7px;
            min-height: 0;
        }

        .gallery img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-main {
            min-width: 0;
            min-height: 0;
        }

        /* Main layout */

        .details-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 20px;
            align-items: start;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            gap: 18px;
            min-width: 0;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .035);
        }

        /* Title */

        .adventure-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .badges {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 10px;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .badge-green {
            background: #e0f3e8;
            color: #1B4332;
        }

        .badge-orange {
            background: #fff0e5;
            color: #D95D39;
        }

        .rating {
            color: #D97706;
            background: #fff7e8;
        }

        .adventure-header h1 {
            font-size: 25px;
            line-height: 1.7;
            font-weight: 900;
        }

        .reviews-link {
            display: inline-flex;
            align-items: center;
            min-height: 44px;
            margin-block-start: 12px;
            padding: 8px 16px;
            border: 1px solid var(--color-brand, #a43716);
            border-radius: var(--radius-control, 0.5rem);
            color: var(--color-brand, #a43716);
            font-size: 14px;
            font-weight: 700;
        }

        .reviews-link:hover {
            color: var(--color-brand-dark, #862201);
            text-decoration: underline;
        }

        .reviews-link:focus-visible {
            outline: 2px solid var(--color-brand, #a43716);
            outline-offset: 3px;
        }

        .location {
            color: #777;
            font-size: 12px;
            margin-top: 7px;
        }

        /* Organizer */

        .organizer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .organizer-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .organizer-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            background: #e5e7eb;
        }

        .organizer h3 {
            font-size: 14px;
        }

        .organizer p {
            font-size: 10px;
            color: #888;
            margin-top: 4px;
        }

        .contact-button {
            background: #eef2ff;
            color: #1B4332;
            padding: 8px 13px;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 700;
        }

        /* Quick information */

        .features {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .feature {
            background: white;
            padding: 16px 10px;
            border-radius: 11px;
            text-align: center;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .035);
        }

        .feature-icon {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            margin: 0 auto 9px;
            border-radius: 9px;
            background: #e0f3e8;
            font-size: 16px;
        }

        .feature small {
            display: block;
            font-size: 10px;
            color: #888;
        }

        .feature strong {
            display: block;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Description */

        .section-title {
            font-size: 18px;
            font-weight: 900;
            margin-bottom: 13px;
            color: #1E293B;
        }

        .description {
            color: #666;
            font-size: 12px;
            line-height: 2.2;
            white-space: pre-line;
        }

        /* Itinerary */

        .timeline {
            position: relative;
            padding-right: 22px;
        }

        .timeline::before {
            content: "";
            position: absolute;
            right: 5px;
            top: 8px;
            bottom: 10px;
            width: 2px;
            background: #dcece2;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 22px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item::before {
            content: "";
            position: absolute;
            right: -22px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #1B4332;
            border: 3px solid #e0f3e8;
        }

        .timeline-time {
            color: #D95D39;
            font-size: 10px;
            font-weight: 700;
        }

        .timeline-item h3 {
            font-size: 13px;
            margin: 4px 0;
        }

        .timeline-item p {
            font-size: 11px;
            line-height: 1.9;
            color: #888;
        }

        /* Booking card */

        .booking-card {
            position: sticky;
            top: 20px;
        }

        .booking-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .booking-price strong {
            font-size: 25px;
            color: #D95D39;
        }

        .booking-price span {
            font-size: 11px;
            color: #888;
        }

        .booking-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            margin: 13px 0 7px;
        }

        .booking-input {
            width: 100%;
            padding: 11px;
            border: 1px solid #e5e7eb;
            background: #f8f9ff;
            border-radius: 8px;
            font-size: 12px;
            outline: none;
        }

        .people-control {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9ff;
            padding: 9px 12px;
            border-radius: 8px;
        }

        .people-control button {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 7px;
            background: white;
            color: #1B4332;
            font-size: 18px;
            cursor: pointer;
        }

        .people-control strong {
            font-size: 14px;
        }

        .total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eee;
            padding-top: 16px;
            margin-top: 18px;
            font-weight: 800;
            font-size: 12px;
        }

        .total-price {
            color: #D95D39;
            font-size: 22px;
        }

        .book-button {
            display: block;
            width: 100%;
            border: none;
            background: #D95D39;
            color: white;
            padding: 13px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 13px;
            margin-top: 18px;
            cursor: pointer;
        }

        .book-button:hover {
            background: #b84929;
        }

        .booking-note {
            text-align: center;
            color: #888;
            font-size: 10px;
            margin-top: 12px;
        }

        .benefits {
            margin-top: 15px;
        }

        .benefits p {
            font-size: 11px;
            margin: 12px 0;
            color: #555;
        }

        /* Responsive */

        @media (max-width: 850px) {
            .details-layout {
                grid-template-columns: 1fr;
            }

            .booking-card {
                position: static;
                grid-row: 1;
            }

            .gallery {
                height: 280px;
            }
        }

        @media (max-width: 550px) {
            .container {
                padding: 12px;
            }

            .gallery {
                height: 220px;
            }

            .adventure-header h1 {
                font-size: 19px;
            }

            .features {
                grid-template-columns: repeat(2, 1fr);
            }

            .organizer {
                align-items: flex-start;
                flex-direction: column;
            }

            .contact-button {
                margin-right: 60px;
            }

            .card {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <a href="{{ route('home') }}" class="logo">يلا جو</a>

        <a href="{{ route('adventuresAll.index') }}" class="back-link">
            ← العودة إلى المغامرات
        </a>
    </nav>

    <main class="container">

        <!-- Photo Gallery -->

        <section class="gallery">

            <div class="gallery-left">
                @foreach($adventure->images->skip(1)->take(2) as $image)
                    <img src="{{ asset('images/' . $image->image_path) }}" alt="{{ $adventure->title }}">
                @endforeach
            </div>

            <div class="gallery-main">
                @if($adventure->images->isNotEmpty())
                    <img src="{{ asset('images/' . $adventure->images->first()->image_path) }}"
                        alt="{{ $adventure->title }}">
                @else
                    <img src="https://images.unsplash.com/photo-1470252649378-9c29740c9fa8?w=1000" alt="Adventure">
                @endif
            </div>

        </section>

        <div class="details-layout">

            <!-- Booking Sidebar -->
            <aside>

                <div class="card booking-card">

                    <div class="booking-price">
                        <span>السعر للشخص</span>
                        <strong>
                            {{ number_format($adventure->price, 2) }}
                            <small style="font-size: 12px;">د.أ</small>
                        </strong>
                    </div>

                    <form action="#" method="POST" id="bookingForm">
                        @csrf

                        <input type="hidden" name="adventure_id" value="{{ $adventure->id }}">

                        <label class="booking-label">تاريخ الرحلة</label>
                        <input type="date" name="booking_date" class="booking-input" min="{{ date('Y-m-d') }}" required>

                        <label class="booking-label">عدد الأشخاص</label>

                        <div class="people-control">
                            <button type="button" id="increase">+</button>

                            <strong id="peopleCount">2</strong>

                            <button type="button" id="decrease">−</button>
                        </div>

                        <input type="hidden" name="people" id="peopleInput" value="2">

                        <div class="total">
                            <span>الإجمالي التقريبي</span>

                            <span class="total-price">
                                <span id="totalPrice">
                                    {{ number_format($adventure->price * 2, 2) }}
                                </span>
                                د.أ
                            </span>
                        </div>

                        <button type="button" class="book-button"
                            onclick="alert('واجهة الحجز جاهزة، اربطها بمسار الحجز عند تفعيله.')">
                            🔒 تأكيد الحجز الآن
                        </button>

                        <p class="booking-note">
                            السعر النهائي يعتمد على تفاصيل الحجز
                        </p>

                    </form>

                </div>

                <div class="card benefits">
                    <p>🟢 <strong>تجربة محلية أصيلة</strong><br>اكتشف الثقافة والمجتمع المحلي.</p>
                    <p>🟠 <strong>منظم موثوق</strong><br>تواصل مع منظم المغامرة قبل الحجز.</p>
                </div>

            </aside>


            <!-- Main Adventure Content -->
            <section class="main-content">

                <!-- Adventure Title -->
                <div class="card">

                    <div class="adventure-header">

                        <div>
                            <div class="badges">
                                <span class="badge badge-green">
                                    ✦ تجربة محلية
                                </span>

                                <span class="badge badge-orange">
                                    {{ $adventure->category }}
                                </span>
                            </div>

                            <h1>{{ $adventure->title }}</h1>

                            <p class="location">
                                📍 {{ $adventure->location }}، الأردن
                            </p>

                            <a href="{{ route('reviews.index', ['adventure' => $adventure]) }}" class="reviews-link">
                                عرض تقييمات المغامرة
                            </a>
                        </div>

                        <span class="badge rating">
                            ⭐ تجربة مميزة
                        </span>

                    </div>

                </div>


                <!-- Organizer -->
                <div class="card organizer">

                    <div class="organizer-info">

                        <img class="organizer-avatar"
                            src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100"
                            alt="منظم المغامرة">

                        <div>
                            <h3>{{ $adventure->organizer_name ?? 'منظم الرحلة' }}</h3>
                            <p>منظم المغامرة · الأردن</p>
                        </div>

                    </div>

                    <a href="#" class="contact-button">
                        تواصل مع المنظم
                    </a>

                </div>


                <!-- Quick Features -->
                <div class="features">

                    <div class="feature">
                        <div class="feature-icon">⏱</div>
                        <small>مدة المغامرة</small>
                        <strong>{{ $adventure->duration_hours }} ساعات</strong>
                    </div>

                    <div class="feature">
                        <div class="feature-icon">🥾</div>
                        <small>مستوى الصعوبة</small>
                        <strong>{{ $adventure->difficulty_level ?? 'متوسط' }}</strong>
                    </div>

                    <div class="feature">
                        <div class="feature-icon">👥</div>
                        <small>السعة القصوى</small>
                        <strong>{{ $adventure->capacity }} شخص</strong>
                    </div>

                    <div class="feature">
                        <div class="feature-icon">📍</div>
                        <small>الموقع</small>
                        <strong>{{ $adventure->location }}</strong>
                    </div>

                </div>


                <!-- Description -->
                <div class="card">

                    <h2 class="section-title">
                        🌿 عن التجربة
                    </h2>

                    <p class="description">
                        {{ $adventure->description }}
                    </p>

                </div>


                <!-- Itinerary -->
                <div class="card">

                    <h2 class="section-title">
                        ♧ برنامج الرحلة خطوة بخطوة
                    </h2>

                    <div class="timeline">

                        <div class="timeline-item">
                            <span class="timeline-time">08:00 صباحاً</span>
                            <h3>التجمع والانطلاق</h3>
                            <p>الالتقاء بالمجموعة والانطلاق إلى موقع المغامرة.</p>
                        </div>

                        <div class="timeline-item">
                            <span class="timeline-time">09:30 صباحاً</span>
                            <h3>الوصول والتعرف على المنطقة</h3>
                            <p>استكشاف المكان والتعرف على طبيعته وثقافته المحلية.</p>
                        </div>

                        <div class="timeline-item">
                            <span class="timeline-time">10:15 صباحاً</span>
                            <h3>بدء النشاط الرئيسي</h3>
                            <p>الاستمتاع بالنشاط الأساسي والتجربة التي يقدمها المنظم.</p>
                        </div>

                        <div class="timeline-item">
                            <span class="timeline-time">12:00 ظهراً</span>
                            <h3>وجبة محلية واستراحة</h3>
                            <p>الاستمتاع بوجبة محلية وأخذ استراحة مع المجموعة.</p>
                        </div>

                        <div class="timeline-item">
                            <span class="timeline-time">02:00 ظهراً</span>
                            <h3>نهاية الرحلة والعودة</h3>
                            <p>اختتام التجربة والعودة من موقع المغامرة.</p>
                        </div>

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
