<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddVotersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'voter_ids' => ['required', 'array'],
            'voter_ids.*' => ['exists:users,id'],
        ];
    }
}
