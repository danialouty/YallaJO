<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityOrganizer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'in:Admin,Adventurer,Activity_Organizer'],
        ], [
            'search.*' => 'أدخل قيمة بحث صالحة.',
            'role.in' => 'اختر دورًا صالحًا.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.users.index')->withErrors($validator);
        }

        $filters = $validator->validated();
        $query = User::query()->select('id', 'name', 'email', 'role', 'created_at')->latest('id');

        if (filled($filters['search'] ?? null)) {
            $search = '%'.$filters['search'].'%';
            $query->where(fn ($users) => $users->whereLike('name', $search)->orWhereLike('email', $search));
        }

        if (filled($filters['role'] ?? null)) {
            $query->where('role', $filters['role']);
        }

        return view('admin.users.index', [
            'records' => $query->paginate(15)->appends($filters),
            'filters' => $filters,
        ]);
    }

    public function show(string $user): View
    {
        $user = User::query()->select('id', 'name', 'email', 'role', 'created_at')
            ->with(['adventurer:id,user_id,experience_level,location', 'activityOrganizer:id,user_id,organizer_type,approval_status'])
            ->findOrFail($user);

        return view('admin.users.show', compact('user'));
    }

    public function create(): View
    {
        return view('admin.users.form', ['user' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->accountRules() + [
            'role' => ['required', 'in:Admin,Adventurer,Activity_Organizer'],
            'organizer_type' => ['exclude_unless:role,Activity_Organizer', 'required', Rule::in([ActivityOrganizer::TOURISM_OFFICE, ActivityOrganizer::ADVENTURE_GROUP])],
            'license_number' => ['exclude_unless:role,Activity_Organizer', 'nullable', 'required_if:organizer_type,'.ActivityOrganizer::TOURISM_OFFICE, 'string', 'max:100'],
        ]);

        $user = DB::transaction(function () use ($data) {
            // User's hashed cast handles passwords before insertion.
            $user = User::create(Arr::only($data, ['name', 'email', 'phone_number', 'password', 'role']));

            if ($user->role === 'Activity_Organizer') {
                $user->activityOrganizer()->create([
                    'organizer_type' => $data['organizer_type'],
                    'license_number' => $data['license_number'] ?? '',
                    'approval_status' => ActivityOrganizer::PENDING,
                ]);
            } elseif ($user->role === 'Adventurer') {
                $user->adventurer()->create();
            }

            return $user;
        });

        return redirect()->route('admin.users.show', $user)->with('success', 'أُنشئ حساب المستخدم.');
    }

    private function accountRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }
}
