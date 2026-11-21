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
        <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="الانتقال إلى الصفحة الرئيسية">
            <x-application-logo class="block size-11 shrink-0" aria-hidden="true" />
            <span class="logo">يلا جو</span>
        </a>
        <a href="{{ route('adventuresAll.index') }}" class="back-link">← العودة إلى المغامرات</a>
    </nav>

    <main class="container" data-adventure-booking
          data-price="{{ $adventure->price }}"
          data-capacity="{{ $adventure->capacity }}">
        @php
            $galleryImages = $adventure->images
                ->map(fn ($image) => asset('images/' . $image->image_path))
                ->values()
                ->all();

            $fallbackImages = [
                'https://images.unsplash.com/photo-1551632811-561732d1e306?auto=format&fit=crop&w=1600&q=85',
                'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=85',
                'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1200&q=85',
                'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1200&q=85',
            ];

            foreach ($fallbackImages as $fallbackImage) {
                if (count($galleryImages) >= 4) {
                    break;
                }
                if (!in_array($fallbackImage, $galleryImages, true)) {
                    $galleryImages[] = $fallbackImage;
                }
            }

            $mainGalleryImage = $galleryImages[0] ?? $fallbackImages[0];
        @endphp

        <section class="gallery" data-adventure-gallery aria-label="صور المغامرة">
            <figure class="gallery-main">
                <img id="gallery-main-image"
                     src="{{ $mainGalleryImage }}"
                     alt="{{ $adventure->title }}"
                     loading="eager"
                     fetchpriority="high">
                <button type="button" class="gallery-expand" data-gallery-expand aria-label="عرض الصورة بالحجم الكامل">
                    عرض الصورة
                </button>
                <span class="gallery-count">{{ count($galleryImages) }} صور</span>
            </figure>

            <div class="gallery-thumbnails" aria-label="صور مصغرة للمغامرة">
                @foreach(array_slice($galleryImages, 0, 4) as $index => $galleryImage)
                    <button type="button"
                            class="gallery-thumbnail {{ $index === 0 ? 'is-active' : '' }}"
                            data-gallery-src="{{ $galleryImage }}"
                            aria-label="عرض الصورة رقم {{ $index + 1 }}">
                        <img src="{{ $galleryImage }}" alt="{{ $adventure->title }} - صورة {{ $index + 1 }}" loading="lazy">
                    </button>
                @endforeach
            </div>
        </section>

        @if (session('booking_success'))
            <div id="adventure-booking-success" class="adventure-booking-success-backdrop"
                 role="dialog" aria-modal="true" aria-labelledby="adventure-booking-success-title">
                <section class="adventure-booking-success-card" dir="rtl">
                    <div class="adventure-booking-success-icon" aria-hidden="true">✓</div>
                    <p class="adventure-booking-success-kicker">اكتمل الحجز</p>
                    <h2 id="adventure-booking-success-title">تم إرسال طلبك بنجاح</h2>
                    <p>{{ session('booking_success') }}</p>
                    <button type="button" data-close-adventure-booking-success autofocus>متابعة التصفح</button>
                </section>
            </div>
        @endif

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
                        <p class="booking-note">الحجز متاح لحسابات المغامرين فقط.</p>
                    @elseif (!auth()->check())
                        <a href="{{ route('login', ['redirect' => request()->fullUrl()]) }}"
                           class="book-button block text-center">
                            سجل دخولك لتأكيد الحجز
                        </a>
                    @elseif ($alreadyBooked)
                        <div class="booking-note" role="status">سبق وقدّمت طلب حجز لهذه المغامرة. يمكنك متابعة حالته من صفحة حجوزاتي.</div>
                        <a href="{{ route('bookings.index') }}" class="book-button block text-center">متابعة حالة الحجز</a>
                    @else
                    <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                        @csrf
                        <input type="hidden" name="adventure_id" value="{{ $adventure->id }}">

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
                        @forelse ($adventure->days as $day)
                            <div class="timeline-item">
                                <span class="timeline-time">اليوم {{ $day->day_number }}</span>
                                <h3>{{ $day->title }}</h3>
                                @if ($day->description)
                                    <p>{{ $day->description }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">لم تتم إضافة برنامج الرحلة بعد.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
