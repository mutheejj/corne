<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CastVoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->is_voter;
    }

    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'candidate_id' => ['nullable', 'exists:candidates,id', 'required_without:abstain'],
            'position_id' => ['required', 'exists:positions,id'],
            'abstain' => ['boolean'],
        ];
    }
}
