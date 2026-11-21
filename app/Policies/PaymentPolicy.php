<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->role === 'Adventurer';
    }

    public function view(User $user, Payment $payment): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        }

        if ($user->role !== 'Adventurer') {
            return Response::deny();
        }

        return $this->viewForBooking($user, $payment->booking);
    }

    public function viewForBooking(User $user, ?Booking $booking): Response
    {
        if (! $this->viewAny($user)) {
            return Response::deny();
        }

        if ($booking && ($user->isAdmin() || $booking->adventurer?->user_id === $user->id)) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Payment $payment): bool
    {
        return false;
    }

    public function delete(User $user, Payment $payment): bool
    {
        return false;
    }
}
