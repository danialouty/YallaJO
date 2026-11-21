
@php
    $adventurer = $adventurer ?? null;

    $formAction = $formAction
        ?? route('adventurer.store');

    $formMethod = strtoupper($formMethod ?? 'POST');

    $cancelUrl = $cancelUrl ?? route('adventures.index');
    $modalName = $modalName ?? null;
    $idPrefix = $modalName ? $modalName . '-' : '';

    $submitLabel = $submitLabel
        ?? 'إنشاء الملف الشخصي';

    $experienceLevels = [
        'Beginner' => 'مبتدئ',
        'Intermediate' => 'متوسط',
        'Advanced' => 'متقدم',
    ];

    $interestOptions = [
        'Hiking' => 'المشي والمسارات الجبلية',
        'Camping' => 'التخييم',
        'Diving' => 'الغوص',
        'kayaking' => 'التجديف',
        'climbing' => 'التسلق',
    ];

    $savedInterests = $adventurer?->interests ?? [];
    $savedInterests = is_array($savedInterests) ? $savedInterests : [];

    $selectedInterests = old('_adventurer_profile_form')
        ? old('interests', [])
        : $savedInterests;

    $selectedInterests = is_array($selectedInterests)
        ? $selectedInterests
        : [];

    foreach (array_merge($savedInterests, $selectedInterests) as $interest) {
        if (is_string($interest)) {
            $interestOptions[$interest] ??= $interest;
        }
    }

    $governorates = [
        'عمّان', 'إربد', 'الزرقاء', 'البلقاء', 'المفرق', 'جرش',
        'عجلون', 'مادبا', 'الكرك', 'الطفيلة', 'معان', 'العقبة',
    ];

    $location = old('location', $adventurer?->location);
    $selectedLocation = is_string($location) ? $location : '';

    foreach ([$adventurer?->location, $selectedLocation] as $legacyLocation) {
        if (is_string($legacyLocation) && $legacyLocation !== '' && ! in_array($legacyLocation, $governorates, true)) {
            $governorates[] = $legacyLocation;
        }
    }
@endphp

<form
    action="{{ $formAction }}"
    method="POST"
    class="space-y-7"
>
    @csrf
    <input type="hidden" name="_adventurer_profile_form" value="1">
    @if ($modalName)
        <input type="hidden" name="_modal" value="{{ $modalName }}">
    @endif

    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div
            role="alert"
            @if ($modalName && old('_modal') === $modalName)
                tabindex="-1" autofocus
            @endif
            class="rounded-control border
                   border-danger/30 bg-danger/5
                   px-4 py-3 text-sm text-danger"
        >
            <p class="font-bold">
                يرجى مراجعة البيانات المدخلة.
            </p>

            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Experience Level --}}
    <div class="space-y-2">
        <label
            for="{{ $idPrefix }}experience_level"
            class="block text-sm font-semibold text-ink"
        >
            مستوى الخبرة
            <span class="text-danger">*</span>
        </label>

        <select
            id="{{ $idPrefix }}experience_level"
            name="experience_level"
            required
            @if ($errors->has('experience_level'))
                aria-invalid="true"
                aria-describedby="{{ $idPrefix }}experience-level-error"
            @endif
            class="block w-full rounded-control
                   border border-outline/60 bg-surface
                   py-3 ps-4 pe-10 text-sm text-ink
                   rtl:bg-position-[left_0.5rem_center]
                   focus:border-brand focus:outline-none
                   focus:ring-2 focus:ring-brand/20"
        >
            <option value="">
                اختر مستوى خبرتك
            </option>

            @foreach ($experienceLevels as $value => $label)
                <option
                    value="{{ $value }}"
                    @selected(
                        old(
                            'experience_level',
                            $adventurer?->experience_level
                        ) === $value
                    )
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>

        @error('experience_level')
            <p
                id="{{ $idPrefix }}experience-level-error"
                class="text-xs text-danger"
            >
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Adventure Interests --}}
    <fieldset class="space-y-3">
        <legend class="text-sm font-semibold text-ink">
            ما المغامرات التي تفضلها؟
        </legend>

        <p class="text-xs text-muted">
            يمكنك اختيار أكثر من اهتمام.
        </p>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            @foreach ($interestOptions as $value => $label)
                <label
                    class="flex cursor-pointer items-center
                           gap-3 rounded-control border
                           border-outline/40 bg-surface
                           px-4 py-3 transition-colors
                           hover:border-brand/50
                           hover:bg-brand/5"
                >
                    <input
                        type="checkbox"
                        name="interests[]"
                        value="{{ $value }}"
                        @checked(in_array(
                            (string) $value,
                            $selectedInterests,
                            true
                        ))
                        class="size-4 rounded border-outline
                               text-brand focus:ring-brand"
                    >

                    <span class="text-sm text-ink">
                        {{ $label }}
                    </span>
                </label>
            @endforeach
        </div>

        @error('interests')
            <p class="text-xs text-danger">
                {{ $message }}
            </p>
        @enderror

        @error('interests.*')
            <p class="text-xs text-danger">
                {{ $message }}
            </p>
        @enderror
    </fieldset>

    {{-- Location --}}
    <div class="space-y-2" dir="rtl">
        <label for="{{ $idPrefix }}adventurer-location" class="block text-sm font-semibold text-ink">
            الموقع (اختياري)
        </label>
        <select
            id="{{ $idPrefix }}adventurer-location"
            name="location"
            aria-describedby="{{ $idPrefix }}adventurer-location-hint{{ $errors->has('location') ? ' ' . $idPrefix . 'adventurer-location-error' : '' }}"
            @if ($errors->has('location')) aria-invalid="true" @endif
            class="block w-full rounded-control border bg-surface py-3 ps-4 pe-10 text-sm text-ink
                   rtl:bg-position-[left_0.5rem_center]
                   focus:outline-none focus:ring-2
                   {{ $errors->has('location') ? 'border-danger focus:border-danger focus:ring-danger/20' : 'border-outline/60 focus:border-brand focus:ring-brand/20' }}"
        >
            <option value="" @selected($selectedLocation === '')>بدون تحديد</option>
            @foreach ($governorates as $governorate)
                <option
                    value="{{ $governorate }}"
                    @selected($selectedLocation === $governorate)
                >{{ $governorate }}</option>
            @endforeach
        </select>
        <p id="{{ $idPrefix }}adventurer-location-hint" class="text-xs text-muted">
            اختر محافظتك لتخصيص تجربتك.
        </p>
        @error('location')
            <p id="{{ $idPrefix }}adventurer-location-error" class="text-xs text-danger" role="alert">
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Form Actions --}}
    <div
        class="flex flex-col-reverse gap-3
               border-t border-outline/30
               pt-6 sm:flex-row"
    >
        <x-ui.button
            type="submit"
            variant="primary"
            size="lg"
            class="flex-1"
        >
            {{ $submitLabel }}
        </x-ui.button>

        @if ($modalName)
            <x-ui.button type="submit" variant="outline" size="lg" formmethod="dialog" formnovalidate>
                إلغاء
            </x-ui.button>
        @else
            <a
                href="{{ $cancelUrl }}"
                class="inline-flex min-h-12 items-center
                       justify-center rounded-control
                       border border-outline px-6 py-3
                       text-sm font-semibold text-ink
                       hover:bg-canvas"
            >
                إلغاء
            </a>
        @endif
    </div>
</form>
