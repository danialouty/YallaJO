<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adventure;
use App\Models\ActivityOrganizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdventureController extends Controller
{
    private const CATEGORIES = ['Hiking', 'Diving', 'Camping', 'Cyciling', 'Zaitoon day out', 'kayaking', 'climbing', 'Sky Diving', 'Blimping', 'Toursim'];

    public function index(Request $request): View|RedirectResponse
    {
        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:Active,Suspended'],
            'difficulty_level' => ['nullable', 'in:Easy,Medium,Hard'],
        ], [
            'search.*' => 'أدخل قيمة بحث صالحة.',
            'status.in' => 'اختر حالة صالحة.',
            'difficulty_level.in' => 'اختر مستوى صعوبة صالحًا.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.adventures.index')->withErrors($validator);
        }

        $filters = $validator->validated();
        $query = Adventure::with('activityOrganizer.user:id,name')->latest('id');

        if (filled($filters['search'] ?? null)) {
            $query->whereLike('title', '%'.$filters['search'].'%');
        }

        foreach (['status', 'difficulty_level'] as $field) {
            if (filled($filters[$field] ?? null)) {
                $query->where($field, $filters[$field]);
            }
        }

        return view('admin.adventures.index', [
            'records' => $query->paginate(15)->appends($filters),
            'filters' => $filters,
        ]);
    }

    public function show(string $adventure): View
    {
        $adventure = Adventure::with('activityOrganizer.user:id,name')->findOrFail($adventure);

        return view('admin.adventures.show', compact('adventure'));
    }

    public function create(): View
    {
        return view('admin.adventures.form', ['adventure' => null, 'categories' => self::CATEGORIES]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules() + [
            'activity_organizer_id' => ['required', 'integer', 'exists:activity_organizers,id'],
        ]);
        $adventure = DB::transaction(function () use ($data) {
            $organizer = ActivityOrganizer::query()->lockForUpdate()->findOrFail($data['activity_organizer_id']);
            unset($data['activity_organizer_id']);

            return $organizer->adventures()->create($data);
        });

        return redirect()->route('admin.adventures.show', $adventure)->with('success', 'أُنشئت المغامرة.');
    }

    public function edit(string $adventure): View
    {
        $adventure = Adventure::findOrFail($adventure);

        return view('admin.adventures.form', ['adventure' => $adventure, 'categories' => self::CATEGORIES]);
    }

    public function update(Request $request, string $adventure): RedirectResponse
    {
        $data = $request->validate($this->rules() + ['activity_organizer_id' => ['missing']]);

        DB::transaction(function () use ($adventure, $data) {
            $record = Adventure::query()->lockForUpdate()->findOrFail($adventure);
            if ((int) $data['capacity'] < $record->capacity
                && $record->reservedBookings()->sum('participants_count') > (int) $data['capacity']) {
                throw ValidationException::withMessages(['capacity' => 'السعة الجديدة أقل من عدد المشاركين المحجوز بالفعل.']);
            }
            $record->update($data);
        });

        return redirect()->route('admin.adventures.show', $adventure)->with('success', 'حُفظت بيانات المغامرة.');
    }

    public function suspend(string $adventure): RedirectResponse
    {
        $record = Adventure::findOrFail($adventure);
        $record->update(['status' => 'Suspended']);

        return redirect()->route('admin.adventures.show', $record)->with('success', 'عُلّقت المغامرة وبقيت سجلاتها المرتبطة محفوظة.');
    }

    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'difficulty_level' => ['required', 'in:Easy,Medium,Hard'],
            'capacity' => ['required', 'integer', 'between:1,2147483647'],
            'category' => ['required', Rule::in(self::CATEGORIES)],
            'location' => ['required', 'string', 'max:255'],
            'duration_hours' => ['required', 'numeric', 'decimal:0,2', 'gt:0', 'max:999.99'],
            'price' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999.99'],
            'requirements' => ['required', 'string', 'max:10000'],
            'status' => ['required', 'in:Active,Suspended'],
        ];
    }
}
