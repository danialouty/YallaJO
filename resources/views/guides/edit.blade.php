<x-app-layout>
    <div dir="rtl" class="py-8 px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto">
        <a href="{{ route('guides.index') }}" class="text-sm font-semibold text-[#A94B2B]">← العودة إلى المرشدين</a>
        <h1 class="mt-3 text-2xl font-extrabold text-gray-900">تعديل المرشد #{{ $guide->id }}</h1>
        <div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="bg-[#2D5041] text-white px-6 py-4 font-bold">بيانات المرشد</div>
            <form action="{{ route('guides.update', $guide->id) }}" method="POST" class="p-6">
                @csrf
                @method('PATCH')
                @include('guides.partials.form', ['organizers' => $organizers, 'guide' => $guide])
                <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-4">
                    <a href="{{ route('guides.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700">إلغاء</a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#A94B2B] hover:bg-[#8E3D22] text-white text-sm font-bold">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>