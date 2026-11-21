<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Admin Verification & Revenue') }} — YallaJO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 font-['Cairo',sans-serif] text-slate-100 antialiased">
    <div class="mx-auto max-w-6xl px-4 py-10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ __('Admin Verification & Revenue') }}</h1>
                <p class="mt-1 text-sm text-slate-400">{{ __('Logged in as') }} {{ auth()->user()->name }}</p>
            </div>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                {{ session('status') }}
            </div>
        @endif

        {{-- KPI Summary Cards --}}
        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
                <p class="text-xs tracking-wider text-slate-400">{{ __('Platform Revenue') }}</p>
                <p class="mt-2 text-2xl font-bold text-emerald-400">
                    {{ number_format($totalRevenue, 2) }} {{ __('JOD') }}
                </p>
            </div>
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
                <p class="text-xs tracking-wider text-slate-400">{{ __('Total Bookings') }}</p>
                <p class="mt-2 text-2xl font-bold text-white">{{ $totalBookings }}</p>
            </div>
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
                <p class="text-xs tracking-wider text-slate-400">{{ __('Pending Verifications') }}</p>
                <p class="mt-2 text-2xl font-bold text-amber-400">{{ $pendingCount }}</p>
            </div>
        </div>

        {{-- Organizers Table --}}
        <div class="mt-8 overflow-x-auto rounded-2xl border border-slate-800 bg-slate-900">
            <table class="w-full text-start text-sm">
                <thead class="border-b border-slate-800 bg-slate-900/60 text-xs text-slate-400">
                    <tr>
                        <th class="px-5 py-3.5 text-start">{{ __('Organizer') }}</th>
                        <th class="px-5 py-3.5 text-start">{{ __('Type') }}</th>
                        <th class="px-5 py-3.5 text-start">{{ __('License / Social') }}</th>
                        <th class="px-5 py-3.5 text-start">{{ __('Status') }}</th>
                        <th class="px-5 py-3.5 text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($organizers as $org)
                        @php
                            $badge = match ($org->approval_status) {
                                'Approved' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                                'Rejected' => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                                default    => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                            };
                        @endphp
                        <tr class="hover:bg-slate-800/40">
                            <td class="px-5 py-4">
                                <div class="font-medium text-white">{{ $org->user->name }}</div>
                                <div class="text-xs text-slate-400">
                                    <span dir="ltr" class="inline-block">{{ $org->user->email }}</span>
                                    ·
                                    <span dir="ltr" class="inline-block">{{ $org->user->phone_number ?? __('No phone') }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-300">
                                {{ __($org->organizer_type) }}
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-300">
                                <div>
                                    {{ __('License') }}:
                                    <span dir="ltr" class="inline-block">{{ $org->license_number ?: '—' }}</span>
                                </div>
                                @if ($org->social_handle_url)
                                    <a href="{{ $org->social_handle_url }}" target="_blank" rel="noopener"
                                        class="text-emerald-400 underline hover:text-emerald-300">
                                        {{ __('Social Link') }}
                                    </a>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $badge }}">
                                    {{ __($org->approval_status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($org->approval_status !== 'Approved')
                                        <form method="POST" action="{{ route('admin.organizers.update', $org) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="approval_status" value="Approved">
                                            <button type="submit"
                                                class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-500">
                                                {{ __('Approve') }}
                                            </button>
                                        </form>
                                    @endif

                                    @if ($org->approval_status !== 'Rejected')
                                        <form method="POST" action="{{ route('admin.organizers.update', $org) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="approval_status" value="Rejected">
                                            <button type="submit"
                                                class="rounded-lg bg-amber-600/80 px-3 py-1.5 text-xs font-medium text-white hover:bg-amber-500">
                                                {{ __('Reject') }}
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.organizers.destroy', $org) }}"
                                        data-confirm="{{ __('Delete this organizer profile?') }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="rounded-lg border border-rose-500/40 px-3 py-1.5 text-xs font-medium text-rose-400 hover:bg-rose-500/10">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                                {{ __('No organizer profiles submitted yet.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
