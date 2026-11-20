
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>جميع المغامرات | يلا جو</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: #f8f8f5;
            color: #1E293B;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            background: white;
            padding: 16px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .logo {
            color: #1B4332;
            font-size: 25px;
            font-weight: 800;
        }

        .nav-link {
            color: #1E293B;
            font-size: 13px;
            font-weight: 700;
        }

        .page-header {
            background: #1B4332;
            color: white;
            padding: 45px 7%;
        }

        .page-header h1 {
            font-size: 30px;
            margin-bottom: 8px;
        }

        .page-header p {
            font-size: 13px;
            color: #dce8df;
        }

        .container {
            max-width: 1250px;
            margin: auto;
            padding: 35px 20px;
        }

        .filter-box {
            background: white;
            padding: 18px;
            border-radius: 12px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,.04);
            margin-bottom: 30px;
        }

        .filter-box input,
        .filter-box select {
            width: 100%;
            padding: 11px;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            font-family: inherit;
            font-size: 12px;
            background: white;
        }

        .filter-button {
            border: none;
            background: #D95D39;
            color: white;
            border-radius: 7px;
            padding: 0 25px;
            font-family: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-heading h2 {
            font-size: 21px;
        }

        .results-count {
            color: #777;
            font-size: 12px;
        }

        .adventures-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .adventure-card {
            background: white;
            border: 1px solid #eee;
            border-radius: 12px;
            overflow: hidden;
            transition: .25s;
        }

        .adventure-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,.08);
        }

        .card-image {
            height: 190px;
            position: relative;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #1B4332;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 10px;
        }

        .card-content {
            padding: 17px;
        }

        .location {
            color: #888;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .card-content h3 {
            font-size: 17px;
            margin-bottom: 12px;
        }

        .details {
            display: flex;
            justify-content: space-between;
            color: #777;
            font-size: 11px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 13px;
        }

        .price {
            color: #D95D39;
            font-size: 17px;
            font-weight: 800;
        }

        .book-button {
            background: #D95D39;
            color: white;
            padding: 7px 15px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .empty {
            grid-column: 1 / -1;
            text-align: center;
            background: white;
            padding: 50px 15px;
            border-radius: 12px;
            color: #777;
        }

        .pagination {
            margin-top: 30px;
        }

        .footer {
            background: #1B4332;
            color: white;
            text-align: center;
            padding: 22px;
            font-size: 12px;
        }

        @media (max-width: 800px) {
            .adventures-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .filter-box {
                grid-template-columns: 1fr 1fr;
            }

            .filter-button {
                min-height: 42px;
            }
        }

        @media (max-width: 550px) {
            .adventures-grid {
                grid-template-columns: 1fr;
            }

            .filter-box {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <a href="{{ route('home') }}" class="logo">يلا جو</a>
        <a href="{{ route('home') }}" class="nav-link">العودة للرئيسية ←</a>
    </nav>

    <header class="page-header">
        <h1>اكتشف جميع المغامرات</h1>
        <p>استكشف أجمل التجارب والأنشطة السياحية في الأردن واختر مغامرتك القادمة.</p>
    </header>

    <main class="container">

        <!-- Search and Filters -->
        <form action="{{ route('adventures.index') }}" method="GET" class="filter-box">

            <input
                type="text"
                name="search"
                placeholder="ابحث عن مغامرة..."
                value="{{ request('search') }}"
            >

            <select name="location">
                <option value="">جميع المواقع</option>

                @foreach($locations as $location)
                    <option value="{{ $location }}"
                        {{ request('location') == $location ? 'selected' : '' }}>
                        {{ $location }}
                    </option>
                @endforeach
            </select>

            <select name="category">
                <option value="">جميع التصنيفات</option>

                @foreach($categories as $category)
                    <option value="{{ $category }}"
                        {{ request('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="filter-button">
                بحث
            </button>

        </form>

        <!-- Adventures -->
        <div class="section-heading">
            <h2>المغامرات المتاحة</h2>

            <span class="results-count">
                {{ $adventures->total() }} مغامرة
            </span>
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

                        <span class="category">
                            {{ $adventure->category }}
                        </span>

                    </div>

                    <div class="card-content">

                        <div class="location">
                            📍 {{ $adventure->location }}
                        </div>

                        <h3>{{ $adventure->title }}</h3>

                        <div class="details">
                            <span>⏱ {{ $adventure->duration_hours }} ساعات</span>
                            <span>👥 {{ $adventure->capacity }} مقاعد</span>
                        </div>

                        <div class="card-footer">

                            <a
                                href="{{ route('adventures.profile', $adventure) }}"
                                class="book-button"
                            >
                                عرض التفاصيل
                            </a>

                            <span class="price">
                                {{ number_format($adventure->price, 2) }} د.أ
                            </span>

                        </div>

                    </div>

                </article>

            @empty

                <div class="empty">
                    <h3>لا توجد مغامرات حالياً</h3>
                    <p>جرّب تغيير خيارات البحث أو التصنيف.</p>
                </div>

            @endforelse

        </div>

        <div class="pagination">
            {{ $adventures->links() }}
        </div>

    </main>

    <footer class="footer">
        © {{ date('Y') }} يلا جو - اكتشف الأردن بطريقة مختلفة
    </footer>

</body>
</html>
