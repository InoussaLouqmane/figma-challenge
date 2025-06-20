<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'site_name' => 'sometimes|string|max:255',  // Nom du site, obligatoire
            'about' => 'nullable|string',  // À propos, optionnel
            'email' => 'sometimes|email|max:255',  // Email obligatoire et valide
            'phone' => 'nullable|string|max:20',  // Téléphone, optionnel et limité à 20 caractères
            'logo' => 'nullable|url',  // URL du logo, optionnel
            'facebook' => 'nullable|url',  // URL Facebook, optionnel
            'linkedin' => 'nullable|url',  // URL LinkedIn, optionnel
            'github' => 'nullable|url',  // URL GitHub, optionnel
        ];
    }
}
