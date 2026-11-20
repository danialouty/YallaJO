<dialog id="organizer-profile-modal"
    class="w-full max-w-lg rounded-2xl border border-slate-700 bg-slate-900 p-0 text-slate-100 shadow-2xl backdrop:bg-black/70">
    <div class="p-6">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <h2 class="text-lg font-semibold">
                {{ $organizer ? 'Edit Organizer Profile' : 'Complete Organizer Profile' }}
            </h2>
            <button type="button"
                onclick="document.getElementById('organizer-profile-modal').close()"
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
                    Organizer Type <span class="text-rose-400">*</span>
                </label>
                <select id="organizer_type" name="organizer_type" required
                    class="mt-1.5 w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-white focus:border-emerald-500 focus:outline-none">
                    @php
                        $selectedType = old('organizer_type', $organizer?->organizer_type ?? 'tourism_office');
                    @endphp
                    <option value="tourism_office" @selected($selectedType === 'tourism_office')>
                        Tourism Office (Licensed Agency)
                    </option>
                    <option value="hike_group" @selected($selectedType === 'hike_group')>
                        Hike / Adventure Group
                    </option>
                </select>
                @error('organizer_type')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- License Number --}}
            <div>
                <label for="license_number" class="block text-sm font-medium text-slate-300">
                    License Number
                    <span id="license-hint" class="text-xs text-amber-400">(Required for Tourism Offices)</span>
                </label>
                <input type="text" id="license_number" name="license_number"
                    value="{{ old('license_number', $organizer?->license_number) }}"
                    placeholder="e.g. MOTA-2026-4812"
                    class="mt-1.5 w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none">
                @error('license_number')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Social Handle URL --}}
            <div>
                <label for="social_handle_url" class="block text-sm font-medium text-slate-300">
                    Social Media / Website URL
                    <span id="social-hint" class="text-xs text-slate-400">(Recommended for Hike Groups)</span>
                </label>
                <input type="url" id="social_handle_url" name="social_handle_url"
                    value="{{ old('social_handle_url', $organizer?->social_handle_url) }}"
                    placeholder="https://instagram.com/yourgroup"
                    class="mt-1.5 w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-emerald-500 focus:outline-none">
                @error('social_handle_url')
                    <p class="mt-1 text-xs text-rose-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-800 pt-4">
                <button type="button"
                    onclick="document.getElementById('organizer-profile-modal').close()"
                    class="rounded-lg border border-slate-700 px-4 py-2 text-sm text-slate-300 hover:bg-slate-800">
                    Cancel
                </button>
                <button type="submit"
                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-500">
                    {{ $organizer ? 'Save Changes' : 'Submit for Approval' }}
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('organizer-profile-modal');
        const typeSelect = document.getElementById('organizer_type');
        const licenseHint = document.getElementById('license-hint');

        const syncHints = () => {
            const isOffice = typeSelect.value === 'tourism_office';
            licenseHint.textContent = isOffice
                ? '(Required for Tourism Offices)'
                : '(Optional for Hike Groups)';
            licenseHint.className = isOffice
                ? 'text-xs text-amber-400'
                : 'text-xs text-slate-400';
        };

        typeSelect.addEventListener('change', syncHints);
        syncHints();

        // Keep modal open if server-side validation failed
        @if ($errors->any())
            modal.showModal();
        @endif
    });
</script>
