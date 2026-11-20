<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-extrabold text-gray-900">أهلاً بعودتك!</h2>
        <p class="text-sm text-gray-500 mt-1">سجّل دخولك لمتابعة مغامراتك وحجوزاتك</p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- البريد الإلكتروني -->
        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   placeholder="name@example.com"
                   class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-3.5 focus:border-[#2D5041] focus:ring-[#2D5041]">
            @error('email')
                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- كلمة المرور -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="password" class="block text-sm font-bold text-gray-700">كلمة المرور</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#2D5041] hover:underline">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-3.5 focus:border-[#2D5041] focus:ring-[#2D5041]">
            @error('password')
                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- تذكرني -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-gray-300 text-[#2D5041] focus:ring-[#2D5041]">
                <span class="text-xs text-gray-600 font-medium">تذكرني على هذا الجهاز</span>
            </label>
        </div>

        <!-- زر الدخول -->
        <button type="submit"
                class="w-full py-3 bg-[#A94B2B] hover:bg-[#8E3D22] text-white font-bold text-sm rounded-xl shadow-sm transition cursor-pointer mt-2">
            تسجيل الدخول
        </button>

        <!-- رابط إنشاء حساب -->
        <p class="text-center text-xs text-gray-600 pt-4 border-t border-gray-100">
            ليس لديك حساب بعد؟
            <a href="{{ route('register') }}" class="font-bold text-[#2D5041] hover:underline">
                أنشئ حساب مغامر جديد
            </a>
        </p>
    </form>
</x-guest-layout>