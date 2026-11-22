<?php

namespace App\Models;

use Database\Factories\ReviewFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    /** @use HasFactory<ReviewFactory> */
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'adventurer_id',
        'adventure_id',
        'booking_id',
        'rating',
        'comment',
        'is_reported',
        'report_status',
        'report_reason',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_reported' => 'boolean',
        ];
    }

    public function adventurer(): BelongsTo
    {
        return $this->belongsTo(Adventurer::class);
    }

    public function adventure(): BelongsTo
    {
        return $this->belongsTo(Adventure::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
