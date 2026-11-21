<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Organizer Dashboard') }} — YallaJO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 font-['Cairo',sans-serif] text-slate-100 antialiased">
    <div class="mx-auto max-w-4xl px-4 py-10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ __('Organizer Dashboard') }}</h1>
                <p class="mt-1 text-sm text-slate-400">
                    {{ __('Logged in as') }} {{ auth()->user()->name }}
                    (<span dir="ltr" class="inline-block">{{ auth()->user()->email }}</span>)
                </p>
            </div>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                {{ __(session('status')) }}
            </div>
        @endif

        <div class="mt-6 rounded-2xl border border-slate-800 bg-slate-900 p-6">
            @if (!$organizer)
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-amber-400">{{ __('Organizer Profile Incomplete') }}</h2>
                        <p class="mt-1 text-sm text-slate-400">
                            {{ __('Submit your tourism office license or hike group details to get verified and start publishing adventures.') }}
                        </p>
                    </div>
                    <button type="button"
                        data-modal-open="organizer-profile-modal"
                        class="shrink-0 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-500">
                        {{ __('Complete Profile') }}
                    </button>
                </div>
            @else
                @php
                    $badgeStyles = match ($organizer->approval_status) {
                        'Approved', 'approved' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                        'Rejected', 'rejected' => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                        default    => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                    };
                @endphp

                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-lg font-semibold">{{ __('Organizer Verification Profile') }}</h2>
                            <span class="rounded-full border px-3 py-0.5 text-xs font-medium {{ $badgeStyles }}">
                                {{ __($organizer->approval_status) }}
                            </span>
                        </div>
                        <dl class="mt-3 space-y-1.5 text-sm text-slate-300">
                            <div>
                                <span class="text-slate-500">{{ __('Type') }}:</span>
                                {{ __($organizer->organizer_type) }}
                            </div>
                            <div>
                                <span class="text-slate-500">{{ __('License Number') }}:</span>
                                <span dir="ltr" class="inline-block">{{ $organizer->license_number ?: '—' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500">{{ __('Social / Website') }}:</span>
                                @if ($organizer->social_handle_url)
                                    <a href="{{ $organizer->social_handle_url }}" target="_blank" rel="noopener" dir="ltr"
                                        class="inline-block text-emerald-400 underline hover:text-emerald-300">
                                        {{ $organizer->social_handle_url }}
                                    </a>
                                @else
                                    —
                                @endif
                            </div>
                        </dl>
                    </div>

                    <button type="button"
                        data-modal-open="organizer-profile-modal"
                        class="shrink-0 rounded-lg border border-slate-700 bg-slate-800 px-4 py-2 text-sm font-medium text-slate-200 hover:bg-slate-700">
                        {{ __('Edit Profile') }}
                    </button>
                </div>
                @if (in_array($organizer->approval_status, ['Approved', 'approved'], true))
                    <div class="mt-6 border-t border-slate-800 pt-5">
                        <a href="{{ route('guides.index') }}"
                           class="inline-flex rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-500">
                            إدارة ملف المرشد والجداول
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @include('organizer.partials.profile-modal', ['organizer' => $organizer])
</body>
</html>
