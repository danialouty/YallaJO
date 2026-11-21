<?php

namespace App\Models;

use Database\Factories\AdventureDayFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdventureDay extends Model
{
    /** @use HasFactory<AdventureDayFactory> */
    use HasFactory;

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
