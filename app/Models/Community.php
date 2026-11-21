<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Community extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'region',
        'activity_style',
        'cover_image',
    ];

    public function memberships(): HasMany
    {
        return $this->hasMany(CommunityMembership::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'community_memberships')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(CommunityMessage::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
