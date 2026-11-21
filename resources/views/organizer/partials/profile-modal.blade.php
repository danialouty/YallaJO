<dialog id="organizer-profile-modal"
    @if ($errors->any()) data-modal-reopen @endif
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    class="w-full max-w-lg rounded-2xl border border-slate-700 bg-slate-900 p-0 text-slate-100 shadow-2xl backdrop:bg-black/70">
    <div class="p-6">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <h2 class="text-lg font-semibold">
                {{ $organizer ? __('Edit Organizer Profile') : __('Complete Organizer Profile') }}
            </h2>
            <button type="button"
                data-modal-close
                class="rounded-lg px-2.5 py-1 text-slate-400 hover:bg-slate-800 hover:text-white">
                ✕
            </button>
        </div>

        <form method="POST"
            action="{{ $organizer ? route('organizers.update', $organizer) : route('organizers.store') }}"
            class="mt-5 space-y-4">
            @csrf
            @if ($organizer)
                @method('PUT')
            @endif

            {{-- Organizer Type --}}
            <div>
                <label for="organizer_type" class="block text-sm font-medium text-slate-300">
                    {{ __('Organizer Type') }} <span class="text-rose-400">*</span>
                </label>
                <select id="organizer_type" name="organizer_type" required
                    class="mt-1.5 w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-white focus:border-emerald-500 focus:outline-none">
                    @php
                        $selectedType = old('organizer_type', $organizer?->organizer_type ?? 'tourism_office');
                    @endphp
                    <option value="tourism_office" @selected($selectedType === 'tourism_office')>
                        {{ __('Tourism Office (Licensed Agency)') }}
                    </option>
                    <option value="hike_group" @selected($selectedType === 'hike_group')>
                        {{ __('Hike / Adventure Group') }}
                    </option>
                </select>
                @error('organizer_type')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- License Number --}}
            <div>
                <label for="license_number" class="block text-sm font-medium text-slate-300">
                    {{ __('License Number') }}
                    <span id="license-hint"
                        data-office-hint="({{ __('Required for Tourism Offices') }})"
                        data-group-hint="({{ __('Optional for Hike Groups') }})"
                        class="text-xs text-amber-400">
                        ({{ __('Required for Tourism Offices') }})
                    </span>
                </label>
                <input type="text" id="license_number" name="license_number" dir="ltr"
                    value="{{ old('license_number', $organizer?->license_number) }}"
                    placeholder="MOTA-2026-4812"
                    class="mt-1.5 w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none">
                @error('license_number')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Social Handle URL --}}
            <div>
                <label for="social_handle_url" class="block text-sm font-medium text-slate-300">
                    {{ __('Social Media / Website URL') }}
                    <span class="text-xs text-slate-400">({{ __('Recommended for Hike Groups') }})</span>
                </label>
                <input type="url" id="social_handle_url" name="social_handle_url" dir="ltr"
                    value="{{ old('social_handle_url', $organizer?->social_handle_url) }}"
                    placeholder="https://instagram.com/yourgroup"
                    class="mt-1.5 w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none">
                @error('social_handle_url')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-800 pt-4">
                <button type="button"
                    data-modal-close
                    class="rounded-lg border border-slate-700 px-4 py-2 text-sm text-slate-300 hover:bg-slate-800">
                    {{ __('Cancel') }}
                </button>
                <button type="submit"
                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-500">
                    {{ $organizer ? __('Save Changes') : __('Submit for Approval') }}
                </button>
            </div>
        </form>
    </div>
</dialog>
