<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    //
    protected $fillable = [
        'adventurers_id',
        'adventure_id',
        'rating',
        'comment',
    ];

    public function adventurer(): BelongsTo {
        return $this->belongsTo(Adventurer::class);
    }

    public function adventure(): BelongsTo {
        return $this->belongsTo(Adventure::class);
    }

    public function review(): HasMany{
        return $this->hasMany(Review::class);   
    }
}
