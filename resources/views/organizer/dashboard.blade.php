<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Dashboard — YallaJO</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="mx-auto max-w-4xl px-4 py-10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Organizer Dashboard</h1>
                <p class="text-sm text-slate-400">
                    Logged in as {{ auth()->user()->name }} ({{ auth()->user()->email }})
                </p>
            </div>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 rounded-2xl border border-slate-800 bg-slate-900 p-6">
            @if (!$organizer)
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-amber-400">Organizer Profile Incomplete</h2>
                        <p class="mt-1 text-sm text-slate-400">
                            Submit your tourism office license or hike group details to get verified and start publishing adventures.
                        </p>
                    </div>
                    <button type="button"
                        onclick="document.getElementById('organizer-profile-modal').showModal()"
                        class="shrink-0 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-500">
                        Complete Profile
                    </button>
                </div>
            @else
                @php
                    $badgeStyles = match ($organizer->approval_status) {
                        'approved' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                        'rejected' => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                        default    => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                    };
                @endphp

                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-lg font-semibold">Organizer Verification Profile</h2>
                            <span class="rounded-full border px-3 py-0.5 text-xs font-medium uppercase {{ $badgeStyles }}">
                                {{ $organizer->approval_status }}
                            </span>
                        </div>
                        <dl class="mt-3 space-y-1 text-sm text-slate-300">
                            <div>
                                <span class="text-slate-500">Type:</span>
                                {{ str_replace('_', ' ', ucfirst($organizer->organizer_type)) }}
                            </div>
                            <div>
                                <span class="text-slate-500">License Number:</span>
                                {{ $organizer->license_number ?: 'N/A' }}
                            </div>
                            <div>
                                <span class="text-slate-500">Social / Website:</span>
                                @if ($organizer->social_handle_url)
                                    <a href="{{ $organizer->social_handle_url }}" target="_blank" rel="noopener"
                                        class="text-emerald-400 underline hover:text-emerald-300">
                                        {{ $organizer->social_handle_url }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </div>
                        </dl>
                    </div>

                    <button type="button"
                        onclick="document.getElementById('organizer-profile-modal').showModal()"
                        class="shrink-0 rounded-lg border border-slate-700 bg-slate-800 px-4 py-2 text-sm font-medium text-slate-200 hover:bg-slate-700">
                        Edit Profile
                    </button>
                </div>
            @endif
        </div>
    </div>

    @include('organizer.partials.profile-modal', ['organizer' => $organizer])
</body>
</html>
