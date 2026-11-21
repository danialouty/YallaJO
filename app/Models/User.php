<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role',
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function activityOrganizer(): HasOne{
        return $this->hasOne(ActivityOrganizer::class);
    }

    public function adventurer(): HasOne{
        return $this->hasOne(Adventurer::class);
    }

    /**
     * The activity organizer has 2 foreign keys connected to the
     * user account one for the activity organizer own account and
     * one for the who approved the activity organizer
     * so i am connecting the foreign key 'approved_by' instead of the
     * default 'user_id'
     */
    public function approvedOrganizers(): HasMany{
        return $this->hasMany(ActivityOrganizer::class, 'approved_by');
    }

    public function isAdmin(): bool {
        return $this->role === 'Admin';
    }

    public function isApprovedOrganizer(): bool {
        return $this->role === 'Activity_Organizer'
            && $this->activityOrganizer?->approval_status === ActivityOrganizer::APPROVED;
    }
}
