<x-app-layout>
    <main class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <header class="flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-900">المغامرات</h1>
                <p class="mt-1 text-sm text-slate-500">إدارة المغامرات والمواعيد والصور.</p>
            </div>
            <a href="{{ route('adventures.create') }}" class="rounded-lg bg-orange-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-orange-800">＋ أضف مغامرة</a>
            @session('success')
                <div class="w-full rounded-lg bg-emerald-100 px-4 py-3 text-sm font-semibold text-emerald-900" role="status">{{ $value }}</div>
            @endsession
            @error('adventure')
                <p class="w-full rounded-lg bg-danger/10 px-4 py-3 text-danger" role="alert">{{ $message }}</p>
            @enderror
        </header>

        <div class="overflow-x-auto rounded-2xl bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-right">
                <thead class="bg-slate-50 text-xs font-bold text-slate-600">
                    <tr>
                        <th class="px-5 py-4">المغامرة والموقع</th>
                        <th class="px-5 py-4">التصنيف</th>
                        <th class="px-5 py-4">السعة</th>
                        <th class="px-5 py-4">السعر</th>
                        <th class="px-5 py-4">الحالة</th>
                        <th class="px-5 py-4">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($adventures as $adventure)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @php($adventureImage = $adventure->images->first())
                                    @if ($adventureImage && $adventureImage->image_path)
                                        <img src="{{ asset('images/' . $adventureImage->image_path) }}" alt="{{ $adventure->title }}" class="h-14 w-14 rounded-xl object-cover">
                                    @else
                                        <div class="grid h-14 w-14 place-items-center rounded-xl bg-slate-100 text-xl text-slate-400" aria-label="لا توجد صورة">⛰</div>
                                    @endif
                                    <div>
                                        <h2 class="font-bold text-slate-900">{{ $adventure->title }}</h2>
                                        <p class="mt-1 text-xs text-slate-500">⌖ {{ $adventure->location }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4"><span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-800">{{ $adventure->category }}</span></td>
                            <td class="whitespace-nowrap px-5 py-4">{{ $adventure->capacity }} مقعد</td>
                            <td class="whitespace-nowrap px-5 py-4 font-bold">{{ number_format($adventure->price) }} د.أ</td>
                            <td class="whitespace-nowrap px-5 py-4"><span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-900">{{ $adventure->status }}</span></td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a class="rounded-md bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200" href="{{ route('adventures.show', $adventure) }}">عرض</a>
                                    <a class="rounded-md bg-orange-100 px-3 py-2 text-xs font-bold text-orange-800 hover:bg-orange-200" href="{{ route('adventures.edit', $adventure) }}">تعديل</a>
                                    <form action="{{ route('adventures.destroy', $adventure) }}" method="POST" data-confirm="هل أنت متأكد من حذف المغامرة؟">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md bg-red-100 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-200">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-slate-500">لا توجد مغامرات للعرض.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 text-sm text-slate-500">
            <p>عرض {{ count($adventures) }} مغامرات</p>
            {{ $adventures->links() }}
        </div>
    </main>

    @if ($optionalAdventure)
        <dialog class="adventure-modal m-auto max-h-[calc(100vh-2rem)] w-[min(720px,calc(100%-2rem))] overflow-y-auto rounded-2xl border-0 p-0 text-slate-800 shadow-2xl backdrop:bg-slate-950/60" id="optional-adventure-modal" dir="rtl"
                @if ($optionalAdventure && request('phase') === 'optional') data-modal-reopen @endif>
            <div class="p-5 sm:p-7">
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div><h2 class="text-xl font-black">المرحلة الثانية: الصور</h2><p class="mt-1 text-sm text-slate-500">{{ $optionalAdventure->title }}</p></div>
                    <button class="modal-close rounded-lg px-2 text-2xl text-slate-500 hover:bg-slate-100" type="button" aria-label="إغلاق">&times;</button>
                </div>
                <p class="text-sm text-slate-600">تم إنشاء المغامرة رقم {{ $optionalAdventure->id }}. أضف المواعيد والصور اختيارياً، أو أنهِ العملية.</p>
                @if ($errors->any() && old('_form_type') === 'optional' && (int) old('_adventure_id') === $optionalAdventure->id)
                    <div class="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-800"><ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif

                <section class="mt-6 border-t border-slate-200 pt-5">
                    <h3 class="mb-3 font-bold">الصور (اختياري)</h3>
                    <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-3 sm:grid-cols-2">
                        @csrf
                        <input type="hidden" name="adventure_id" value="{{ $optionalAdventure->id }}">
                        <input type="hidden" name="_form_type" value="optional">
                        <input type="hidden" name="_adventure_id" value="{{ $optionalAdventure->id }}">
                        <label class="text-sm font-semibold sm:col-span-2">اختر الصور<input class="mt-1 block w-full rounded-lg border border-slate-300 p-2 text-sm" type="file" name="image_files[]" accept="image/jpeg,image/png,image/webp" multiple required></label>
                        <div><button class="rounded-lg bg-orange-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-orange-800" type="submit">رفع الصور</button></div>
                    </form>
                </section>
                <a class="mt-6 inline-block rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-200" href="{{ route('adventures.index') }}">إنهاء</a>
            </div>
        </dialog>
    @endif
</x-app-layout>
