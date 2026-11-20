<x-app-layout>
    <div dir="rtl" class="py-8 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('payments.index') }}" class="text-sm font-semibold text-[#A94B2B] hover:text-[#8E3D22]">← العودة إلى المدفوعات</a>
            <h1 class="text-2xl font-extrabold text-gray-900 mt-3">إضافة دفعة جديدة</h1>
            <p class="text-sm text-gray-500 mt-1">اختر الحجز وطريقة الدفع لإنشاء دفعة جديدة</p>
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

        <form action="{{ route('payments.store') }}" method="POST"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            @csrf

            <div class="bg-[#2D5041] text-white px-6 py-4">
                <h2 class="font-bold">بيانات الدفع</h2>
            </div>

            <div class="p-6 space-y-5">
                <div>
                    <label for="booking_id" class="block text-sm font-bold text-gray-700 mb-2">الحجز</label>
                    <select id="booking_id" name="booking_id" required
                            class="w-full rounded-xl border-gray-200 text-sm focus:border-[#A94B2B] focus:ring-[#A94B2B]">
                        <option value="">اختر الحجز</option>
                        @foreach ($bookings as $booking)
                            <option value="{{ $booking->id }}" @selected(old('booking_id') == $booking->id)>
                                حجز #{{ $booking->id }} — {{ number_format($booking->total_amount, 2) }} د.أ
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <span class="block text-sm font-bold text-gray-700 mb-2">طريقة الدفع</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-[#A94B2B]">
                            <input type="radio" name="method" value="Visa" @checked(old('method') === 'Visa')
                                   class="text-[#A94B2B] focus:ring-[#A94B2B]" required>
                            <span class="font-semibold text-gray-700">بطاقة فيزا</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-[#A94B2B]">
                            <input type="radio" name="method" value="Cash" @checked(old('method') === 'Cash' || !old('method'))
                                   class="text-[#A94B2B] focus:ring-[#A94B2B]" required>
                            <span class="font-semibold text-gray-700">الدفع نقدًا</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('payments.index') }}"
                   class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl">
                    إلغاء
                </a>
                <button type="submit"
                        class="px-5 py-2.5 bg-[#A94B2B] hover:bg-[#8E3D22] text-white font-bold text-sm rounded-xl shadow-sm">
                    حفظ الدفعة
                </button>
            </div>
        </form>
    </div>
</x-app-layout>