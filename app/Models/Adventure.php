<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Adventure extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'activity_organizer_id',
        'difficulty_level',
        'start_at',
        'ends_at',
        'status',
    ];

    public function activityOrganizer(): BelongsTo {
        return $this->belongsTo(ActivityOrganizer::class);
    }

    public function review(): HasMany{
        return $this->hasMany(Review::class);   
    }

}
