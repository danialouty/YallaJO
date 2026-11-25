<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adventure;
use App\Models\Community;

class HomeController extends Controller
{
    //
    public function home()
{
    $adventures = Adventure::with([
        'images',
        'schedules' => fn ($query) => $query
            ->with('guide.activityOrganizer.user')
            ->orderBy('starts_at'),
    ])
        ->where('status', 'Active')
        ->latest()
        ->take(4)
        ->get();

    $communities = Community::withCount('members')
        ->latest()
        ->take(3)
        ->get();

    return view('home', compact('adventures', 'communities'));
}
}
