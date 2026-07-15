<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->is_admin;
    }

    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'allow_abstain' => ['boolean'],
            'show_results_live' => ['boolean'],
            'show_vote_count' => ['boolean'],
            'require_student_id_verification' => ['boolean'],
            'max_votes_per_position' => ['nullable', 'integer', 'min:1'],
            'voting_time_limit_minutes' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
