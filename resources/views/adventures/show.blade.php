<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $adventure->title }}</title>
    <style>
        body { margin: 0; padding: 32px; background: #f7f8fc; color: #1e293b; font-family: Arial, sans-serif; }
        main { max-width: 1000px; margin: auto; padding: 28px; background: white; border-radius: 16px; }
        .actions { display: flex; gap: 12px; margin-bottom: 24px; }
        a { color: #d95d39; }
        .details { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; }
        .details p { margin: 0; padding: 12px; background: #f7f8fc; border-radius: 8px; }
        section { margin-top: 30px; }
        .images { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; }
        .images img { width: 100%; height: 160px; object-fit: cover; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid #e8ecf3; }
        th { background: #f7f8fc; }
        .empty { color: #64748b; }
        @media (max-width: 600px) { body { padding: 12px; } main { padding: 18px; overflow-x: auto; } }
    </style>
</head>
<body>
<main>
    <nav class="actions">
        <a href="{{ route('adventures.index') }}">العودة إلى المغامرات</a>
        <a href="{{ route('adventures.index', ['edit' => $adventure->id]) }}">تعديل المغامرة</a>
    </nav>

    <h1>{{ $adventure->title }}</h1>
    <p>{{ $adventure->description }}</p>
    <div class="details">
        <p><strong>رقم المنظم:</strong> {{ $adventure->activity_organizer_id }}</p>
        <p><strong>الصعوبة:</strong> {{ $adventure->difficulty_level }}</p>
        <p><strong>السعة:</strong> {{ $adventure->capacity }}</p>
        <p><strong>التصنيف:</strong> {{ $adventure->category }}</p>
        <p><strong>الموقع:</strong> {{ $adventure->location }}</p>
        <p><strong>المدة:</strong> {{ $adventure->duration_hours }} ساعة</p>
        <p><strong>السعر:</strong> {{ $adventure->price }}</p>
        <p><strong>الحالة:</strong> {{ $adventure->status }}</p>
        <p><strong>المتطلبات:</strong> {{ $adventure->requirements }}</p>
    </div>

    <section>
        <h2>المواعيد</h2>
        @if ($adventure->schedules->isNotEmpty())
            <table>
                <thead><tr><th>رقم الدليل</th><th>يبدأ</th><th>ينتهي</th><th>السعة</th><th>الحالة</th></tr></thead>
                <tbody>
                @foreach ($adventure->schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->guide_id }}</td>
                        <td>{{ $schedule->starts_at?->format('Y-m-d H:i') }}</td>
                        <td>{{ $schedule->ends_at?->format('Y-m-d H:i') }}</td>
                        <td>{{ $schedule->capacity }}</td>
                        <td>{{ $schedule->status }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="empty">لا توجد مواعيد مسجلة.</p>
        @endif
    </section>

    <section>
        <h2>الصور</h2>
        @if ($adventure->images->isNotEmpty())
            <div class="images">
                @foreach ($adventure->images as $image)
                    @if ($image->image_path)
                        <img src="{{ asset('images/' . $image->image_path) }}" alt="{{ $adventure->title }}">
                    @endif
                @endforeach
            </div>
        @else
            <p class="empty">لا توجد صور مسجلة.</p>
        @endif
    </section>
</main>
</body>
</html>
