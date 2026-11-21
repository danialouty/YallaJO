<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2">
        <label for="activity_organizer_id" class="block text-sm font-bold text-gray-700 mb-2">المنظم</label>
        <select id="activity_organizer_id" name="activity_organizer_id" required
                class="w-full rounded-xl border-gray-200 text-sm focus:border-[#A94B2B] focus:ring-[#A94B2B]">
            <option value="">اختر المنظم</option>
            @foreach ($organizers as $organizer)
                <option value="{{ $organizer->id }}"
                    @selected(old('activity_organizer_id', $guide?->activity_organizer_id) == $organizer->id)>
                    {{ $organizer->user?->name ?? 'منظم #' . $organizer->id }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="experience_years" class="block text-sm font-bold text-gray-700 mb-2">سنوات الخبرة</label>
        <input id="experience_years" name="experience_years" type="number" min="0"
               value="{{ old('experience_years', $guide?->experience_years) }}" required
               class="w-full rounded-xl border-gray-200 text-sm focus:border-[#A94B2B] focus:ring-[#A94B2B]">
    </div>
    <div>
        <label for="status" class="block text-sm font-bold text-gray-700 mb-2">الحالة</label>
        <select id="status" name="status"
                class="w-full rounded-xl border-gray-200 text-sm focus:border-[#A94B2B] focus:ring-[#A94B2B]">
            <option value="Available" @selected(old('status', $guide?->status ?? 'Available') === 'Available')>متاح</option>
            <option value="Busy" @selected(old('status', $guide?->status) === 'Busy')>مشغول</option>
        </select>
    </div>
    <div class="sm:col-span-2">
        <label for="specialization" class="block text-sm font-bold text-gray-700 mb-2">التخصصات</label>
        <input id="specialization" name="specialization"
               value="{{ old('specialization', is_array($guide?->specialization) ? implode(', ', $guide->specialization) : '') }}"
               placeholder="تسلق، غوص، مشي طويل" required
               class="w-full rounded-xl border-gray-200 text-sm focus:border-[#A94B2B] focus:ring-[#A94B2B]">
        <p class="mt-1 text-xs text-gray-400">افصل بين التخصصات بفواصل إنجليزية.</p>
    </div>
    <div class="sm:col-span-2">
        <label for="cetification" class="block text-sm font-bold text-gray-700 mb-2">الشهادة</label>
        <input id="cetification" name="cetification"
               value="{{ old('cetification', $guide?->cetification) }}"
               class="w-full rounded-xl border-gray-200 text-sm focus:border-[#A94B2B] focus:ring-[#A94B2B]">
    </div>
    <div class="sm:col-span-2">
        <label for="bio" class="block text-sm font-bold text-gray-700 mb-2">نبذة قصيرة</label>
        <textarea id="bio" name="bio" rows="4" required
                  class="w-full rounded-xl border-gray-200 text-sm focus:border-[#A94B2B] focus:ring-[#A94B2B]">{{ old('bio', $guide?->bio) }}</textarea>
    </div>
</div>
