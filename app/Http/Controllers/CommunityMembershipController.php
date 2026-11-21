<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityMembership;
use Illuminate\Http\Request;

class CommunityMembershipController extends Controller
{
    public function store(Request $request, Community $community)
    {
        abort_unless($request->user()->role === 'Adventurer', 403);
        abort_unless($request->user()->adventurer()->exists(), 403, 'أنشئ ملفك كمغامر أولاً.');

        $community->memberships()->firstOrCreate(
            ['user_id' => $request->user()->id],
            ['role' => 'member']
        );

        return back()->with('success', 'انضممت إلى المجتمع بنجاح.');
    }

    public function destroy(Request $request, Community $community, CommunityMembership $membership)
    {
        abort_unless($membership->community_id === $community->id, 404);
        abort_unless($membership->user_id === $request->user()->id, 403);

        $membership->delete();

        return back()->with('success', 'تمت مغادرتك للمجتمع.');
    }
}
