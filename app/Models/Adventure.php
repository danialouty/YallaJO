<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Adventure extends Model
{
    protected $table = 'adventures';

    protected $fillable = [
        'title',
        'description',
        'activity_organizer_id',
        'difficulty_level',
        'capacity',
        'category',
        'location',
        'duration_hours',
        'price',
        'requirements',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'duration_hours' => 'decimal:2',
            'price' => 'decimal:2',
        ];
    }

    public function activityOrganizer(): BelongsTo
    {
        return $this->belongsTo(ActivityOrganizer::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Adventure_schedules::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reservedBookings(): HasMany
    {
        return $this->bookings()->whereIn('status', ['Pending', 'Accepted']);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Images::class);
    }

    public function days()
    {
        return $this->hasMany(AdventureDay::class)->orderBy('day_number');
    }
}
