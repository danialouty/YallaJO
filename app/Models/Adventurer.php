<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Adventurer extends Model
{
    //
    protected $fillable = [
        'user_id',
        'experience_level',
        'interests',
        'location',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function booking(): BelongsToMany{
        return $this->belongsToMany(Booking::class,'bookings');
    }
}
