<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — YallaJO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="mx-auto max-w-6xl px-4 py-10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Admin Verification & Revenue</h1>
                <p class="text-sm text-slate-400">Logged in as {{ auth()->user()->name }}</p>
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
                <p class="text-xs uppercase tracking-wider text-slate-400">Platform Revenue</p>
                <p class="mt-2 text-2xl font-bold text-emerald-400">{{ number_format($totalRevenue, 2) }} JOD</p>
            </div>
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
                <p class="text-xs uppercase tracking-wider text-slate-400">Total Bookings</p>
                <p class="mt-2 text-2xl font-bold text-white">{{ $totalBookings }}</p>
            </div>
            <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
                <p class="text-xs uppercase tracking-wider text-slate-400">Pending Verifications</p>
                <p class="mt-2 text-2xl font-bold text-amber-400">{{ $pendingCount }}</p>
            </div>
        </div>

        {{-- Organizers Table --}}
        <div class="mt-8 overflow-x-auto rounded-2xl border border-slate-800 bg-slate-900">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-800 bg-slate-900/60 text-xs uppercase text-slate-400">
                    <tr>
                        <th class="px-5 py-3.5">Organizer</th>
                        <th class="px-5 py-3.5">Type</th>
                        <th class="px-5 py-3.5">License / Social</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($organizers as $org)
                        @php
                            $badge = match ($org->approval_status) {
                                'approved' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                                'rejected' => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                                default    => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                            };
                        @endphp
                        <tr class="hover:bg-slate-800/40">
                            <td class="px-5 py-4">
                                <div class="font-medium text-white">{{ $org->user->name }}</div>
                                <div class="text-xs text-slate-400">{{ $org->user->email }} · {{ $org->user->phone_number ?? 'No phone' }}</div>
                            </td>
                            <td class="px-5 py-4 capitalize text-slate-300">
                                {{ str_replace('_', ' ', $org->organizer_type) }}
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-300">
                                <div>License: {{ $org->license_number ?: '—' }}</div>
                                @if ($org->social_handle_url)
                                    <a href="{{ $org->social_handle_url }}" target="_blank" rel="noopener"
                                        class="text-emerald-400 underline hover:text-emerald-300">
                                        Social Link
                                    </a>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full border px-2.5 py-0.5 text-xs font-medium uppercase {{ $badge }}">
                                    {{ $org->approval_status }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($org->approval_status !== 'approved')
                                        <form method="POST" action="{{ route('admin.organizers.update', $org) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="approval_status" value="approved">
                                            <button type="submit"
                                                class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-500">
                                                Approve
                                            </button>
                                        </form>
                                    @endif

                                    @if ($org->approval_status !== 'rejected')
                                        <form method="POST" action="{{ route('admin.organizers.update', $org) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="approval_status" value="rejected">
                                            <button type="submit"
                                                class="rounded-lg bg-amber-600/80 px-3 py-1.5 text-xs font-medium text-white hover:bg-amber-500">
                                                Reject
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.organizers.destroy', $org) }}"
                                        onsubmit="return confirm('Delete this organizer profile?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="rounded-lg border border-rose-500/40 px-3 py-1.5 text-xs font-medium text-rose-400 hover:bg-rose-500/10">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                                No organizer profiles submitted yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
