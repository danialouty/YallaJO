<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل {{ $adventure->title }} | يلا جو</title>
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
            max-width: 1000px;
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

        h1, h2, h3, h4 { margin-top: 0; }
        h1 { margin-bottom: 0; font-size: 24px; }
        h2 { font-size: 19px; }
        h3 { font-size: 16px; }
        h4 { font-size: 14px; }
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

        .form-errors, .success {
            margin-bottom: 18px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 12px;
        }

        .form-errors { background: #FEE2E2; color: #B91C1C; }
        .success { background: #D1FAE5; color: #1B4332; }
        .form-errors ul { margin: 0; padding-right: 20px; }

        .actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; }
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
        .related-section { margin-top: 30px; padding-top: 22px; border-top: 1px solid var(--border); }
        .related-section > p { color: var(--muted); font-size: 13px; }
        .table-wrapper { overflow-x: auto; margin-bottom: 22px; }
        table { width: 100%; min-width: 850px; border-collapse: collapse; }
        th, td { padding: 9px; border-bottom: 1px solid var(--border); text-align: right; }
        th { background: #F7F8FC; font-size: 12px; }
        td input, td select { min-width: 120px; padding: 8px; }
        .image-edit-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 12px; margin-bottom: 18px; }
        .image-edit-item { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; padding: 12px; border: 1px solid var(--border); border-radius: 10px; }
        .image-edit-item label { flex: 1 1 100%; }
        .image-preview { width: 80px; height: 80px; border-radius: 9px; object-fit: cover; }

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
            <h1>تعديل المغامرة</h1>
            <a href="{{ route('adventures.index') }}">العودة إلى المغامرات</a>
        </div>

        @if (session('success'))
            <div class="success" role="status">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="form-errors" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('adventures.update', $adventure) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-field">
                    <label for="title">اسم المغامرة</label>
                    <input id="title" name="title" value="{{ old('title', $adventure->title) }}" required>
                </div>
                <div class="form-field">
                    <label for="activity_organizer_id">رقم المنظم</label>
                    <input id="activity_organizer_id" type="number" name="activity_organizer_id" value="{{ old('activity_organizer_id', $adventure->activity_organizer_id) }}" required>
                </div>
                <div class="form-field full">
                    <label for="description">الوصف</label>
                    <textarea id="description" name="description" required>{{ old('description', $adventure->description) }}</textarea>
                </div>
                <div class="form-field">
                    <label for="difficulty_level">الصعوبة</label>
                    <select id="difficulty_level" name="difficulty_level" required>
                        @foreach (['Easy', 'Medium', 'Hard'] as $value)
                            <option value="{{ $value }}" @selected(old('difficulty_level', $adventure->difficulty_level) === $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="capacity">السعة</label>
                    <input id="capacity" type="number" name="capacity" value="{{ old('capacity', $adventure->capacity) }}" required>
                </div>
                <div class="form-field">
                    <label for="category">التصنيف</label>
                    <select id="category" name="category" required>
                        @foreach (['Hiking', 'Diving', 'Camping', 'Cyciling', 'Zaitoon day out', 'kayaking', 'climbing', 'Sky Diving', 'Blimping', 'Toursim'] as $value)
                            <option value="{{ $value }}" @selected(old('category', $adventure->category) === $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="location">الموقع</label>
                    <input id="location" name="location" value="{{ old('location', $adventure->location) }}" required>
                </div>
                <div class="form-field">
                    <label for="duration_hours">المدة بالساعات</label>
                    <input id="duration_hours" type="number" step="0.01" name="duration_hours" value="{{ old('duration_hours', $adventure->duration_hours) }}" required>
                </div>
                <div class="form-field">
                    <label for="price">السعر</label>
                    <input id="price" type="number" step="0.01" name="price" value="{{ old('price', $adventure->price) }}" required>
                </div>
                <div class="form-field">
                    <label for="status">الحالة</label>
                    <select id="status" name="status" required>
                        @foreach (['Active', 'Suspended'] as $value)
                            <option value="{{ $value }}" @selected(old('status', $adventure->status) === $value)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field full">
                    <label for="requirements">المتطلبات</label>
                    <textarea id="requirements" name="requirements" required>{{ old('requirements', $adventure->requirements) }}</textarea>
                </div>
            </div>
            <div class="actions">
                <button class="button submit" type="submit">حفظ التعديلات</button>
                <a class="button cancel" href="{{ route('adventures.index') }}">إلغاء</a>
            </div>
        </form>

        <section class="related-section">
            <h2>المواعيد</h2>
            @if ($adventure->schedules->isNotEmpty())
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr><th>رقم الدليل</th><th>يبدأ</th><th>ينتهي</th><th>السعة</th><th>الحالة</th><th>حفظ</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($adventure->schedules as $schedule)
                                @php($scheduleFormId = 'schedule-form-' . $schedule->id)
                                <tr>
                                    <td><input form="{{ $scheduleFormId }}" type="number" name="guide_id" value="{{ $schedule->guide_id }}" required></td>
                                    <td><input form="{{ $scheduleFormId }}" type="datetime-local" name="starts_at" value="{{ $schedule->starts_at?->format('Y-m-d\\TH:i') }}" required></td>
                                    <td><input form="{{ $scheduleFormId }}" type="datetime-local" name="ends_at" value="{{ $schedule->ends_at?->format('Y-m-d\\TH:i') }}" required></td>
                                    <td><input form="{{ $scheduleFormId }}" type="number" name="capacity" value="{{ $schedule->capacity }}" required></td>
                                    <td>
                                        <select form="{{ $scheduleFormId }}" name="status">
                                            <option value="Active" @selected($schedule->status === 'Active')>Active</option>
                                            <option value="Suspended" @selected($schedule->status === 'Suspended')>Suspended</option>
                                        </select>
                                    </td>
                                    <td>
                                        <form id="{{ $scheduleFormId }}" action="{{ route('schedules.update', $schedule) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="adventure_id" value="{{ $adventure->id }}">
                                            <input type="hidden" name="_form_type" value="edit">
                                            <input type="hidden" name="_adventure_id" value="{{ $adventure->id }}">
                                            <button class="button submit" type="submit">حفظ</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>لا توجد مواعيد لهذه المغامرة بعد.</p>
            @endif

            <h3>إضافة موعد</h3>
            <form action="{{ route('schedules.store') }}" method="POST" class="form-grid">
                @csrf
                <input type="hidden" name="adventure_id" value="{{ $adventure->id }}">
                <input type="hidden" name="_form_type" value="edit">
                <input type="hidden" name="_adventure_id" value="{{ $adventure->id }}">
                <div class="form-field"><label for="guide_id">رقم الدليل</label><input id="guide_id" type="number" name="guide_id" required></div>
                <div class="form-field"><label for="schedule_capacity">السعة</label><input id="schedule_capacity" type="number" name="capacity" required></div>
                <div class="form-field"><label for="starts_at">يبدأ في</label><input id="starts_at" type="datetime-local" name="starts_at" required></div>
                <div class="form-field"><label for="ends_at">ينتهي في</label><input id="ends_at" type="datetime-local" name="ends_at" required></div>
                <div class="form-field">
                    <label for="schedule_status">الحالة</label>
                    <select id="schedule_status" name="status">
                        <option value="Active">Active</option>
                        <option value="Suspended">Suspended</option>
                    </select>
                </div>
                <div class="form-field"><button class="button submit" type="submit">إضافة الموعد</button></div>
            </form>
        </section>

        <section class="related-section">
            <h2>صور المغامرة</h2>
            <div class="image-edit-list">
                @forelse ($adventure->images as $image)
                    <form action="{{ route('images.update', $image) }}" method="POST" enctype="multipart/form-data" class="image-edit-item">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_form_type" value="edit">
                        <input type="hidden" name="_adventure_id" value="{{ $adventure->id }}">
                        <img src="{{ asset('images/' . $image->image_path) }}" alt="{{ $adventure->title }}" class="image-preview">
                        <label>استبدال الصورة<input type="file" name="image_path" accept="image/jpeg,image/png,image/webp" required></label>
                        <button class="button submit" type="submit">تحديث الصورة</button>
                    </form>
                @empty
                    <p>لا توجد صور لهذه المغامرة بعد.</p>
                @endforelse
            </div>
            <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data" class="form-grid">
                @csrf
                <input type="hidden" name="adventure_id" value="{{ $adventure->id }}">
                <input type="hidden" name="_form_type" value="edit">
                <input type="hidden" name="_adventure_id" value="{{ $adventure->id }}">
                <div class="form-field full">
                    <label for="image_files">إضافة صور</label>
                    <input id="image_files" type="file" name="image_files[]" accept="image/jpeg,image/png,image/webp" multiple required>
                </div>
                <div class="form-field"><button class="button submit" type="submit">رفع الصور</button></div>
            </form>
        </section>
    </main>
</body>

</html>
