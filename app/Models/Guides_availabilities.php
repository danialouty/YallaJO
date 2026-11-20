<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guides_availabilities extends Model
{
    protected $table = 'guides_availabilities';

    protected $fillable = [
        'guide_id',
        'status',
    ];

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guides::class, 'guide_id');
    }
}
