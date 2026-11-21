<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBookingViewer
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $canView = $user?->role === 'Adventurer'
            || ($user?->role === 'Activity_Organizer' && $user->isApprovedOrganizer());

        abort_unless($canView, 403);

        return $next($request);
    }
}
