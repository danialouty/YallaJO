<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdventurer
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->role === 'Adventurer', 403, 'صفحة الحجوزات متاحة للمغامرين فقط.');

        return $next($request);
    }
}
