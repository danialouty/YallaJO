<x-app-layout>
    <main class="mx-auto max-w-3xl space-y-5 px-4 py-10 sm:px-6 lg:px-8" dir="rtl">
        <header class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm font-bold text-emerald-800">تسجيل المنظّمين</p>
            <h1 class="mt-1 text-2xl font-extrabold text-slate-900">ملف المنظّم</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">أكمل بيانات منظمتك لمراجعتها. ستتمكن من إدارة المغامرات والمرشدين بعد الموافقة.</p>
        </header>

        @if (session('status') || session('error'))
            <div class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-900" role="status">{{ session('status') ?? session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="rounded-xl bg-red-50 p-4 text-sm text-red-800" role="alert">
                <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        @if ($organizer)
            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="font-bold text-slate-900">{{ auth()->user()->name }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ __($organizer->organizer_type) }} · {{ $organizer->license_number ?: 'لا يتطلب رقم ترخيص' }}</p>
                    </div>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-bold text-amber-900">{{ __($organizer->approval_status) }}</span>
                </div>
                @if ($organizer->approval_status === 'Approved')
                    <a href="{{ route('organizer.dashboard') }}" class="mt-5 inline-flex rounded-lg bg-emerald-800 px-4 py-2.5 text-sm font-bold text-white">فتح لوحة المنظّم</a>
                @elseif ($organizer->approval_status === 'Rejected')
                    <p class="mt-4 text-sm text-slate-600">عدّل البيانات ثم أرسلها للمراجعة مرة أخرى.</p>
                    <button type="button" data-modal-open="organizer-profile-modal" class="mt-3 rounded-lg bg-orange-700 px-4 py-2.5 text-sm font-bold text-white">تعديل البيانات</button>
                @else
                    <p class="mt-4 text-sm text-slate-600">طلبك قيد المراجعة. ستظهر لك صفحات الإدارة بعد الموافقة.</p>
                @endif
            </section>
        @else
            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="font-bold text-slate-900">أرسل بيانات منظمتك</h2>
                <button type="button" data-modal-open="organizer-profile-modal" class="mt-4 rounded-lg bg-orange-700 px-4 py-2.5 text-sm font-bold text-white">إكمال الملف</button>
            </section>
        @endif

        @include('organizer.partials.profile-modal', ['organizer' => $organizer])
    </main>
</x-app-layout>
