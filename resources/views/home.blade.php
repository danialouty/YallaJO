
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>يلا جو | اكتشف الأردن</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: #f8f8ff;
            color: #172033;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .hero {
            min-height: 415px;
            background:
                linear-gradient(rgba(12, 20, 30, .62), rgba(12, 20, 30, .78)),
                url('https://images.unsplash.com/photo-1547234935-80c7145ec969?q=85&w=2000')
                center/cover no-repeat;
            color: white;
            padding: 35px 5% 25px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .hero-content {
            text-align: center;
            max-width: 850px;
            margin: 30px auto 15px;
        }

        .hero-tag {
            display: inline-block;
            background: rgba(255,255,255,.2);
            border: 1px solid rgba(255,255,255,.25);
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 12px;
            margin-bottom: 14px;
        }

        .hero h1 {
            font-size: clamp(27px, 4vw, 43px);
            line-height: 1.5;
            font-weight: 900;
        }

        .hero h1 span {
            color: #f5c9ae;
        }

        .hero-description {
            font-size: 13px;
            color: #e4e4e4;
            margin-top: 8px;
            line-height: 2;
        }

        /* Search bar */

        .search-box {
            background: white;
            padding: 6px;
            border-radius: 13px;
            max-width: 1100px;
            width: 100%;
            margin: 10px auto 0;
            display: grid;
            grid-template-columns: repeat(4, 1fr) 125px;
            gap: 5px;
            direction: rtl;
        }

        .search-field {
            background: #f0f2fc;
            border-radius: 9px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
        }

        .search-field .field-icon {
            color: #1b4332;
            font-size: 18px;
        }

        .field-content {
            width: 100%;
        }

        .field-content label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            color: #333;
        }

        .field-content select,
        .field-content input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-family: inherit;
            font-size: 11px;
            color: #777;
            margin-top: 3px;
        }

        .search-button {
            background: #b93816;
            color: white;
            border: none;
            border-radius: 9px;
            font-family: inherit;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .search-button:hover {
            background: #922b10;
        }

        .hero-tags {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 7px;
            margin: 12px auto 0;
        }

        .hero-tags span {
            background: rgba(255,255,255,.13);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            color: #eee;
        }

        .hero-footer {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            color: #d8eee1;
            font-size: 10px;
            font-weight: 700;
            margin-top: 30px;
        }

        /* Adventures section */

        .adventures-section {
            max-width: 1250px;
            margin: auto;
            padding: 30px 25px 45px;
        }

        .section-top {
            display: flex;
            justify-content: space-between;
            align-items: end;
            margin-bottom: 20px;
            gap: 15px;
        }

        .section-label {
            display: inline-block;
            background: #dff4e9;
            color: #1b7050;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            margin-bottom: 7px;
        }

        .section-top h2 {
            font-size: 24px;
            font-weight: 900;
            color: #172033;
        }

        .section-top p {
            font-size: 11px;
            color: #777;
            margin-top: 5px;
        }

        .view-all {
            color: #b93816;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            margin-bottom: 5px;
        }

        .adventures-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .adventure-card {
            background: white;
            border: 1px solid #e8e8ef;
            border-radius: 13px;
            overflow: hidden;
            transition: .25s;
        }

        .adventure-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,.08);
        }

        .card-image {
            height: 145px;
            position: relative;
            overflow: hidden;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #1b7050;
            color: white;
            padding: 4px 9px;
            border-radius: 20px;
            font-size: 9px;
        }

        .favorite {
            position: absolute;
            top: 9px;
            left: 9px;
            background: rgba(255,255,255,.9);
            color: #777;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 14px;
        }

        .card-content {
            padding: 12px;
        }

        .card-location {
            font-size: 10px;
            color: #888;
            margin-bottom: 6px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 800;
            color: #172033;
            margin-bottom: 9px;
            line-height: 1.8;
            min-height: 48px;
        }

        .card-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            color: #777;
            margin-bottom: 12px;
        }

        .card-price {
            color: #b93816;
            font-weight: 800;
            font-size: 14px;
        }

        .card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .details-button {
            background: #b93816;
            color: white;
            padding: 5px 13px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
        }

        .details-button:hover {
            background: #922b10;
        }

        .empty-message {
            grid-column: 1 / -1;
            text-align: center;
            padding: 35px;
            background: white;
            border-radius: 12px;
            color: #777;
        }

        /* Responsive */

        @media (max-width: 900px) {
            .adventures-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-box {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-button {
                min-height: 45px;
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 550px) {
            .hero {
                min-height: 560px;
                padding: 25px 15px;
            }

            .hero-content {
                margin-top: 35px;
            }

            .hero h1 {
                font-size: 27px;
            }

            .search-box {
                grid-template-columns: 1fr;
                gap: 7px;
            }

            .search-field {
                min-height: 55px;
            }

            .hero-footer {
                font-size: 8px;
                text-align: center;
            }

            .adventures-section {
                padding: 25px 13px;
            }

            .section-top {
                align-items: start;
            }

            .section-top h2 {
                font-size: 19px;
            }

            .section-top p {
                max-width: 230px;
                line-height: 1.8;
            }

            .adventures-grid {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .card-image {
                height: 115px;
            }

            .card-content {
                padding: 9px;
            }

            .card-title {
                font-size: 12px;
            }

            .view-all {
                font-size: 10px;
            }
        }
    </style>
</head>

<body>

    <!-- Hero Section -->
    <section class="hero">

        <div class="hero-content">
            <div class="hero-tag">✦ بوابتك لتجارب ومغامرات الطبيعة في الأردن</div>

            <h1>
                اكتشف مسارات الأردن<br>
                <span>ومجتمعات المغامرة</span>
            </h1>

            <p class="hero-description">
                احجز رحلتك، اكتشف تجارب جديدة، وخوض مغامرات فريدة
                مع مجموعات محلية ومرشدين محترفين في قلب الطبيعة الأردنية.
            </p>

            <!-- Search Form -->
            <form action="{{ route('adventures.index') }}" method="GET" class="search-box">

                <div class="search-field">
                    <span class="field-icon">📍</span>
                    <div class="field-content">
                        <label>الوجهة</label>
                        <select name="location">
                            <option value="">اختر الوجهة</option>
                            <option value="عمان">عمان</option>
                            <option value="وادي رم">وادي رم</option>
                            <option value="البتراء">البتراء</option>
                            <option value="العقبة">العقبة</option>
                            <option value="عجلون">عجلون</option>
                        </select>
                    </div>
                </div>

                <div class="search-field">
                    <span class="field-icon">🧭</span>
                    <div class="field-content">
                        <label>نوع النشاط</label>
                        <select name="category">
                            <option value="">جميع النشاطات</option>
                            <option value="hiking">المشي والمسارات</option>
                            <option value="camping">التخييم</option>
                            <option value="diving">الغوص</option>
                            <option value="adventure">مغامرات</option>
                        </select>
                    </div>
                </div>

                <div class="search-field">
                    <span class="field-icon">📅</span>
                    <div class="field-content">
                        <label>تاريخ الرحلة</label>
                        <input type="date" name="date">
                    </div>
                </div>

                <div class="search-field">
                    <span class="field-icon">👥</span>
                    <div class="field-content">
                        <label>عدد الأشخاص</label>
                        <select name="people">
                            <option value="1">شخص واحد</option>
                            <option value="2">شخصان</option>
                            <option value="3">3 أشخاص</option>
                            <option value="4">4 أشخاص</option>
                            <option value="5">5 أشخاص أو أكثر</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="search-button">
                    🔍 ابحث الآن
                </button>

            </form>

            <div class="hero-tags">
                <span>🥾 مسارات مشي</span>
                <span>🏕️ تخييم ومبيت</span>
                <span>🚵 مغامرات</span>
                <span>🌊 تجارب مائية</span>
            </div>
        </div>

        <div class="hero-footer">
            <span>♧ تجارب محلية أصيلة</span>
            <span>✦ اكتشف أجمل مناطق الأردن</span>
            <span>♡ مغامرات تناسب الجميع</span>
        </div>

    </section>


    <!-- Adventures Section -->
    <section class="adventures-section">

        <div class="section-top">
            <div>
                <span class="section-label">✧ ابدأ رحلتك القادمة</span>
                <h2>مغامرات وتجارب لأول مرة</h2>
                <p>
                    تجارب مميزة، ومسارات مختارة بعناية لتعيش أجمل لحظات المغامرة.
                </p>
            </div>

            <a href="{{ route('adventuresAll.index') }}" class="view-all">
                عرض كل المغامرات ←
            </a>
        </div>


        <div class="adventures-grid">

            @forelse($adventures as $adventure)

                <article class="adventure-card">

                    <div class="card-image">

                        <img
                            src="{{ $adventure->images->isNotEmpty()
                                ? asset('images/' . $adventure->images->first()->image_path)
                                : 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=600' }}"
                            alt="{{ $adventure->title }}"
                        >

                        <span class="category-badge">
                            {{ $adventure->category }}
                        </span>

                        <span class="favorite">♡</span>

                    </div>

                    <div class="card-content">

                        <div class="card-location">
                            📍 {{ $adventure->location }}
                        </div>

                        <h3 class="card-title">
                            {{ $adventure->title }}
                        </h3>

                        <div class="card-info">
                            <span>⏱ {{ $adventure->duration_hours }} ساعات</span>
                            <span>👥 {{ $adventure->capacity }} مقاعد</span>
                        </div>

                        <div class="card-bottom">

                            <a
                                href="{{ route('adventures.profile', $adventure) }}"
                                class="details-button"
                            >
                                احجز الآن
                            </a>

                            <div>
                                <div style="font-size: 9px; color: #888;">
                                    السعر للشخص
                                </div>

                                <div class="card-price">
                                    {{ number_format($adventure->price, 0) }} د.أ
                                </div>
                            </div>

                        </div>

                    </div>

                </article>

            @empty

                <div class="empty-message">
                    لا توجد مغامرات متاحة حالياً.
                </div>

            @endforelse

        </div>

    </section>

</body>
</html>
