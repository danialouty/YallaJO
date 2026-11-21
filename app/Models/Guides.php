<?php

namespace App\Models;

use Database\Factories\GuidesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Guides extends Model
{
    /** @use HasFactory<GuidesFactory> */
    use HasFactory;

    protected $table = 'guides';

    protected $fillable = [
        'activity_organizer_id',
        'bio',
        'experience_years',
        'specialization',
        'cetification',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'experience_years' => 'integer',
            'specialization' => 'array',
        ];
    }

    public function activityOrganizer(): BelongsTo
    {
        return $this->belongsTo(ActivityOrganizer::class, 'activity_organizer_id');
    }

    public function availability(): HasOne
    {
        return $this->hasOne(Guides_availabilities::class, 'guide_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Adventure_schedules::class, 'guide_id');
    }
}
