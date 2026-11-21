<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $communities = Community::query()
            ->withCount('members')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('region', 'like', "%{$search}%")
                        ->orWhere('activity_style', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('region'), fn ($query) =>
                $query->where('region', $request->string('region')->toString())
            )
            ->when($request->filled('activity_style'), fn ($query) =>
                $query->where('activity_style', $request->string('activity_style')->toString())
            )
            ->when(auth()->check() && auth()->user()->adventurer, function ($query) {
                $interests = auth()->user()->adventurer->interests ?? [];
                if (!empty($interests)) {
                    $query->where(function ($query) use ($interests) {
                        foreach ($interests as $interest) {
                            $query->orWhere('activity_style', 'like', "%{$interest}%");
                        }
                    });
                }
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $regions = Community::select('region')->distinct()->orderBy('region')->pluck('region');
        $activityStyles = Community::select('activity_style')->distinct()->orderBy('activity_style')->pluck('activity_style');

        return view('communities.index', compact('communities', 'regions', 'activityStyles'));
    }

    public function show(Community $community)
    {
        $community->load([
            'members:id,name',
            'messages' => fn ($query) => $query->with('user:id,name')->oldest(),
        ]);
        $community->loadCount('members');

        $isMember = auth()->check()
            && $community->memberships()->where('user_id', auth()->id())->exists();

        $membership = auth()->check()
            ? $community->memberships()->where('user_id', auth()->id())->first()
            : null;

        return view('communities.show', compact('community', 'isMember', 'membership'));
    }
}
