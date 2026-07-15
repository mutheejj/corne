<?php

namespace App\Models;

use Database\Factories\PositionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['election_id', 'title', 'description', 'max_votes', 'sort_order'])]
class Position extends Model
{
    /** @use HasFactory<PositionFactory> */
    use HasFactory;

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
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

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function getTotalVotesAttribute(): int
    {
        return $this->votes()->count();
    }

    public function getApprovedCandidatesAttribute()
    {
        return $this->candidates()->approved()->get();
    }
}
