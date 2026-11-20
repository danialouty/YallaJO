<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizerIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'Activity_Organizer') {
            abort(403, 'Only organizers can access this area.');
        }

        if (!$user->activity_organizer) {
            return redirect()->route('organizers.index')
                ->with('error', 'Please complete your organizer profile first.');
        }

        if ($user->activity_organizer->approval_status !== 'approved') {
            return redirect()->route('organizers.index')
                ->with('error', 'Your organizer account is pending admin approval.');
        }

        return $next($request);
    }
}
