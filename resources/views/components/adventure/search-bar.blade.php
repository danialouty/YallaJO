@props([
    'idPrefix' => 'adventure-search',
])

@php
    $fieldClasses = '
        flex min-h-[78px] min-w-0
        items-center gap-3 rounded-xl
        bg-[#eef1ff] px-4 py-3
        transition-colors
        focus-within:ring-2
        focus-within:ring-brand
    ';

    $labelClasses = '
        mb-1 block text-xs
        font-bold text-muted
    ';

    $controlClasses = '
        block w-full min-w-0
        bg-transparent text-sm text-ink
        placeholder:text-muted/60
        focus:outline-none
    ';
@endphp

<div
    role="search"
    aria-label="خيارات البحث عن المغامرات"
    {{ $attributes->class([
        'w-full max-w-5xl rounded-2xl',
        'bg-white p-3 text-right',
        'text-ink shadow-2xl md:p-4',
    ]) }}
>
    <div
        class="grid grid-cols-1
               gap-3 md:grid-cols-2
               xl:grid-cols-12"
    >

        {{-- 1. Destination --}}
        <div class="{{ $fieldClasses }} xl:col-span-3">

            <svg
                class="size-6 shrink-0 text-brand"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                aria-hidden="true"
            >
                <path
                    d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"
                />
                <circle cx="12" cy="10" r="2.5"/>
            </svg>

            <div class="min-w-0 flex-1">

                <label
                    for="{{ $idPrefix }}-location"
                    class="{{ $labelClasses }}"
                >
                    الوجهة والمكان
                </label>

                <input
                    id="{{ $idPrefix }}-location"
                    name="location"
                    type="text"
                    placeholder="الموجب، رم، عجلون..."
                    class="{{ $controlClasses }}"
                >

            </div>
        </div>


        {{-- 2. Activity Type --}}
        <div class="{{ $fieldClasses }} xl:col-span-3">

            <svg
                class="size-6 shrink-0 text-forest"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                aria-hidden="true"
            >
                <circle cx="12" cy="12" r="9"/>
                <path d="m16 8-2.5 5.5L8 16l2.5-5.5L16 8Z"/>
            </svg>

            <div class="min-w-0 flex-1">

                <label
                    for="{{ $idPrefix }}-activity"
                    class="{{ $labelClasses }}"
                >
                    نوع التجربة
                </label>

                <select
                    id="{{ $idPrefix }}-activity"
                    name="activity_group"
                    class="{{ $controlClasses }} cursor-pointer"
                >
                    <option value="">
                        كل الفعاليات
                    </option>

                    <option value="hiking">
                        هايكنج ومسارات مائية
                    </option>

                    <option value="camping">
                        تخييم ورصد نجوم
                    </option>

                    <option value="diving">
                        غطس ورياضات بحرية
                    </option>

                    <option value="rural">
                        تجارب ريفية وحصاد
                    </option>
                </select>

            </div>
        </div>


        {{-- 3. Date / Period --}}
        <div class="{{ $fieldClasses }} xl:col-span-2">

            <svg
                class="size-6 shrink-0 text-accent"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                aria-hidden="true"
            >
                <rect x="3" y="5" width="18" height="16" rx="2"/>
                <path d="M8 3v4M16 3v4M3 10h18"/>
            </svg>

            <div class="min-w-0 flex-1">

                <label
                    for="{{ $idPrefix }}-period"
                    class="{{ $labelClasses }}"
                >
                    الموعد
                </label>

                <select
                    id="{{ $idPrefix }}-period"
                    name="period"
                    class="{{ $controlClasses }} cursor-pointer"
                >
                    <option value="weekend">
                        عطلة الأسبوع القادمة
                    </option>

                    <option value="friday">
                        يوم الجمعة فقط
                    </option>

                    <option value="saturday">
                        يوم السبت فقط
                    </option>

                    <option value="month">
                        خلال هذا الشهر
                    </option>
                </select>

            </div>
        </div>


        {{-- 4. Difficulty Level --}}
        <div class="{{ $fieldClasses }} xl:col-span-2">

            <svg
                class="size-6 shrink-0 text-accent"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                aria-hidden="true"
            >
                <path d="m3 20 6-10 4 6 2-3 6 7H3Z"/>
            </svg>

            <div class="min-w-0 flex-1">

                <label
                    for="{{ $idPrefix }}-difficulty"
                    class="{{ $labelClasses }}"
                >
                    مستوى المشقة
                </label>

                <select
                    id="{{ $idPrefix }}-difficulty"
                    name="difficulty_level"
                    class="{{ $controlClasses }} cursor-pointer"
                >
                    <option value="Easy">
                        سهل / للمبتدئين
                    </option>

                    <option value="Medium">
                        متوسط (لياقة عادية)
                    </option>

                    <option value="Hard">
                        متقدم ومغامر
                    </option>
                </select>

            </div>
        </div>


        {{-- 5. Search Button --}}
        <div class="md:col-span-2 xl:col-span-2">

            <button
                type="button"
                disabled
                title="البحث قيد الربط بنظام المغامرات"
                class="flex min-h-[78px] w-full
                       cursor-not-allowed
                       items-center justify-center
                       gap-2 rounded-xl
                       bg-brand px-5 py-3
                       font-display font-bold
                       text-white opacity-70"
            >
                <svg
                    class="size-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    aria-hidden="true"
                >
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-4-4"/>
                </svg>

                <span>ابحث الآن</span>
            </button>

        </div>

    </div>
</div>