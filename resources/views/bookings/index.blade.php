@extends('layouts.dashboard')

@section('dashboard-theme', 'adventurer')
@section('dashboard-name', 'بوابة المغامر')
@section('dashboard-title', 'إدارة حجوزاتي')

@section('dashboard-header-actions')
    <button type="button" data-open-create-modal
            class="btn-yalla-primary inline-flex items-center justify-center gap-2 px-5 py-2.5 font-bold text-sm rounded-xl shadow-sm cursor-pointer">
        <span>+</span>
        <span>حجز مغامرة جديدة</span>
    </button>
@endsection

@section('content')
    <div class="yallajo-bookings-wrapper">

        <!-- رسائل النجاح والخطأ -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- بطاقات الإحصائيات السريعة (KPI Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium">إجمالي الحجوزات</p>
                <p class="text-2xl font-extrabold text-gray-900 mt-2">
                    {{ $data->count() }} <span class="text-xs font-normal text-gray-400">حجز</span>
                </p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium">بانتظار موافقة المنظم</p>
                <p class="text-2xl font-extrabold text-amber-600 mt-2">
                    {{ $data->where('status', 'Pending')->count() }}
                </p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium">الحجوزات المؤكدة</p>
                <p class="text-2xl font-extrabold text-[#2D5041] mt-2">
                    {{ $data->where('status', 'Confirmed')->count() }}
                </p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium">إجمالي قيمة الحجوزات</p>
                <p class="text-2xl font-extrabold text-[#A94B2B] mt-2">
                    {{ number_format($data->sum('total_amount'), 2) }} <span class="text-xs font-normal">د.أ</span>
                </p>
            </div>
        </div>

        <!-- جدول الحجوزات -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <h2 class="font-bold text-gray-800">سجل حجوزاتك</h2>
                <span class="text-xs text-gray-400">يمكنك تعديل عدد المشاركين طالما الحجز "قيد الانتظار"</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100 text-xs text-gray-500">
                            <th class="py-3.5 px-6 font-semibold">المغامرة</th>
                            <th class="py-3.5 px-4 font-semibold">عدد المشاركين</th>
                            <th class="py-3.5 px-4 font-semibold">سعر الفرد</th>
                            <th class="py-3.5 px-4 font-semibold">الإجمالي</th>
                            <th class="py-3.5 px-4 font-semibold">حالة الحجز</th>
                            <th class="py-3.5 px-6 font-semibold text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($data as $booking)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="py-4 px-6">
                                    <p class="font-bold text-gray-900">{{ $booking->adventure->title ?? 'مغامرة #' . $booking->adventure_id }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">تاريخ الطلب: {{ $booking->created_at->format('Y/m/d') }}</p>
                                </td>

                                <td class="py-4 px-4 font-medium">{{ $booking->participants_count }} مشاركين</td>
                                <td class="py-4 px-4 text-gray-600">{{ number_format($booking->unit_price, 2) }} د.أ</td>
                                <td class="py-4 px-4 font-extrabold text-gray-900">{{ number_format($booking->total_amount, 2) }} د.أ</td>

                                <td class="py-4 px-4">
                                    @if ($booking->status === 'Confirmed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            مؤكد من المنظم
                                        </span>
                                    @elseif ($booking->status === 'Cancelled')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                            ملغي
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                            قيد الانتظار
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- صفحة Show المستقلة -->
                                        <a href="{{ route('bookings.show', $booking->id) }}"
                                           class="px-3 py-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                                            التفاصيل
                                        </a>

                                        <!-- زر Edit Pop-up (يظهر فقط إذا كانت الحالة Pending) -->
                                        @if ($booking->status === 'Pending')
                                            <button type="button"
                                                    data-open-edit-modal
                                                    data-id="{{ $booking->id }}"
                                                    data-title="{{ $booking->adventure->title ?? 'مغامرة #' . $booking->adventure_id }}"
                                                    data-unit-price="{{ $booking->unit_price }}"
                                                    data-count="{{ $booking->participants_count }}"
                                                    class="px-3 py-1.5 text-xs font-semibold bg-[#E8F1ED] hover:bg-[#d5e6de] text-[#2D5041] rounded-lg transition cursor-pointer">
                                                تعديل
                                            </button>
                                        @endif

                                        <!-- إلغاء الحجز -->
                                        <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST"
                                              onsubmit="return confirm('هل أنت متأكد من إلغاء وحذف هذا الحجز؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 text-xs font-semibold bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition cursor-pointer">
                                                إلغاء
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <p class="text-gray-500 font-medium mb-3">لا يوجد لديك أي حجوزات حالياً</p>
                                    <button type="button" data-open-create-modal class="text-sm font-bold text-[#A94B2B] underline cursor-pointer">
                                        احجز مغامرتك الأولى الآن
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==================== POP-UP 1: إنشاء حجز جديد ==================== -->
        <div id="createBookingModal" class="booking-modal-backdrop {{ $errors->any() && !old('_method') ? '' : 'hidden' }}">
            <div class="booking-modal-card">
                <div class="bg-[#2D5041] text-white px-6 py-4 flex items-center justify-between">
                    <h3 class="font-bold text-lg">حجز مغامرة جديدة</h3>
                    <button type="button" data-close-create-modal class="text-white/70 hover:text-white text-2xl leading-none cursor-pointer">&times;</button>
                </div>

                <form action="{{ route('bookings.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    @if ($errors->any() && !old('_method'))
                        <div class="p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label for="create_adventure_id" class="block text-sm font-bold text-gray-700 mb-1.5">اختر المغامرة</label>
                        <select name="adventure_id" id="create_adventure_id" required
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-[#2D5041] focus:ring-[#2D5041]">
                            <option value="" data-price="0">-- اختر من المغامرات المتاحة --</option>
                            @foreach ($adventures as $adventure)
                                <option value="{{ $adventure->id }}"
                                        data-price="{{ $adventure->price }}"
                                        {{ old('adventure_id') == $adventure->id ? 'selected' : '' }}>
                                    {{ $adventure->title }} ({{ $adventure->price }} دينار / للشخص)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="create_participants_count" class="block text-sm font-bold text-gray-700 mb-1.5">عدد المشاركين</label>
                        <div class="flex items-center justify-between border border-gray-300 rounded-xl px-4 py-2">
                            <button type="button" id="create_minus_btn" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 font-bold text-lg cursor-pointer">-</button>
                            <input type="number" name="participants_count" id="create_participants_count" value="{{ old('participants_count', 1) }}" min="1" required
                                   class="w-20 text-center border-0 font-bold text-lg focus:ring-0">
                            <button type="button" id="create_plus_btn" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 font-bold text-lg cursor-pointer">+</button>
                        </div>
                    </div>

                    <div class="bg-[#F8F9FA] p-4 rounded-xl border border-gray-200 flex items-center justify-between">
                        <span class="text-sm text-gray-600 font-medium">الإجمالي المطلوب:</span>
                        <span class="text-xl font-extrabold text-[#A94B2B]">
                            <span id="create_total_amount">0.00</span> دينار أردني
                        </span>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn-yalla-primary flex-1 py-2.5 font-bold text-sm rounded-xl cursor-pointer">
                            تأكيد الحجز الآن
                        </button>
                        <button type="button" data-close-create-modal class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl cursor-pointer">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ==================== POP-UP 2: تعديل الحجز ==================== -->
        <div id="editBookingModal" class="booking-modal-backdrop hidden">
            <div class="booking-modal-card">
                <div class="bg-[#2D5041] text-white px-6 py-4 flex items-center justify-between">
                    <h3 class="font-bold text-lg">تعديل الحجز <span id="edit_booking_id_label"></span></h3>
                    <button type="button" data-close-edit-modal class="text-white/70 hover:text-white text-2xl leading-none cursor-pointer">&times;</button>
                </div>

                <form id="editBookingForm" action="" method="POST" class="p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">المغامرة المختارة</label>
                        <div id="edit_adventure_title" class="p-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-800"></div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">حالة الطلب الحالية</label>
                        <div class="p-2.5 bg-amber-50 border border-amber-200 rounded-xl text-xs font-bold text-amber-800">
                            قيد الانتظار (بانتظار موافقة منظم الرحلة)
                        </div>
                    </div>

                    <div>
                        <label for="edit_participants_count" class="block text-sm font-bold text-gray-700 mb-1.5">تعديل عدد المشاركين</label>
                        <div class="flex items-center justify-between border border-gray-300 rounded-xl px-4 py-2">
                            <button type="button" id="edit_minus_btn" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 font-bold text-lg cursor-pointer">-</button>
                            <input type="number" name="participants_count" id="edit_participants_count" value="1" min="1" required
                                   class="w-20 text-center border-0 font-bold text-lg focus:ring-0">
                            <button type="button" id="edit_plus_btn" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 font-bold text-lg cursor-pointer">+</button>
                        </div>
                    </div>

                    <div class="bg-[#F8F9FA] p-4 rounded-xl border border-gray-200 flex items-center justify-between">
                        <span class="text-sm text-gray-600 font-medium">الإجمالي بعد التعديل:</span>
                        <span class="text-xl font-extrabold text-[#2D5041]">
                            <span id="edit_total_amount">0.00</span> دينار أردني
                        </span>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn-yalla-secondary flex-1 py-2.5 font-bold text-sm rounded-xl cursor-pointer">
                            حفظ التعديلات
                        </button>
                        <button type="button" data-close-edit-modal class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl cursor-pointer">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <style>
        [data-dashboard-layout] > div > div > div.min-h-20 {
    display: none;
}
        .yallajo-bookings-wrapper {
            font-family: 'Cairo', sans-serif;
        }

        :root {
            --yalla-green: #2D5041;
            --yalla-green-light: #E8F1ED;
            --yalla-rust: #A94B2B;
            --yalla-rust-hover: #8E3D22;
        }

        .btn-yalla-primary {
            background-color: var(--yalla-rust);
            color: #ffffff;
            transition: background-color 0.2s ease;
        }

        .btn-yalla-primary:hover {
            background-color: var(--yalla-rust-hover);
        }

        .btn-yalla-secondary {
            background-color: var(--yalla-green);
            color: #ffffff;
            transition: opacity 0.2s ease;
        }

        .btn-yalla-secondary:hover {
            opacity: 0.92;
        }

        .booking-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 50;
            background-color: rgba(17, 24, 39, 0.55);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .booking-modal-backdrop.hidden {
            display: none;
        }

        .booking-modal-card {
            background: #ffffff;
            border-radius: 1rem;
            width: 100%;
            max-width: 28rem;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(10px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Pop-up إنشاء حجز جديد
            const createModal = document.getElementById('createBookingModal');
            const openCreateBtns = document.querySelectorAll('[data-open-create-modal]');
            const closeCreateBtns = document.querySelectorAll('[data-close-create-modal]');

            const adventureSelect = document.getElementById('create_adventure_id');
            const createCountInput = document.getElementById('create_participants_count');
            const createTotalSpan = document.getElementById('create_total_amount');

            function updateCreateTotal() {
                if (!adventureSelect || !createCountInput || !createTotalSpan) return;
                const selectedOption = adventureSelect.options[adventureSelect.selectedIndex];
                const price = parseFloat(selectedOption?.dataset.price || 0);
                const count = parseInt(createCountInput.value || 1);
                createTotalSpan.textContent = (price * count).toFixed(2);
            }

            openCreateBtns.forEach(btn => btn.addEventListener('click', () => {
                createModal?.classList.remove('hidden');
                updateCreateTotal();
            }));

            closeCreateBtns.forEach(btn => btn.addEventListener('click', () => {
                createModal?.classList.add('hidden');
            }));

            adventureSelect?.addEventListener('change', updateCreateTotal);
            createCountInput?.addEventListener('input', updateCreateTotal);

            document.getElementById('create_minus_btn')?.addEventListener('click', () => {
                if (parseInt(createCountInput.value) > 1) {
                    createCountInput.value = parseInt(createCountInput.value) - 1;
                    updateCreateTotal();
                }
            });

            document.getElementById('create_plus_btn')?.addEventListener('click', () => {
                createCountInput.value = parseInt(createCountInput.value) + 1;
                updateCreateTotal();
            });

            // 2. Pop-up تعديل الحجز
            const editModal = document.getElementById('editBookingModal');
            const editForm = document.getElementById('editBookingForm');
            const editTitleEl = document.getElementById('edit_adventure_title');
            const editBookingIdSpan = document.getElementById('edit_booking_id_label');
            const editCountInput = document.getElementById('edit_participants_count');
            const editTotalSpan = document.getElementById('edit_total_amount');
            const openEditBtns = document.querySelectorAll('[data-open-edit-modal]');
            const closeEditBtns = document.querySelectorAll('[data-close-edit-modal]');

            let currentEditUnitPrice = 0;

            function updateEditTotal() {
                if (!editCountInput || !editTotalSpan) return;
                const count = parseInt(editCountInput.value || 1);
                editTotalSpan.textContent = (currentEditUnitPrice * count).toFixed(2);
            }

            openEditBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    const title = btn.dataset.title;
                    const count = btn.dataset.count;
                    currentEditUnitPrice = parseFloat(btn.dataset.unitPrice || 0);

                    editForm.action = `/bookings/${id}`;
                    editBookingIdSpan.textContent = `#${id}`;
                    editTitleEl.textContent = title;
                    editCountInput.value = count;

                    updateEditTotal();
                    editModal?.classList.remove('hidden');
                });
            });

            closeEditBtns.forEach(btn => btn.addEventListener('click', () => {
                editModal?.classList.add('hidden');
            }));

            editCountInput?.addEventListener('input', updateEditTotal);

            document.getElementById('edit_minus_btn')?.addEventListener('click', () => {
                if (parseInt(editCountInput.value) > 1) {
                    editCountInput.value = parseInt(editCountInput.value) - 1;
                    updateEditTotal();
                }
            });

            document.getElementById('edit_plus_btn')?.addEventListener('click', () => {
                editCountInput.value = parseInt(editCountInput.value) + 1;
                updateEditTotal();
            });

            // إغلاق النافذة عند الضغط بالخارج
            [createModal, editModal].forEach(modal => {
                modal?.addEventListener('click', (e) => {
                    if (e.target === modal) modal.classList.add('hidden');
                });
            });
        });
    </script>
@endsection