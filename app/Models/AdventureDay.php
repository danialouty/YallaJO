<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdventureDay extends Model
{
    //
    protected $fillable = [
        'adventure_id',
        'day_number',
        'title',
        'description',
    ];

    // Relationship with Adventure
    public function adventure()
    {
        return $this->belongsTo(Adventure::class);
    }
}
