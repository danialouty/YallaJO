<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-extrabold text-gray-900">انضم إلى مجتمع يلا جو</h2>
        <p class="text-sm text-gray-500 mt-1">أنشئ حسابك وابدأ بحجز تجاربك القادمة في الأردن</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="role" class="mb-1 block text-sm font-bold text-gray-700">نوع الحساب</label>
            <div class="relative">
                <select id="role" name="role" required
                    class="w-full rounded-xl border-gray-300 py-2.5 pe-3.5 ps-11 text-sm focus:border-[#2D5041] focus:ring-[#2D5041]"
                    style="appearance: none; background-image: none; padding-left: 2.75rem; padding-right: 0.875rem;">
                    <option value="Adventurer" @selected(old('role', $role) === 'Adventurer')>مغامر</option>
                    <option value="Activity_Organizer" @selected(old('role', $role) === 'Activity_Organizer')>منظّم مغامرات</option>
                </select>
                <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.22 7.47a.75.75 0 0 1 1.06 0L10 11.19l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                </svg>
            </div>
            @error('role')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- الاسم الكامل -->
        <div>
            <label for="name" class="block text-sm font-bold text-gray-700 mb-1">الاسم الكامل</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="أحمد "
                class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-3.5 focus:border-[#2D5041] focus:ring-[#2D5041]">
            @error('name')
                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- البريد الإلكتروني -->
        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                placeholder="name@example.com"
                class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-3.5 focus:border-[#2D5041] focus:ring-[#2D5041]">
            @error('email')
                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>
        <!-- رقم الهاتف -->
        <div>
            <label for="phone_number" class="block text-sm font-bold text-gray-700 mb-1"> رقم الهاتف</label>
            <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number') }}" required autocomplete="username"
                placeholder=". . . . . . . . 07"
                class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-3.5 focus:border-[#2D5041] focus:ring-[#2D5041]">
        </div>

        <fieldset id="organizer-fields" class="hidden space-y-4 rounded-xl border border-gray-200 p-4">
            <legend class="px-2 text-sm font-bold text-gray-700">بيانات المنظّم المطلوبة للمراجعة</legend>
            <div>
                <label for="organizer_type" class="mb-1 block text-sm font-bold text-gray-700">نوع المنظّم</label>
                <div class="relative">
                    <select id="organizer_type" name="organizer_type"
                        class="w-full rounded-xl border-gray-300 py-2.5 pe-3.5 ps-11 text-sm focus:border-[#2D5041] focus:ring-[#2D5041]"
                        style="appearance: none; background-image: none; padding-left: 2.75rem; padding-right: 0.875rem;">
                        <option value="Tourism_office" @selected(old('organizer_type') === 'Tourism_office')>مكتب سياحي</option>
                        <option value="Adventure_group" @selected(old('organizer_type') === 'Adventure_group')>مجموعة مغامرات</option>
                    </select>
                    <svg class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.22 7.47a.75.75 0 0 1 1.06 0L10 11.19l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </div>
                @error('organizer_type')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
            </div>
            <div id="organizer-license-field">
                <label for="license_number" class="mb-1 block text-sm font-bold text-gray-700">رقم الترخيص</label>
                <input id="license_number" type="text" name="license_number" value="{{ old('license_number') }}"
                    class="w-full rounded-xl border-gray-300 px-3.5 py-2.5 text-sm focus:border-[#2D5041] focus:ring-[#2D5041]">
                @error('license_number')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="social_handle_url" class="mb-1 block text-sm font-bold text-gray-700">رابط الموقع أو التواصل (اختياري)</label>
                <input id="social_handle_url" type="url" name="social_handle_url" value="{{ old('social_handle_url') }}"
                    placeholder="https://example.com"
                    class="w-full rounded-xl border-gray-300 px-3.5 py-2.5 text-sm focus:border-[#2D5041] focus:ring-[#2D5041]">
                @error('social_handle_url')<p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
            </div>
            <p class="text-xs leading-5 text-gray-500">سيكون الحساب بحالة «قيد المراجعة» حتى يوافق عليه المسؤول.</p>
        </fieldset>

        <!-- كلمة المرور -->
        <div>
            <label for="password" class="block text-sm font-bold text-gray-700 mb-1">كلمة المرور</label>
            <div class="relative">
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    placeholder="8 أحرف على الأقل"
                    class="w-full rounded-xl border-gray-300 text-sm py-2.5 ps-3.5 pe-12 focus:border-[#2D5041] focus:ring-[#2D5041]">
                <button type="button" data-password-toggle="password" aria-label="إظهار كلمة المرور" aria-pressed="false"
                        class="absolute left-3 top-1/2 -translate-y-1/2 rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-[#2D5041]">
                    <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/>
                        <circle cx="12" cy="12" r="2.5"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- تأكيد كلمة المرور -->
        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">تأكيد كلمة
                المرور</label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password" placeholder="أعد كتابة كلمة المرور"
                    class="w-full rounded-xl border-gray-300 text-sm py-2.5 ps-3.5 pe-12 focus:border-[#2D5041] focus:ring-[#2D5041]">
                <button type="button" data-password-toggle="password_confirmation" aria-label="إظهار تأكيد كلمة المرور" aria-pressed="false"
                        class="absolute left-3 top-1/2 -translate-y-1/2 rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-[#2D5041]">
                    <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/>
                        <circle cx="12" cy="12" r="2.5"/>
                    </svg>
                </button>
            </div>
            @error('password_confirmation')
                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- زر التسجيل -->
        <button type="submit"
            class="w-full py-3 bg-[#A94B2B] hover:bg-[#8E3D22] text-white font-bold text-sm rounded-xl shadow-sm transition cursor-pointer mt-2">
            إنشاء الحساب
        </button>

        <!-- رابط تسجيل الدخول -->
        <p class="text-center text-xs text-gray-600 pt-4 border-t border-gray-100">
            لديك حساب بالفعل؟
            <a href="{{ route('login') }}" class="font-bold text-[#2D5041] hover:underline">
                سجّل الدخول من هنا
            </a>
        </p>
    </form>
    <script>
        const accountRole = document.getElementById('role');
        const organizerType = document.getElementById('organizer_type');
        const organizerFields = document.getElementById('organizer-fields');
        const licenseField = document.getElementById('organizer-license-field');
        const licenseInput = document.getElementById('license_number');

        function updateOrganizerFields() {
            const isOrganizer = accountRole.value === 'Activity_Organizer';
            organizerFields.classList.toggle('hidden', !isOrganizer);
            organizerFields.disabled = !isOrganizer;
            const needsLicense = isOrganizer && organizerType.value === 'Tourism_office';
            licenseField.classList.toggle('hidden', !needsLicense);
            licenseInput.required = needsLicense;
        }

        accountRole.addEventListener('change', updateOrganizerFields);
        organizerType.addEventListener('change', updateOrganizerFields);
        updateOrganizerFields();
    </script>
</x-guest-layout>
