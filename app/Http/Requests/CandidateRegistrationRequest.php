<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CandidateRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'unique:users,student_id'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'faculty' => ['required', 'string'],
            'department' => ['required', 'string'],
            'course' => ['required', 'string'],
            'year_of_study' => ['required', 'integer', 'min:1', 'max:6'],
            'position_id' => ['required', 'exists:positions,id'],
            'manifesto_title' => ['required', 'string', 'max:255'],
            'manifesto' => ['required', 'string', 'min:100'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'terms' => ['required', 'accepted'],
        ];
    }
}
