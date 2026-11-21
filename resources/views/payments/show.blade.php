<x-app-layout>
    <div dir="rtl" class="py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <a href="{{ route('payments.index') }}" class="text-sm font-semibold text-[#A94B2B] hover:text-[#8E3D22]">← العودة إلى المدفوعات</a>
                <h1 class="text-2xl font-extrabold text-gray-900 mt-3">تفاصيل الدفعة #{{ $payment->id }}</h1>
            </div>

            @if ($payment->status === 'Confirmed')
                <span class="inline-flex px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">مؤكد</span>
            @elseif ($payment->status === 'Canceled')
                <span class="inline-flex px-4 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700">ملغي</span>
            @else
                <span class="inline-flex px-4 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">قيد الانتظار</span>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="bg-[#2D5041] text-white p-6">
                <p class="text-sm text-emerald-100">إجمالي المبلغ</p>
                <p class="text-3xl font-extrabold mt-1">{{ number_format($payment->total_amount, 2) }} د.أ</p>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 bg-[#F8F9FA] rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">رقم الحجز</p>
                    <p class="font-bold text-gray-900">حجز #{{ $payment->booking_id }}</p>
                </div>
                <div class="p-4 bg-[#F8F9FA] rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">طريقة الدفع</p>
                    <p class="font-bold text-gray-900">{{ $payment->method === 'Visa' ? 'بطاقة فيزا' : 'الدفع نقدًا' }}</p>
                </div>
                <div class="p-4 bg-[#F8F9FA] rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">حالة الدفعة</p>
                    <p class="font-bold text-gray-900">
                        {{ $payment->status === 'Confirmed' ? 'مؤكد' : ($payment->status === 'Canceled' ? 'ملغي' : 'قيد الانتظار') }}
                    </p>
                </div>
                <div class="p-4 bg-[#F8F9FA] rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">تاريخ الدفع</p>
                    <p class="font-bold text-gray-900">{{ $payment->paid_at ? $payment->paid_at->format('Y/m/d H:i') : 'لم يتم الدفع بعد' }}</p>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-wrap justify-end gap-3">
                <a href="{{ route('payments.edit', $payment->id) }}"
                   class="px-5 py-2.5 bg-[#E8F1ED] hover:bg-[#d5e6de] text-[#2D5041] font-bold text-sm rounded-xl">
                    تعديل الدفعة
                </a>
                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" data-confirm="هل أنت متأكد من حذف هذه الدفعة؟">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 font-bold text-sm rounded-xl">
                        حذف الدفعة
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>