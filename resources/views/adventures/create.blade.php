<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مغامرة | يلا جو</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #D95D39;
            --neutral: #1E293B;
            --muted: #64748B;
            --border: #E8ECF3;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 32px 16px;
            background: #F7F8FC;
            color: var(--neutral);
            font-family: 'Cairo', sans-serif;
        }

        .page {
            max-width: 760px;
            margin: auto;
            padding: 28px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 22px;
        }

        h1 { margin: 0; font-size: 24px; }
        a { color: var(--primary); text-decoration: none; font-weight: 700; }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .form-field { display: flex; flex-direction: column; gap: 5px; }
        .form-field.full { grid-column: 1 / -1; }
        label { font-size: 12px; font-weight: 700; }

        input, textarea, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            color: var(--neutral);
            font: inherit;
            font-size: 13px;
        }

        textarea { min-height: 90px; resize: vertical; }

        .form-errors {
            margin-bottom: 18px;
            padding: 12px 16px;
            border-radius: 8px;
            background: #FEE2E2;
            color: #B91C1C;
            font-size: 12px;
        }

        .form-errors ul { margin: 0; padding-right: 20px; }

        .actions { display: flex; gap: 10px; margin-top: 20px; }
        .button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 10px 18px;
            border: 0;
            border-radius: 8px;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .submit { background: var(--primary); color: #fff; }
        .cancel { background: #F1F5F9; color: var(--neutral); }

        @media (max-width: 600px) {
            body { padding: 12px; }
            .page { padding: 18px; }
            .form-grid { grid-template-columns: 1fr; }
            .form-field.full { grid-column: auto; }
            .page-heading { align-items: flex-start; }
        }
    </style>
</head>

<body>
    <main class="page">
        <div class="page-heading">
            <h1>إضافة مغامرة</h1>
            <a href="{{ route('adventures.index') }}">العودة إلى المغامرات</a>
        </div>

        @if ($errors->any())
            <div class="form-errors" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('adventures.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-field">
                    <label for="title">اسم المغامرة</label>
                    <input id="title" name="title" value="{{ old('title') }}" required>
                </div>
                <div class="form-field">
                    <label for="activity_organizer_id">رقم المنظم</label>
                    <input id="activity_organizer_id" type="number" name="activity_organizer_id" value="{{ old('activity_organizer_id') }}" required>
                </div>
                <div class="form-field full">
                    <label for="description">الوصف</label>
                    <textarea id="description" name="description" required>{{ old('description') }}</textarea>
                </div>
                <div class="form-field">
                    <label for="difficulty_level">الصعوبة</label>
                    <select id="difficulty_level" name="difficulty_level" required>
                        @foreach (['Easy' => 'سهل', 'Medium' => 'متوسط', 'Hard' => 'صعب'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('difficulty_level') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="capacity">السعة</label>
                    <input id="capacity" type="number" name="capacity" value="{{ old('capacity') }}" required>
                </div>
                <div class="form-field">
                    <label for="category">التصنيف</label>
                    <select id="category" name="category" required>
                        @foreach (['Hiking', 'Diving', 'Camping', 'Cyciling', 'Zaitoon day out', 'kayaking', 'climbing', 'Sky Diving', 'Blimping', 'Toursim'] as $category)
                            <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="location">الموقع</label>
                    <input id="location" name="location" value="{{ old('location') }}" required>
                </div>
                <div class="form-field">
                    <label for="duration_hours">المدة بالساعات</label>
                    <input id="duration_hours" type="number" step="0.01" name="duration_hours" value="{{ old('duration_hours') }}" required>
                </div>
                <div class="form-field">
                    <label for="price">السعر</label>
                    <input id="price" type="number" step="0.01" name="price" value="{{ old('price') }}" required>
                </div>
                <div class="form-field">
                    <label for="status">الحالة</label>
                    <select id="status" name="status" required>
                        @foreach (['Active', 'Suspended'] as $status)
                            <option value="{{ $status }}" @selected(old('status', 'Active') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field full">
                    <label for="requirements">المتطلبات</label>
                    <textarea id="requirements" name="requirements" required>{{ old('requirements') }}</textarea>
                </div>
            </div>

            <div class="actions">
                <button class="button submit" type="submit">حفظ المغامرة</button>
                <a class="button cancel" href="{{ route('adventures.index') }}">إلغاء</a>
            </div>
        </form>
    </main>
</body>

</html>
