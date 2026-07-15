<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'password', 'role', 'student_id', 'phone', 'faculty', 'department', 'course', 'year_of_study', 'avatar', 'is_active'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'last_login_at' => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function elections(): BelongsToMany
    {
        return $this->belongsToMany(Election::class, 'election_voters');
    }

    public function voteRecords(): HasMany
    {
        return $this->hasMany(VoteRecord::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function createdElections(): HasMany
    {
        return $this->hasMany(Election::class, 'created_by');
    }

    public function approvedCandidates(): HasMany
    {
        return $this->hasMany(Candidate::class, 'approved_by');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeVoters($query)
    {
        return $query->where('role', 'voter');
    }

    public function scopeCandidates($query)
    {
        return $query->where('role', 'candidate');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'admin';
    }

    public function getIsVoterAttribute(): bool
    {
        return $this->role === 'voter';
    }

    public function getIsCandidateAttribute(): bool
    {
        return $this->role === 'candidate';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return Storage::disk('public')->url($this->avatar);
        }

        return asset('images/default-avatar.svg');
    }

    public function hasVotedIn(Election $election): bool
    {
        return $this->voteRecords()->where('election_id', $election->id)->exists();
    }

    public function hasVotedForPosition(Position $position): bool
    {
        return $this->voteRecords()->where('position_id', $position->id)->exists();
    }
}
