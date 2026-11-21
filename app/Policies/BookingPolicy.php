<?php

namespace App\Policies;

use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'Adventurer';
    }
}
