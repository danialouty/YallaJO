<?php

namespace App\Http\Controllers;

use App\Models\ActivityOrganizer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminOrganizerController extends Controller
{
    private const TYPES = [
        ActivityOrganizer::TOURISM_OFFICE => 'مكتب سياحة',
        ActivityOrganizer::ADVENTURE_GROUP => 'مجموعة مغامرات',
    ];

    private const STATUSES = [
        ActivityOrganizer::PENDING => 'بانتظار الموافقة',
        ActivityOrganizer::APPROVED => 'معتمد',
        ActivityOrganizer::REJECTED => 'مرفوض',
        ActivityOrganizer::BANNED => 'محظور',
    ];

    public function index(Request $request): View|RedirectResponse
    {
        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(array_keys(self::STATUSES))],
        ], [
            'search.string' => 'أدخل عبارة بحث نصية.',
            'search.max' => 'الحد الأقصى لعبارة البحث ١٠٠ حرف.',
            'status.in' => 'اختر حالة منظّم صالحة.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.organizers.index')->withErrors($validator);
        }

        $filters = $validator->validated();
        $query = ActivityOrganizer::with(['user:id,name,email,phone_number', 'approver:id,name'])->latest('id');

        if (filled($filters['search'] ?? null)) {
            $pattern = '%'.strtr($filters['search'], ['!' => '!!', '%' => '!%', '_' => '!_']).'%';
            $query->where(function ($organizers) use ($pattern) {
                $organizers->whereRaw("license_number LIKE ? ESCAPE '!'", [$pattern])
                    ->orWhereHas('user', function ($users) use ($pattern) {
                        $users->whereRaw("name LIKE ? ESCAPE '!'", [$pattern])
                            ->orWhereRaw("email LIKE ? ESCAPE '!'", [$pattern])
                            ->orWhereRaw("phone_number LIKE ? ESCAPE '!'", [$pattern]);
                    });
            });
        }

        if (filled($filters['status'] ?? null)) {
            $query->where('approval_status', $filters['status']);
        }

        return view('admin.organizers', [
            'organizers' => $query->paginate(15)->appends($filters),
            'filters' => $filters,
            'statuses' => self::STATUSES,
            'types' => self::TYPES,
        ]);
    }

    public function create(): View
    {
        return view('admin.organizer-records.form', ['organizer' => null, 'types' => self::TYPES]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->profileRules() + [
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 'Activity_Organizer')],
        ]);

        $organizer = DB::transaction(function () use ($data) {
            $user = User::query()->lockForUpdate()->findOrFail($data['user_id']);
            if ($user->role !== 'Activity_Organizer' || $user->adventurer()->exists() || $user->activityOrganizer()->exists()) {
                throw ValidationException::withMessages(['user_id' => 'يلزم حساب منظّم لا يملك ملفًا مرتبطًا بالفعل.']);
            }
            unset($data['user_id']);
            $data['license_number'] ??= '';
            $data['approval_status'] = ActivityOrganizer::PENDING;

            return $user->activityOrganizer()->create($data);
        });

        return redirect()->route('admin.organizers.show', $organizer)->with('success', 'أُنشئ ملف المنظّم بانتظار الموافقة.');
    }

    public function show(ActivityOrganizer $organizer): View
    {
        $organizer->load(['user:id,name,email,phone_number', 'approver:id,name']);

        return view('admin.organizer-records.show', ['organizer' => $organizer, 'types' => self::TYPES, 'statuses' => self::STATUSES]);
    }

    public function edit(ActivityOrganizer $organizer): View
    {
        return view('admin.organizer-records.form', ['organizer' => $organizer, 'types' => self::TYPES]);
    }

    public function updateProfile(Request $request, ActivityOrganizer $organizer): RedirectResponse
    {
        $data = $request->validate($this->profileRules() + ['user_id' => ['missing']]);
        $data['license_number'] ??= '';
        $organizer->update($data);

        return redirect()->route('admin.organizers.show', $organizer)->with('success', 'حُفظ ملف المنظّم.');
    }

    private function profileRules(): array
    {
        return [
            'organizer_type' => ['required', Rule::in(array_keys(self::TYPES))],
            'license_number' => ['nullable', 'required_if:organizer_type,'.ActivityOrganizer::TOURISM_OFFICE, 'string', 'max:100'],
            'social_handle_url' => ['nullable', 'url:http,https', 'max:255'],
            'approval_status' => ['missing'],
            'approved_by' => ['missing'],
            'approved_at' => ['missing'],
        ];
    }

    public function update(Request $request, ActivityOrganizer $organizer): RedirectResponse
    {
        $validated = $request->validate([
            'approval_status' => ['required', Rule::in([ActivityOrganizer::APPROVED, ActivityOrganizer::REJECTED])],
        ], [
            'approval_status.required' => 'اختر قرار الاعتماد.',
            'approval_status.in' => 'القرار المتاح هو الاعتماد أو الرفض.',
        ]);

        $status = $validated['approval_status'];
        $updated = DB::transaction(function () use ($organizer, $status, $request) {
            $current = ActivityOrganizer::query()->lockForUpdate()->findOrFail($organizer->getKey());

            if ($current->approval_status === ActivityOrganizer::BANNED) {
                return false;
            }

            if ($current->approval_status !== $status) {
                $current->approval_status = $status;

                if ($status === ActivityOrganizer::APPROVED) {
                    $current->approved_by = $request->user()->id;
                    $current->approved_at = now();
                }

                $current->save();
            }

            return true;
        });

        $redirect = redirect()->route('admin.organizers.index');

        return $updated
            ? $redirect->with('status', 'حالة المنظّم: '.self::STATUSES[$status].'.')
            : $redirect->with('error', 'يتطلب تغيير حالة المنظّم المحظور إجراءً منفصلًا.');
    }

    public function destroy(ActivityOrganizer $organizer): never
    {
        abort(403, 'حذف ملفات المنظّمين غير متاح.');
    }
}
