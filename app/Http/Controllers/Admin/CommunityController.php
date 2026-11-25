<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $validator = Validator::make($request->query(), [
            'search' => ['nullable', 'string', 'max:100'],
        ], [
            'search.string' => 'أدخل عبارة بحث نصية.',
            'search.max' => 'الحد الأقصى لعبارة البحث ١٠٠ حرف.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.communities.index')->withErrors($validator);
        }

        $filters = $validator->validated();
        $query = Community::query()->withCount(['memberships', 'messages'])->latest('id');

        if (filled($filters['search'] ?? null)) {
            $pattern = '%'.strtr($filters['search'], ['!' => '!!', '%' => '!%', '_' => '!_']).'%';
            $query->where(function ($communities) use ($pattern) {
                foreach (['name', 'slug', 'region', 'activity_style'] as $column) {
                    $communities->orWhereRaw("$column LIKE ? ESCAPE '!'", [$pattern]);
                }
            });
        }

        return view('admin.communities.index', [
            'records' => $query->paginate(15)->appends($filters),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.communities.form', ['community' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->communityRules());
        $community = Community::create($data);

        return redirect()->route('admin.communities.show', $community)->with('success', 'أُنشئ المجتمع.');
    }

    public function show(Community $community): View
    {
        $community->loadCount(['memberships', 'messages']);
        $messages = $community->messages()
            ->with('user:id,name,email')
            ->latest('id')
            ->paginate(20);

        return view('admin.communities.show', compact('community', 'messages'));
    }

    public function edit(Community $community): View
    {
        return view('admin.communities.form', compact('community'));
    }

    public function update(Request $request, Community $community): RedirectResponse
    {
        $community->update($request->validate($this->communityRules($community)));

        return redirect()->route('admin.communities.show', $community)->with('success', 'حُفظت بيانات المجتمع.');
    }

    public function destroy(Request $request, Community $community): RedirectResponse
    {
        $request->validate([
            'confirm_delete' => ['accepted'],
        ], [
            'confirm_delete.accepted' => 'أكّد رغبتك في حذف المجتمع.',
        ]);

        $deleted = DB::transaction(function () use ($community) {
            $current = Community::query()->lockForUpdate()->findOrFail($community->getKey());

            if ($current->messages()->exists()) {
                return false;
            }

            $current->delete();

            return true;
        });

        if (! $deleted) {
            return redirect()->route('admin.communities.show', $community)
                ->with('error', 'لا يمكن حذف هذا المجتمع لاحتوائه على رسائل؛ حُفظت الرسائل دون تغيير.');
        }

        return redirect()->route('admin.communities.index')->with('success', 'حُذف المجتمع.');
    }

    private function communityRules(?Community $community = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'alpha_dash:ascii', 'max:255', Rule::unique('communities', 'slug')->ignore($community)],
            'description' => ['required', 'string', 'max:10000'],
            'region' => ['required', 'string', 'max:255'],
            'activity_style' => ['required', 'string', 'max:255'],
            'cover_image' => ['nullable', 'url:http,https', 'max:255'],
        ];
    }
}
