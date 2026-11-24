<x-app-layout>
    <div dir="rtl" class="py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <a href="{{ route('guides.index') }}" class="text-sm font-semibold text-[#9B7448]">← العودة إلى المرشدين</a>
                <h1 class="mt-3 text-2xl font-extrabold text-gray-900">تفاصيل المرشد #{{ $guide->id }}</h1>
            </div>
            @if ($guide->status === 'Available')
                <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">متاح</span>
            @else
                <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">مشغول</span>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="bg-[#3F6653] text-white p-6">
                <p class="text-sm text-emerald-100">المنظم المسؤول</p>
                <p class="text-2xl font-extrabold mt-1">{{ $guide->activityOrganizer?->user?->name ?? 'غير مرتبط بمنظم' }}</p>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 bg-[#F8F9FA] rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">سنوات الخبرة</p>
                    <p class="font-bold text-gray-900">{{ $guide->experience_years }} سنة</p>
                </div>
                <div class="p-4 bg-[#F8F9FA] rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">الشهادة</p>
                    <p class="font-bold text-gray-900">{{ $guide->cetification ?: 'غير مسجلة' }}</p>
                </div>
                <div class="sm:col-span-2 p-4 bg-[#F8F9FA] rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-2">التخصصات</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($guide->specialization ?? [] as $specialty)
                            <span class="px-3 py-1 rounded-lg bg-[#EEF4F0] text-[#3F6653] text-xs font-bold">{{ $specialty }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="sm:col-span-2 p-4 bg-[#F8F9FA] rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-2">نبذة</p>
                    <p class="font-medium text-gray-800 leading-7">{{ $guide->bio }}</p>
                </div>
                <div class="sm:col-span-2 p-4 bg-[#F8F9FA] rounded-xl border border-gray-100">
                    <p class="text-xs text-gray-500 mb-2">الجداول المرتبطة</p>
                    <p class="font-bold text-gray-900">{{ $guide->schedules->count() }} جدول</p>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('guides.edit', $guide->id) }}" class="px-5 py-2.5 bg-[#EEF4F0] hover:bg-[#DCE9E1] text-[#3F6653] font-bold text-sm rounded-xl">تعديل المرشد</a>
            </div>
        </div>
    </div>
</x-app-layout>