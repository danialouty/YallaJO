<?php

namespace App\Http\Controllers;

use App\Models\ActivityOrganizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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

    public function show(ActivityOrganizer $organizer): View
    {
        $organizer->load(['user:id,name,email,phone_number', 'approver:id,name']);

        return view('admin.organizer-records.show', ['organizer' => $organizer, 'types' => self::TYPES, 'statuses' => self::STATUSES]);
    }
}
