<x-app-layout>
    <div dir="rtl" class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">إدارة المدفوعات</h1>
                <p class="text-sm text-gray-500 mt-1">تابع جميع المدفوعات وحالات تأكيدها في YallaJO</p>
            </div>

        </div>

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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium">إجمالي المدفوعات</p>
                <p class="text-2xl font-extrabold text-gray-900 mt-2">
                    {{ $payments->count() }} <span class="text-xs font-normal text-gray-400">دفعة</span>
                </p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium">بانتظار التأكيد</p>
                <p class="text-2xl font-extrabold text-amber-600 mt-2">
                    {{ $payments->where('status', 'Pending')->count() }}
                </p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium">مدفوعات مؤكدة</p>
                <p class="text-2xl font-extrabold text-[#2D5041] mt-2">
                    {{ $payments->where('status', 'Confirmed')->count() }}
                </p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium">إجمالي المبالغ</p>
                <p class="text-2xl font-extrabold text-[#A94B2B] mt-2">
                    {{ number_format($payments->sum('total_amount'), 2) }} <span class="text-xs font-normal">د.أ</span>
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800">سجل المدفوعات</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100 text-xs text-gray-500">
                            <th class="py-3.5 px-6 font-semibold">رقم الدفعة</th>
                            <th class="py-3.5 px-4 font-semibold">رقم الحجز</th>
                            <th class="py-3.5 px-4 font-semibold">المبلغ</th>
                            <th class="py-3.5 px-4 font-semibold">طريقة الدفع</th>
                            <th class="py-3.5 px-4 font-semibold">الحالة</th>
                            <th class="py-3.5 px-4 font-semibold">تاريخ الدفع</th>
                            <th class="py-3.5 px-6 font-semibold text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($payments as $payment)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="py-4 px-6 font-bold text-gray-900">#{{ $payment->id }}</td>
                                <td class="py-4 px-4 text-gray-600">حجز #{{ $payment->booking_id }}</td>
                                <td class="py-4 px-4 font-extrabold text-[#A94B2B]">
                                    {{ number_format($payment->total_amount, 2) }} د.أ
                                </td>
                                <td class="py-4 px-4 text-gray-700">
                                    {{ $payment->method === 'Visa' ? 'بطاقة فيزا' : 'الدفع نقدًا' }}
                                </td>
                                <td class="py-4 px-4">
                                    @if ($payment->status === 'Confirmed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">مؤكد</span>
                                    @elseif ($payment->status === 'Canceled')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">ملغي</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">قيد الانتظار</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-gray-600">
                                    {{ $payment->paid_at ? $payment->paid_at->format('Y/m/d') : 'لم يتم الدفع بعد' }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('payments.show', $payment->id) }}"
                                           class="px-3 py-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                                            التفاصيل
                                        </a>
                                        <a href="{{ route('payments.edit', $payment->id) }}"
                                           class="px-3 py-1.5 text-xs font-semibold bg-[#E8F1ED] hover:bg-[#d5e6de] text-[#2D5041] rounded-lg transition">
                                            تعديل
                                        </a>
                                        <form action="{{ route('payments.destroy', $payment->id) }}" method="POST"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الدفعة؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 text-xs font-semibold bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition cursor-pointer">
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 px-6 text-center text-sm text-gray-500">
                                    لا توجد مدفوعات مسجلة حاليًا.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .btn-yalla-primary {
            background-color: #A94B2B;
            color: #ffffff;
            transition: background-color 0.2s ease;
        }
        .btn-yalla-primary:hover { background-color: #8E3D22; }
    </style>

</x-app-layout>
