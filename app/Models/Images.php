<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Images extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'adventure_id',
        'image_path',
    ];

    public function adventure(): BelongsTo{
        return $this->belongsTo(Adventure::class);
    }
}
