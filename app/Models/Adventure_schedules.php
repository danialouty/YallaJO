<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Adventure_schedules extends Model
{
    protected $table = 'adventure_schedules';

    protected $fillable = [
        'adventure_id',
        'guide_id',
        'starts_at',
        'ends_at',
        'capacity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'capacity' => 'integer',
        ];
    }

    public function adventure(): BelongsTo
    {
        return $this->belongsTo(Adventure::class);
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guides::class);
    }
}
