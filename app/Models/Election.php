<?php

namespace App\Models;

use Database\Factories\ElectionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['title', 'slug', 'description', 'status', 'type', 'faculty_id', 'department_id', 'starts_at', 'ends_at', 'results_published_at', 'is_anonymous', 'require_2fa', 'created_by', 'settings'])]
class Election extends Model
{
    /** @use HasFactory<ElectionFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'results_published_at' => 'datetime',
            'is_anonymous' => 'boolean',
            'require_2fa' => 'boolean',
            'settings' => 'array',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function voteRecords(): HasMany
    {
        return $this->hasMany(VoteRecord::class);
    }

    public function voters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'election_voters');
    }

    public function settings(): HasOne
    {
        return $this->hasOne(ElectionSetting::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'model_id')->where('model_type', self::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeOngoing($query)
    {
        return $query->whereIn('status', ['active', 'paused']);
    }

    public function getIsOngoingAttribute(): bool
    {
        return in_array($this->status, ['active', 'paused']);
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getIsUpcomingAttribute(): bool
    {
        return in_array($this->status, ['draft', 'scheduled']) && $this->starts_at > now();
    }

    public function getTimeRemainingAttribute(): ?int
    {
        if (! $this->is_ongoing) {
            return null;
        }

        $remaining = now()->diffInSeconds($this->ends_at, false);

        return $remaining > 0 ? (int) $remaining : 0;
    }

    public function getTotalVotersAttribute(): int
    {
        return $this->voters()->count();
    }

    public function getTurnoutPercentageAttribute(): float
    {
        $totalVoters = $this->total_voters;

        if ($totalVoters === 0) {
            return 0.0;
        }

        $votedCount = $this->voteRecords()
            ->distinct('user_id')
            ->count('user_id');

        return round(($votedCount / $totalVoters) * 100, 2);
    }

    public function getUrlAttribute(): string
    {
        return route('elections.show', $this);
    }

    public function canStart(): bool
    {
        return in_array($this->status, ['draft', 'scheduled'])
            && $this->positions()->exists()
            && $this->candidates()->approved()->exists();
    }

    public function canEnd(): bool
    {
        return in_array($this->status, ['active', 'paused']);
    }

    public function hasVoted(User $user): bool
    {
        return $this->voteRecords()->where('user_id', $user->id)->exists();
    }

    public function totalVotesCast(): int
    {
        return $this->votes()->count();
    }
}
