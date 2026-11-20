<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المغامرات القادمة ونسبة الإشغال</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --primary: #D95D39;
            --secondary: #1B4332;
            --tertiary: #D97706;
            --neutral: #1E293B;

            --background: #F7F8FC;
            --white: #FFFFFF;
            --muted: #64748B;
            --border: #E8ECF3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--background);
            color: var(--neutral);
            padding: 32px;
        }

        .dashboard {
            max-width: 1250px;
            margin: auto;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        /* Header */

        .header {
            padding: 22px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .header > div:first-of-type:not(.alert) {
            flex: 1 1 260px;
        }

        .header .alert {
            flex: 0 0 100%;
            order: -1;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .alert-success {
            color: var(--secondary);
            background: #D1FAE5;
            border: 1px solid #A7F3D0;
        }

        .header h1 {
            font-size: 25px;
            font-weight: 800;
            color: #111827;
        }

        .header p {
            color: var(--muted);
            font-size: 12px;
            margin-top: 3px;
        }

        /* Filters */

        .filters {
            display: flex;
            gap: 5px;
            background: #F1F3F9;
            padding: 4px;
            border-radius: 12px;
        }

        .filter {
            padding: 9px 15px;
            text-decoration: none;
            color: var(--neutral);
            font-size: 12px;
            font-weight: 600;
            border-radius: 9px;
            white-space: nowrap;
            transition: .2s;
        }

        .filter:hover,
        .filter.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 1px 5px rgba(0, 0, 0, .08);
        }

        /* Table */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 980px;
        }

        thead {
            background: #F7F8FC;
        }

        th {
            padding: 16px 14px;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            text-align: center;
            white-space: nowrap;
        }

        th:first-child {
            text-align: right;
            padding-right: 28px;
        }

        td {
            padding: 22px 14px;
            border-bottom: 1px solid #F1F3F7;
            vertical-align: middle;
            text-align: center;
        }

        tbody tr {
            transition: background .2s;
        }

        tbody tr:hover {
            background: #FAFBFD;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        /* Adventure information */

        .adventure-info {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 270px;
            text-align: right;
        }

        .adventure-image {
            width: 66px;
            height: 66px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .adventure-image-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F1F5F9;
            color: #94A3B8;
        }

        .adventure-details h3 {
            font-size: 15px;
            font-weight: 800;
            color: var(--neutral);
            line-height: 1.8;
        }

        .adventure-details p {
            font-size: 11px;
            color: var(--muted);
            margin-top: 3px;
        }

        .location {
            color: var(--primary);
            font-size: 11px;
            margin-left: 4px;
        }

        /* Category */

        .category {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
            background: #FFE4D6;
            color: #A94326;
        }

        /* Date */

        .date-info {
            font-size: 12px;
            line-height: 1.8;
            color: var(--neutral);
            min-width: 105px;
        }

        .date-info strong {
            display: block;
            font-size: 13px;
        }

        .date-info span {
            display: block;
            color: var(--muted);
            font-size: 11px;
        }

        /* Occupancy */

        .occupancy {
            min-width: 170px;
            text-align: center;
        }

        .occupancy-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 9px;
            font-size: 11px;
            font-weight: 700;
        }

        .percentage {
            color: var(--primary);
        }

        .percentage.green {
            color: var(--secondary);
        }

        .percentage.orange {
            color: var(--tertiary);
        }

        .progress {
            width: 100%;
            height: 7px;
            background: #E8EDF5;
            border-radius: 20px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 20px;
            background: var(--primary);
        }

        .progress-bar.green {
            background: var(--secondary);
        }

        .progress-bar.orange {
            background: #FDBA74;
        }

        .occupancy small {
            display: block;
            margin-top: 7px;
            font-size: 10px;
            color: var(--muted);
        }

        /* Price */

        .price-info {
            min-width: 90px;
        }

        .price {
            font-size: 18px;
            font-weight: 800;
            color: var(--neutral);
        }

        .currency {
            font-size: 11px;
        }

        .price-info small {
            display: block;
            color: var(--muted);
            font-size: 10px;
        }

        /* Status */

        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
            background: #C8F1DC;
            color: var(--secondary);
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
        }

        /* Footer */

        .footer {
            padding: 18px 25px;
            background: #FAFBFD;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .footer p {
            font-size: 12px;
            color: var(--muted);
        }

        .footer a {
            color: var(--primary);
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .footer nav {
            direction: ltr;
        }

        .footer nav svg {
            width: 18px;
            height: 18px;
        }

        /* Actions Column */
        td:last-child {
            white-space: nowrap;
        }

        /* Actions Container */
        td form {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* Action Buttons */
        td .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;

            padding: 7px 12px;
            border-radius: 8px;
            border: none;

            font-family: 'Cairo', sans-serif;
            font-size: 11px;
            font-weight: 700;

            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        /* View */
        td .btn-info {
            background: #E2E8F0;
            color: #1E293B;
        }

        /* Edit */
        td .btn-primary {
            background: #FFF0E8;
            color: #D95D39;
        }

        /* Delete */
        td .btn-danger {
            background: #FEE2E2;
            color: #DC2626;
        }

        /* Hover Effects */
        td .btn:hover {
            transform: translateY(-2px);
            filter: brightness(0.95);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        }

        /* Icons */
        td .btn i {
            font-size: 12px;
        }

        /* Delete hover */
        td .btn-danger:hover {
            background: #FECACA;
        }

        /* Edit hover */
        td .btn-primary:hover {
            background: #FFE0D0;
        }

        /* View hover */
        td .btn-info:hover {
            background: #CBD5E1;
        }

        /* Responsive */

        @media (max-width: 768px) {
            body {
                padding: 12px;
            }

            .header {
                padding: 18px;
                align-items: stretch;
            }

            .header h1 {
                font-size: 20px;
            }

            .header > div:first-of-type:not(.alert) {
                flex-basis: 100%;
            }

            .filters {
                width: 100%;
                overflow-x: auto;
            }

            .filter {
                flex: 1;
                text-align: center;
            }

            .footer {
                padding: 15px;
            }

            td .btn {
                padding: 6px 8px;
                font-size: 10px;
            }

            td form {
                gap: 4px;
            }

            .add-adventure-container {
                width: 100%;
            }

            .add-adventure {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 8px;
            }

            .header {
                padding: 14px;
                gap: 12px;
            }

            .filters {
                gap: 3px;
            }

            .filter {
                padding: 8px 10px;
                font-size: 10px;
            }
        }

        /* Add Adventure Button */
        .add-adventure {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            padding: 10px 18px;
            background: #D95D39;
            color: #fff;

            border: none;
            border-radius: 10px;

            font-family: 'Cairo', sans-serif;
            font-size: 13px;
            font-weight: 700;

            text-decoration: none;
            transition: all 0.3s ease;
        }

        /* Hover */
        .add-adventure:hover {
            background: #BF4B2B;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(217, 93, 57, 0.25);
        }

        /* Icon */
        .add-adventure i {
            font-size: 14px;
        }

        /* Container */
        .add-adventure-container {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 0;
        }

        .empty-state {
            padding: 48px 20px;
            color: var(--muted);
        }

        .adventure-modal {
            width: min(720px, calc(100% - 28px));
            max-height: calc(100vh - 40px);
            margin: auto;
            padding: 0;
            border: 0;
            border-radius: 16px;
            color: var(--neutral);
            box-shadow: 0 24px 70px rgba(15, 23, 42, .28);
        }

        .adventure-modal::backdrop { background: rgba(15, 23, 42, .58); }
        .modal-content { padding: 24px; overflow-y: auto; }
        .modal-heading { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
        .modal-heading h2 { font-size: 21px; }
        .modal-close { border: 0; background: transparent; font-size: 25px; cursor: pointer; color: var(--muted); }
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .form-field { display: flex; flex-direction: column; gap: 5px; text-align: right; }
        .form-field.full { grid-column: 1 / -1; }
        .form-field label { font-size: 12px; font-weight: 700; }
        .form-field input, .form-field textarea, .form-field select {
            width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 8px;
            font: inherit; font-size: 13px; background: #fff; color: var(--neutral);
        }
        .form-field textarea { min-height: 76px; resize: vertical; }
        .modal-actions { display: flex; gap: 10px; margin-top: 20px; }
        .modal-actions button { border: 0; border-radius: 8px; padding: 10px 18px; font: inherit; font-weight: 700; cursor: pointer; }
        .modal-submit { background: var(--primary); color: white; }
        .modal-cancel { background: #F1F5F9; color: var(--neutral); }
        .form-errors { margin-bottom: 15px; padding: 12px; border-radius: 8px; background: #FEE2E2; color: #B91C1C; font-size: 12px; }
        .edit-related-section { margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--border); }
        .edit-related-section h3 { margin-bottom: 14px; }
        .edit-related-section h4 { margin: 20px 0 12px; }
        .edit-related-section table { min-width: 850px; }
        .edit-related-section td { padding: 8px; }
        .edit-related-section input, .edit-related-section select { max-width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: 6px; font: inherit; }
        .image-edit-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 12px; margin-bottom: 18px; }
        .image-edit-item { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; padding: 12px; border: 1px solid var(--border); border-radius: 10px; }
        .image-edit-item label { flex: 1 1 100%; font-size: 12px; }
        .image-edit-item .adventure-image { width: 80px; height: 80px; }
        @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } .form-field.full { grid-column: auto; } .modal-content { padding: 18px; } }
    </style>
</head>

<body>



    <div class="dashboard">

        <!-- Header -->
        <div class="header">

            @session('success')
                <div class="alert alert-success" role="alert"> {{ $value }} </div>
            @endsession

            <div>
                <h1>المغامرات</h1>
                <p>متابعة دقيقة لقوائم الحضور وتأكيدات المقاعد والإيرادات المباشرة</p>
            </div>


            <div class="filters">
                <a href="#" class="filter">مكتملة (1)</a>
                <a href="#" class="filter">قيد الانطلاق (3)</a>
                <a href="#" class="filter active">جميع المغامرات (6)</a>
            </div>

            <div class="add-adventure-container">

                <a class="add-adventure" href="{{ route('adventures.create') }}">
                    <i class="fa fa-plus"></i>
                    اضف مغامرة
                </a>

            </div>

        </div>

        <!-- Adventures Table -->
        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>اسم المغامرة والموقع</th>
                        <th>التصنيف</th>
                        <th>المقاعد ونسبة الإشغال</th>
                        <th>السعر</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($adventures as $adventure)

                        <tr>

                            <!-- Adventure -->
                            <td>
                                <div class="adventure-info">
                                    @php($adventureImage = $adventure->images->first())

                                    @if ($adventureImage && $adventureImage->image_path)
                                        <img src="{{ asset('images/' . $adventureImage->image_path) }}"
                                            alt="{{ $adventure->title }}"
                                            class="adventure-image">
                                    @else
                                        <div class="adventure-image adventure-image-placeholder"
                                            aria-label="لا توجد صورة">
                                            <i class="fa-solid fa-mountain-sun"></i>
                                        </div>
                                    @endif

                                    <div class="adventure-details">
                                        <h3>{{ $adventure['title'] }}</h3>

                                        <p>
                                            <span class="location">⌖</span>
                                            {{ $adventure['location'] }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            <!-- Category -->
                            <td>
                                <span class="category">
                                    {{ $adventure['category'] }}
                                </span>
                            </td>


                            <!-- Occupancy -->
                            <td>
                                <div class="occupancy">
                                    {{ $adventure['capacity'] }} مقعد
                                </div>
                            </td>

                            <!-- Price -->
                            <td>
                                <div class="price-info">

                                    <div class="price">
                                        {{ number_format($adventure['price']) }}
                                        <span class="currency">د.أ</span>
                                    </div>

                                </div>
                            </td>

                            <!-- Status -->
                            <td>

                                <span class="status">

                                    <span class="status-dot"></span>

                                    {{ $adventure['status'] }}

                                </span>

                            </td>

                            {{-- Actions --}}
                            <td>

                                <form action="{{ route('adventures.destroy', $adventure->id) }}" method="POST">

                                    <a class="btn btn-info btn-sm" href="{{ route('adventures.show', $adventure->id) }}">
                                        <i class="fa-solid fa-list"></i> عرض
                                    </a>

                                    <a class="btn btn-primary btn-sm" href="{{ route('adventures.edit', $adventure) }}">
                                        <i class="fa-solid fa-pen-to-square"></i> تعديل
                                    </a>

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this order?')">

                                        <i class="fa-solid fa-trash"></i> حذف

                                    </button>

                                </form>

                            </td>


                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                لا توجد مغامرات للعرض.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="footer">

            <p>
                عرض {{ count($adventures) }} مغامرات
            </p>

            <div>
                {{ $adventures->links() }}
            </div>

        </div>

    </div>

    @if ($optionalAdventure)
        <dialog class="adventure-modal" id="optional-adventure-modal" dir="rtl">
            <div class="modal-content">
                <div class="modal-heading">
                    <div><h2>المرحلة الثانية: المواعيد والصور</h2><p>{{ $optionalAdventure->title }}</p></div>
                    <button class="modal-close" type="button" aria-label="إغلاق">&times;</button>
                </div>
                <p>تم إنشاء المغامرة رقم {{ $optionalAdventure->id }}. أضف المواعيد والصور اختيارياً، أو أنهِ العملية.</p>
                @if ($errors->any() && old('_form_type') === 'optional' && (int) old('_adventure_id') === $optionalAdventure->id)
                    <div class="form-errors"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif

                <section class="edit-related-section">
                    <h3>المواعيد (اختياري)</h3>
                    <form action="{{ route('schedules.store') }}" method="POST" class="form-grid">
                        @csrf
                        <input type="hidden" name="adventure_id" value="{{ $optionalAdventure->id }}">
                        <input type="hidden" name="_form_type" value="optional">
                        <input type="hidden" name="_adventure_id" value="{{ $optionalAdventure->id }}">
                        <div class="form-field"><label>رقم الدليل</label><input type="number" name="guide_id" value="{{ old('_form_type') === 'optional' ? old('guide_id') : '' }}" required></div>
                        <div class="form-field"><label>سعة الموعد</label><input type="number" name="capacity" value="{{ old('_form_type') === 'optional' ? old('capacity') : '' }}" required></div>
                        <div class="form-field"><label>يبدأ في</label><input type="datetime-local" name="starts_at" value="{{ old('_form_type') === 'optional' ? old('starts_at') : '' }}" required></div>
                        <div class="form-field"><label>ينتهي في</label><input type="datetime-local" name="ends_at" value="{{ old('_form_type') === 'optional' ? old('ends_at') : '' }}" required></div>
                        <div class="form-field"><label>الحالة</label><select name="status"><option value="Active">Active</option><option value="Suspended">Suspended</option></select></div>
                        <div class="form-field"><button class="modal-submit" type="submit">إضافة الموعد</button></div>
                    </form>
                </section>

                <section class="edit-related-section">
                    <h3>الصور (اختياري)</h3>
                    <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data" class="form-grid">
                        @csrf
                        <input type="hidden" name="adventure_id" value="{{ $optionalAdventure->id }}">
                        <input type="hidden" name="_form_type" value="optional">
                        <input type="hidden" name="_adventure_id" value="{{ $optionalAdventure->id }}">
                        <div class="form-field full"><label>اختر الصور</label><input type="file" name="image_files[]" accept="image/jpeg,image/png,image/webp" multiple required></div>
                        <div class="form-field"><button class="modal-submit" type="submit">رفع الصور</button></div>
                    </form>
                </section>

                <div class="modal-actions">
                    <a class="modal-cancel" style="text-decoration:none" href="{{ route('adventures.index') }}">إنهاء</a>
                </div>
            </div>
        </dialog>
    @endif


    <script>
        document.querySelectorAll('.adventure-modal').forEach((modal) => {
            modal.querySelectorAll('.modal-close, [data-close-modal]').forEach((button) => button.addEventListener('click', () => modal.close()));
            modal.addEventListener('click', (event) => { if (event.target === modal) modal.close(); });
        });
        @if ($optionalAdventure && request('phase') === 'optional')
            document.getElementById('optional-adventure-modal').showModal();
        @endif
    </script>

</body>

</html>
