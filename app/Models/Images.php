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

    public function getUrlAttribute(): string
    {
        $path = trim((string) $this->image_path);

        // Accept values copied from Markdown links as well as plain URLs.
        if (preg_match('/^\[([^\]]+)\]\((https?:\/\/[^)]+)\)$/i', $path, $matches)) {
            $path = $matches[2];
        }

        return preg_match('/^https?:\/\//i', $path)
            ? $path
            : asset('images/' . ltrim($path, '/'));
    }

    public function adventure(): BelongsTo{
        return $this->belongsTo(Adventure::class);
    }
}
