<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ActivityOrganizer extends Model
{
    //
    protected $fillable = [
        'user_id',
        'license_number',
        'organizer_type',
        'approval_status ',
        'social_handle_url',
        'approved_by',
        'approved_at',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function adventure(): HasMany {
        return $this->hasMany(Adventure::class);
    }

    public function booking(): BelongsToMany{
        return $this->belongsToMany(Booking::class,'bookings');
    }
    
}
