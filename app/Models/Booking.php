<?php

namespace App\Models;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'adventure_id',
        'adventurer_id',
        'participants_count',
        'unit_price',
        'commission_rate',
        'total_amount',
        'status',
        'capacity',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'participants_count' => 'integer',
            'unit_price' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'capacity' => 'integer',
            'cancelled_at' => 'datetime',
        ];
    }

    public function adventure(): BelongsTo{
        return $this->belongsTo(Adventure::class);
    }

    public function adventurer(): BelongsTo{
        return $this->belongsTo(Adventurer::class);
    }

    public function payment(): HasOne{
        return $this->hasOne(Payment::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function isChangeable(): bool
    {
        return $this->status === 'Pending'
            && ! ($this->payment_exists ?? $this->payment()->exists())
            && ! ($this->review_exists ?? $this->review()->exists());
    }

    public static function calculateTotal(string $unitPrice, int $participants): string
    {
        $unitCents = (int) str_replace('.', '', $unitPrice);
        if ($unitCents < 0 || ($unitCents > 0 && $participants > intdiv(999999999, $unitCents))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'participants_count' => 'الإجمالي خارج النطاق المدعوم.',
            ]);
        }

        $totalCents = $unitCents * $participants;

        return sprintf('%d.%02d', intdiv($totalCents, 100), $totalCents % 100);
    }
}
