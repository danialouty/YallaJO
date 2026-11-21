@php
    $item = $data ?? $booking;
@endphp

@extends('layouts.dashboard')

@section('dashboard-theme', 'adventurer')
@section('dashboard-name', 'بوابة المغامر')
@section('dashboard-title', 'تفاصيل الحجز #' . $item->id)

@section('dashboard-header-actions')
    <a href="{{ route('bookings.index') }}"
       class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-bold text-[#2D5041] shadow-sm transition hover:bg-gray-50">
        <span>&rarr; العودة إلى حجوزاتي</span>
    </a>
@endsection

@section('content')
    <div class="yallajo-show-wrapper max-w-4xl mx-auto">

        <!-- حالة الحجز -->
        <div class="mb-5 flex items-center justify-between">
            <span class="text-sm font-semibold text-gray-500">حالة الطلب الحالية:</span>

            @if ($item->status === 'Confirmed')
                <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                    مؤكد من المنظم
                </span>
            @elseif ($item->status === 'Cancelled')
                <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
                    ملغي
                </span>
            @else
                <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                    قيد الانتظار (بانتظار الموافقة)
                </span>
            @endif
        </div>

        <!-- بطاقة تفاصيل الحجز -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="bg-[#2D5041] text-white p-6">
                <p class="text-xs text-emerald-200 mb-1">رقم المرجع: #{{ $item->id }}</p>
                <h2 class="text-2xl font-extrabold">{{ $item->adventure->title ?? 'مغامرة #' . $item->adventure_id }}</h2>
            </div>

            <div class="p-6 space-y-6">
                <!-- تفاصيل العدد والسعر -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 bg-[#F8F9FA] rounded-xl border border-gray-100">
                        <span class="text-xs text-gray-500 block">عدد المشاركين</span>
                        <span class="text-lg font-extrabold text-gray-900 mt-1 block">{{ $item->participants_count }} أشخاص</span>
                    </div>

                    <div class="p-4 bg-[#F8F9FA] rounded-xl border border-gray-100">
                        <span class="text-xs text-gray-500 block">سعر الفرد الواحد</span>
                        <span class="text-lg font-extrabold text-gray-900 mt-1 block">{{ number_format($item->unit_price, 2) }} د.أ</span>
                    </div>

                    <div class="p-4 bg-[#E8F1ED] rounded-xl border border-emerald-200">
                        <span class="text-xs text-[#2D5041] font-bold block">المبلغ الإجمالي</span>
                        <span class="text-xl font-extrabold text-[#2D5041] mt-1 block">{{ number_format($item->total_amount, 2) }} د.أ</span>
                    </div>
                </div>

                <!-- تواريخ الطلب -->
                <div class="border-t border-gray-100 pt-4 text-sm text-gray-600 space-y-2">
                    <div class="flex justify-between">
                        <span>تاريخ إرسال طلب الحجز:</span>
                        <span class="font-bold text-gray-800">{{ $item->created_at->format('Y/m/d - h:i A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>آخر تحديث للحالة:</span>
                        <span class="font-bold text-gray-800">{{ $item->updated_at->format('Y/m/d - h:i A') }}</span>
                    </div>
                </div>

                <!-- زر الإلغاء -->
                <div class="border-t border-gray-100 pt-5 flex items-center justify-end gap-3">
                    <form action="{{ route('bookings.destroy', $item->id) }}" method="POST"
                          onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الحجز؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 font-bold text-sm rounded-xl transition cursor-pointer">
                            إلغاء الحجز
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .yallajo-show-wrapper { font-family: 'Cairo', sans-serif; }
    </style>
@endsection