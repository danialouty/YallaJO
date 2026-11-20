<x-app-layout>
    <div dir="rtl" class="py-8 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('payments.index') }}" class="text-sm font-semibold text-[#A94B2B] hover:text-[#8E3D22]">← العودة إلى المدفوعات</a>
            <h1 class="text-2xl font-extrabold text-gray-900 mt-3">تعديل الدفعة #{{ $payment->id }}</h1>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                <ul class="list-disc pr-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('payments.update', $payment->id) }}" method="POST"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            @csrf
            @method('PATCH')

            <div class="bg-[#2D5041] text-white px-6 py-4">
                <h2 class="font-bold">بيانات الدفعة</h2>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="method" class="block text-sm font-bold text-gray-700 mb-2">طريقة الدفع</label>
                    <select id="method" name="method"
                            class="w-full rounded-xl border-gray-200 text-sm focus:border-[#A94B2B] focus:ring-[#A94B2B]">
                        <option value="Visa" @selected(old('method', $payment->method) === 'Visa')>بطاقة فيزا</option>
                        <option value="Cash" @selected(old('method', $payment->method) === 'Cash')>الدفع نقدًا</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-bold text-gray-700 mb-2">حالة الدفع</label>
                    <select id="status" name="status"
                            class="w-full rounded-xl border-gray-200 text-sm focus:border-[#A94B2B] focus:ring-[#A94B2B]">
                        <option value="Pending" @selected(old('status', $payment->status) === 'Pending')>قيد الانتظار</option>
                        <option value="Confirmed" @selected(old('status', $payment->status) === 'Confirmed')>مؤكد</option>
                        <option value="Canceled" @selected(old('status', $payment->status) === 'Canceled')>ملغي</option>
                    </select>
                </div>

                <div class="sm:col-span-2 p-4 bg-[#F8F9FA] border border-gray-200 rounded-xl flex items-center justify-between">
                    <span class="text-sm text-gray-500">إجمالي المبلغ</span>
                    <strong class="text-xl text-[#A94B2B]">{{ number_format($payment->total_amount, 2) }} د.أ</strong>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('payments.index') }}"
                   class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl">
                    إلغاء
                </a>
                <button type="submit"
                        class="px-5 py-2.5 bg-[#A94B2B] hover:bg-[#8E3D22] text-white font-bold text-sm rounded-xl shadow-sm">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</x-app-layout>