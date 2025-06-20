<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChallengeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',  // Titre obligatoire
            'edition' => 'sometimes|string|max:100',  // Edition obligatoire
            'description' => 'nullable|string',  // Description facultative
            'cover' => 'nullable|string',  // Cover facultatif, URL ou chemin
            'status' => 'required|in:draft,open,closed',  // Status obligatoire
            'end_date' => 'nullable|date|after_or_equal:today',
        ];
    }
}
