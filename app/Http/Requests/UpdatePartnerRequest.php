<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePartnerRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',  // Nom obligatoire, chaîne de 255 caractères max
            'logo' => 'sometimes|url',  // L'URL du logo est obligatoire
            'type' => 'sometimes|in:gold,vip,standard',  // Type obligatoire parmi les options définies
            'website' => 'nullable|url',  // Site web optionnel, mais si présent, doit être une URL valide
            'visible' => 'nullable|boolean',
        ];
    }
}
