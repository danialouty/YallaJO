<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-extrabold text-gray-900">انضم إلى مجتمع يلا جو</h2>
        <p class="text-sm text-gray-500 mt-1">أنشئ حسابك وابدأ بحجز تجاربك القادمة في الأردن</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

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

        <!-- كلمة المرور -->
        <div>
            <label for="password" class="block text-sm font-bold text-gray-700 mb-1">كلمة المرور</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                placeholder="8 أحرف على الأقل"
                class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-3.5 focus:border-[#2D5041] focus:ring-[#2D5041]">
            @error('password')
                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- تأكيد كلمة المرور -->
        <div>
            <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">تأكيد كلمة
                المرور</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" placeholder="أعد كتابة كلمة المرور"
                class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-3.5 focus:border-[#2D5041] focus:ring-[#2D5041]">
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
</x-guest-layout>