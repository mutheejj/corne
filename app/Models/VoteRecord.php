<?php

namespace App\Models;

use Database\Factories\VoteRecordFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'election_id', 'position_id', 'verification_code', 'receipt_hash', 'voted_at'])]
class VoteRecord extends Model
{
    /** @use HasFactory<VoteRecordFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'voted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function scopeForElection($query, Election $election)
    {
        return $query->where('election_id', $election->id);
    }

    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }
}
