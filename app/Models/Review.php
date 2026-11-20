<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'adventurer_id',
        'adventure_id',
        'booking_id',
        'rating',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function adventurer(): BelongsTo {
        return $this->belongsTo(Adventurer::class);
    }

    public function adventure(): BelongsTo {
        return $this->belongsTo(Adventure::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
