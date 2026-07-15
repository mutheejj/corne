<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->is_candidate || $this->user()->is_admin;
    }

    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'manifesto_title' => ['sometimes', 'required', 'string', 'max:255'],
            'manifesto' => ['sometimes', 'required', 'string', 'min:100'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
