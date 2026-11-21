<?php

namespace App\Policies;

use App\Models\Adventure;
use App\Models\User;

class AdventurePolicy
{
    public function manage(User $user, Adventure $adventure): bool
    {
        return $user->isApprovedOrganizer()
            && (int) $adventure->activity_organizer_id === (int) $user->activityOrganizer->id;
    }
}
