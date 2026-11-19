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

        if (!$user || $user->role !== 'organizer') {
            abort(403, 'Only organizers can access this area.');
        }

        if (!$user->organizerProfile) {
            return redirect()->route('organizer.dashboard')
                ->with('error', 'Please complete your organizer profile first.');
        }

        if ($user->organizerProfile->approval_status !== 'approved') {
            return redirect()->route('organizer.dashboard')
                ->with('error', 'Your organizer account is pending admin approval.');
        }

        return $next($request);
    }
}
