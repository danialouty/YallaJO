<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    //
    protected $fillable = [
        'adventure_id',
        'adventurers_id',
        'count',
    ];

    public function adventure(): BelongsTo{
        return $this->belongsTo(Adventure::class);
    }

    public function adventurer(): BelongsTo{
        return $this->belongsTo(Adventurer::class);
    }

    public function payment(): HasOne{
        return $this->hasOne(Payment::class);
    }
}
