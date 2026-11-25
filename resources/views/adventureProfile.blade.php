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
                ->take(4)
                ->map(fn ($image) => $image->url)
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
                    <h2 id="adventure-booking-success-title">تم الحجز</h2>
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
                        <input type="hidden" name="payment_method" value="Cash">

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

                        <button type="button" class="book-button" data-open-payment>
                            حجز
                        </button>
                        <p class="booking-note">أدخل بيانات الدفع لإكمال الحجز.</p>
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
                                <span class="badge badge-green">{{ $adventure->category }}</span>
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
                            <h3>{{ $adventure->activityOrganizer?->user?->name ?? 'منظم الرحلة' }}</h3>
                            <p>منظم المغامرة · الأردن</p>
                        </div>
                    </div>
                    @php($organizerUser = $adventure->activityOrganizer?->user)
                    @if ($organizerUser?->phone_number)
                        <a href="https://wa.me/{{ preg_replace('/\\D+/', '', $organizerUser->phone_number) }}" class="contact-button" target="_blank" rel="noopener">تواصل مع المنظم</a>
                    @elseif ($organizerUser?->email)
                        <a href="mailto:{{ $organizerUser->email }}" class="contact-button">تواصل مع المنظم</a>
                    @else
                        <span class="contact-button" aria-disabled="true">لا تتوفر بيانات تواصل</span>
                    @endif
                </div>

                <div class="features">
                    <div class="feature"><div class="feature-icon">⏱</div><small>مدة المغامرة</small><strong>{{ $adventure->duration_hours }} ساعات</strong></div>
                    <div class="feature"><div class="feature-icon">🥾</div><small>مستوى الصعوبة</small><strong>{{ $adventure->difficulty_level ?? 'متوسط' }}</strong></div>
                    <div class="feature"><div class="feature-icon">👥</div><small>السعة القصوى</small><strong>{{ $adventure->capacity }} شخص</strong></div>
                    <div class="feature"><div class="feature-icon">📍</div><small>نقطة تجمع المغامرين</small><strong>{{ $adventure->location }}</strong></div>
                </div>

                @php($meetingPointQuery = $adventure->location . ', الأردن')
                <section class="card meeting-point-card" aria-labelledby="meeting-point-title">
                    <div class="meeting-point-heading">
                        <div>
                            <h2 id="meeting-point-title" class="section-title">🗺️ خريطة نقطة التجمع</h2>
                            <p class="description">{{ $adventure->location }}، الأردن</p>
                        </div>
                        <a class="contact-button" href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode($meetingPointQuery) }}" target="_blank" rel="noopener">الاتجاهات</a>
                    </div>
                    <iframe class="meeting-point-map"
                            src="https://www.google.com/maps?q={{ urlencode($meetingPointQuery) }}&amp;output=embed"
                            title="خريطة نقطة التجمع: {{ $adventure->location }}"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen></iframe>
                </section>

                <div class="card">
                    <h2 class="section-title">📅 مواعيد المغامرة وجداول المرشدين</h2>
                    <div class="adventure-schedules">
                        @forelse ($adventure->schedules as $schedule)
                            <article class="adventure-schedule">
                                <div><small>تاريخ المغامرة</small><strong>{{ $schedule->starts_at?->translatedFormat('l، j F Y') ?? 'غير محدد' }}</strong></div>
                                <div><small>الوقت</small><strong>{{ $schedule->starts_at?->format('H:i') ?? '—' }} – {{ $schedule->ends_at?->format('H:i') ?? '—' }}</strong></div>
                                <div><small>المرشد</small><strong>{{ $schedule->guide?->activityOrganizer?->user?->name ?? 'سيتم تحديده' }}</strong></div>
                                <div><small>نقطة التجمع</small><strong>{{ $adventure->location }}</strong></div>
                            </article>
                        @empty
                            <p class="description">لم تُحدد مواعيد لهذه المغامرة بعد.</p>
                        @endforelse
                    </div>
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

    @if (auth()->check() && auth()->user()->role === 'Adventurer' && ! $alreadyBooked && $adventure->status === 'Active' && $adventure->capacity > 0)
        <div id="adventure-payment-modal" class="adventure-booking-success-backdrop is-hidden" role="dialog" aria-modal="true" aria-labelledby="payment-modal-title">
            <section class="adventure-booking-success-card payment-modal-card" dir="rtl">
                <p class="adventure-booking-success-kicker">الدفع الآمن</p>
                <h2 id="payment-modal-title">بيانات الدفع</h2>
                <p>الإجمالي: <strong><span data-payment-total>{{ number_format($adventure->price, 2) }}</span> د.أ</strong></p>
                <form id="payment-details-form" novalidate>
                    <label class="booking-label" for="payment-cardholder">اسم حامل البطاقة</label>
                    <input class="booking-input" id="payment-cardholder" autocomplete="cc-name" required>
                    <label class="booking-label" for="payment-card-number">رقم البطاقة</label>
                    <input class="booking-input" id="payment-card-number" inputmode="numeric" autocomplete="cc-number" maxlength="19" required>
                    <div class="payment-fields-row">
                        <div><label class="booking-label" for="payment-expiry">تاريخ الانتهاء</label><input class="booking-input" id="payment-expiry" placeholder="MM/YY" autocomplete="cc-exp" maxlength="5" required></div>
                        <div><label class="booking-label" for="payment-cvv">رمز الأمان</label><input class="booking-input" id="payment-cvv" inputmode="numeric" autocomplete="cc-csc" maxlength="4" required></div>
                    </div>
                    <p class="booking-note" data-payment-error role="alert"></p>
                    <button type="submit" class="book-button">تأكيد الحجز</button>
                    <button type="button" class="payment-cancel-button" data-close-payment>رجوع</button>
                </form>
            </section>
        </div>
    @endif
</body>
</html>
