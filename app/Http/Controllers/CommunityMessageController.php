<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;

class CommunityMessageController extends Controller
{
    public function store(Request $request, Community $community)
    {
        abort_unless(
            $community->memberships()->where('user_id', $request->user()->id)->exists(),
            403,
            'يجب الانضمام إلى المجتمع أولاً.'
        );

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $community->messages()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return back();
    }
}
