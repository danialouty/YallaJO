<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ActivityOrganizer extends Model
{
    protected $table = 'activity_organizers';

    protected $fillable = [
        'user_id',
        'license_number',
        'organizer_type',
        'approval_status',
        'social_handle_url',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function adventures(): HasMany {
        return $this->hasMany(Adventure::class);
    }

    public function guide(): HasOne
    {
        return $this->hasOne(Guides::class, 'activity_organizer_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    
    
}
