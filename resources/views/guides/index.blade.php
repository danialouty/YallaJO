<x-app-layout>
    <div dir="rtl" class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">مرحبًا، {{ Auth::user()->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">هذه لوحة إدارة رحلاتك وجداولك كمَرشد</p>
            </div>
            @if (! $guide)
                <a href="{{ route('guides.create') }}" class="inline-flex rounded-xl bg-[#9B7448] px-4 py-2.5 text-sm font-bold text-white">إضافة مرشد</a>
            @elseif ($guide->status === 'Available')
                <span class="inline-flex px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">متاح للعمل</span>
            @else
                <span class="inline-flex px-4 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">مشغول حاليًا</span>
            @endif
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium">إجمالي الجداول</p>
                <p class="text-2xl font-extrabold text-gray-900 mt-2">{{ $schedules->count() }} <span class="text-xs font-normal text-gray-400">جدول</span></p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium">الجداول النشطة</p>
                <p class="text-2xl font-extrabold text-[#3F6653] mt-2">{{ $activeSchedules }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium">الجداول المعلقة</p>
                <p class="text-2xl font-extrabold text-amber-600 mt-2">{{ $suspendedSchedules }}</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium">سنوات الخبرة</p>
                <p class="text-2xl font-extrabold text-[#9B7448] mt-2">{{ $guide?->experience_years ?? 0 }} <span class="text-xs font-normal">سنة</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-bold text-gray-800">جداول المغامرات القادمة</h2>
                    <span class="text-xs text-gray-400">{{ $activeSchedules }} جدول نشط</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-100 text-xs text-gray-500">
                                <th class="py-3.5 px-6 font-semibold">المغامرة</th>
                                <th class="py-3.5 px-4 font-semibold">البداية</th>
                                <th class="py-3.5 px-4 font-semibold">النهاية</th>
                                <th class="py-3.5 px-4 font-semibold">السعة</th>
                                <th class="py-3.5 px-6 font-semibold">الحالة</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse ($schedules as $schedule)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="py-4 px-6 font-bold text-gray-900">{{ $schedule->adventure?->title ?? 'مغامرة #' . $schedule->adventure_id }}</td>
                                    <td class="py-4 px-4 text-gray-600">{{ $schedule->starts_at?->format('Y/m/d H:i') ?? 'غير محدد' }}</td>
                                    <td class="py-4 px-4 text-gray-600">{{ $schedule->ends_at?->format('Y/m/d H:i') ?? 'غير محدد' }}</td>
                                    <td class="py-4 px-4 text-gray-700">{{ $schedule->capacity }} مشارك</td>
                                    <td class="py-4 px-6">
                                        @if ($schedule->status === 'Active')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">نشط</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">معلق</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-12 px-6 text-center text-sm text-gray-500">لا توجد جداول مغامرات مسجلة لك حاليًا.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-4">
                @if ($guide)
                    <div class="bg-[#3F6653] rounded-2xl p-6 text-white shadow-sm">
                        <p class="text-sm text-emerald-100">ملف المرشد</p>
                        <p class="text-xl font-extrabold mt-2">{{ $organizer?->user?->name ?? Auth::user()->name }}</p>
                        <p class="text-sm text-emerald-100 mt-2 leading-6">{{ $guide->bio }}</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                        <p class="text-sm font-bold text-gray-800 mb-3">التخصصات</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($guide->specialization ?? [] as $specialty)
                                <span class="px-3 py-1 rounded-lg bg-[#EEF4F0] text-[#3F6653] text-xs font-bold">{{ $specialty }}</span>
                            @endforeach
                        </div>
                        @if ($guide->cetification)
                            <p class="mt-4 text-xs text-gray-500">الشهادة: <strong class="text-gray-800">{{ $guide->cetification }}</strong></p>
                        @endif
                        <form action="{{ route('guides.update', $guide->id) }}" method="POST" class="mt-5">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $guide->status === 'Available' ? 'Busy' : 'Available' }}">
                            <button type="submit" class="w-full px-4 py-2.5 rounded-xl {{ $guide->status === 'Available' ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' }} font-bold text-sm">
                                {{ $guide->status === 'Available' ? 'تغيير إلى مشغول' : 'تغيير إلى متاح' }}
                            </button>
                        </form>
                    </div>

                    <details class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                        <summary class="cursor-pointer font-bold text-gray-800">تعديل بيانات المرشد</summary>
                        <form action="{{ route('guides.update', $guide->id) }}" method="POST" class="mt-5 space-y-4">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2">نبذة المرشد</label>
                                <textarea name="bio" rows="4" required class="w-full rounded-xl border-gray-200 text-sm">{{ $guide->bio }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2">سنوات الخبرة</label>
                                <input type="number" name="experience_years" min="0" required value="{{ $guide->experience_years }}"
                                       class="w-full rounded-xl border-gray-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2">التخصصات مفصولة بفاصلة</label>
                                <input type="text" name="specialization" required value="{{ implode(', ', $guide->specialization ?? []) }}"
                                       class="w-full rounded-xl border-gray-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2">الشهادة</label>
                                <input type="text" name="cetification" value="{{ $guide->cetification }}"
                                       class="w-full rounded-xl border-gray-200 text-sm">
                            </div>
                            <button type="submit" class="w-full rounded-xl bg-[#9B7448] px-4 py-2.5 text-sm font-bold text-white">
                                حفظ بيانات المرشد
                            </button>
                        </form>
                    </details>
                @else
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl mb-4">+</div>
                        <h2 class="font-bold text-gray-900">إنشاء ملف المرشد</h2>
                        <p class="mt-2 text-sm text-gray-500 leading-6">
                            أضف بياناتك كمرشد حتى تتمكن من إدارة الجداول والحالة.
                        </p>
                        <form action="{{ route('guides.store') }}" method="POST" class="mt-5 space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2">نبذة المرشد</label>
                                <textarea name="bio" rows="4" required class="w-full rounded-xl border-gray-200 text-sm">{{ old('bio') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2">سنوات الخبرة</label>
                                <input type="number" name="experience_years" min="0" required value="{{ old('experience_years', 0) }}"
                                       class="w-full rounded-xl border-gray-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2">التخصصات مفصولة بفاصلة</label>
                                <input type="text" name="specialization" required value="{{ old('specialization') }}"
                                       placeholder="Hiking, Camping"
                                       class="w-full rounded-xl border-gray-200 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2">الشهادة</label>
                                <input type="text" name="cetification" value="{{ old('cetification') }}"
                                       class="w-full rounded-xl border-gray-200 text-sm">
                            </div>
                            <input type="hidden" name="status" value="Available">
                            <button type="submit" class="w-full rounded-xl bg-[#9B7448] px-4 py-2.5 text-sm font-bold text-white">
                                إنشاء ملف المرشد
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
