<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adventure;

class HomeController extends Controller
{
    //
    public function home()
{
    $adventures = Adventure::where('status', 'active')
        ->latest()
        ->take(4)
        ->get();

    return view('home', compact('adventures'));
}
}
