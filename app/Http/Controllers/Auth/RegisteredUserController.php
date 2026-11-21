<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Adventurer;
use App\Models\ActivityOrganizer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $role = request()->query('role') === 'Activity_Organizer' ? 'Activity_Organizer' : 'Adventurer';

        return view('auth.register', compact('role'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['sometimes', 'in:Adventurer,Activity_Organizer'],
            'organizer_type' => ['required_if:role,Activity_Organizer', 'nullable', 'in:Adventure_group,Tourism_office'],
            'license_number' => ['nullable', 'required_if:organizer_type,Tourism_office', 'string', 'max:255'],
            'social_handle_url' => ['nullable', 'url', 'max:255'],
        ]);
        $validated['role'] ??= 'Adventurer';

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            if ($validated['role'] === 'Activity_Organizer') {
                ActivityOrganizer::create([
                    'user_id' => $user->id,
                    'organizer_type' => $validated['organizer_type'],
                    'license_number' => $validated['license_number'] ?? null,
                    'social_handle_url' => $validated['social_handle_url'] ?? null,
                    'approval_status' => 'Pending',
                ]);
            } else {
                Adventurer::create([
                    'user_id' => $user->id,
                    'interests' => [],
                ]);
            }

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return $user->role === 'Activity_Organizer'
            ? redirect()->route('organizers.index')
            : redirect(route('bookings.index', absolute: false));
    }
}
