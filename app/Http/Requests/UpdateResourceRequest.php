<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateResourceRequest extends FormRequest
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
            'title' => 'sometimes|string|max:255',
            'link' => 'sometimes|url',
            'category' => 'sometimes|in:externe,vidéo,autre',
            'type' => 'sometimes|in:pdf,lien,autre',
            'visible_at' => 'nullable|date', // Facultatif
        ];
    }
}
