<?php

namespace App\Models;

use Database\Factories\AdventurerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Adventurer extends Model
{
    /** @use HasFactory<AdventurerFactory> */
    use HasFactory;

    protected $table = 'adventurers';

    protected $fillable = [
        'user_id',
        'experience_level',
        'interests',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'interests' => 'array',
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
