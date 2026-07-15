<?php

namespace App\Models;

use Database\Factories\ElectionSettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['election_id', 'allow_abstain', 'show_results_live', 'show_vote_count', 'require_student_id_verification', 'max_votes_per_position', 'voting_time_limit_minutes'])]
class ElectionSetting extends Model
{
    /** @use HasFactory<ElectionSettingFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'allow_abstain' => 'boolean',
            'show_results_live' => 'boolean',
            'show_vote_count' => 'boolean',
            'require_student_id_verification' => 'boolean',
        ];
    }

    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }
}
