<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-extrabold text-gray-900">استعادة كلمة المرور</h2>
        <p class="text-sm text-gray-500 mt-2 leading-relaxed">
            نسيت كلمة المرور؟ لا مشكلة. أدخل بريدك الإلكتروني وسنرسل لك رابطاً لإعادة تعيين كلمة مرور جديدة.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="name@example.com"
                   class="w-full rounded-xl border-gray-300 text-sm py-2.5 px-3.5 focus:border-[#2D5041] focus:ring-[#2D5041]">
            @error('email')
                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-3 bg-[#A94B2B] hover:bg-[#8E3D22] text-white font-bold text-sm rounded-xl shadow-sm transition cursor-pointer">
            إرسال رابط استعادة كلمة المرور
        </button>

        <p class="text-center text-xs text-gray-600 pt-4 border-t border-gray-100">
            <a href="{{ route('login') }}" class="font-bold text-[#2D5041] hover:underline">
                &rarr; العودة لتسجيل الدخول
            </a>
        </p>
    </form>
</x-guest-layout>