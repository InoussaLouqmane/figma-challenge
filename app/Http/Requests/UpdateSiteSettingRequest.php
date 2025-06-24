<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'site_name' => 'sometimes|string|max:255',
            'about' => 'nullable|string',
            'email' => 'sometimes|email|max:255',  // Email obligatoire et valide
            'phone' => 'nullable|string|max:20',  // Téléphone, optionnel et limité à 20 caractères
            'logo' => 'nullable|string',
            'facebook' => 'nullable|url',  // URL Facebook, optionnel
            'linkedin' => 'nullable|url',  // URL LinkedIn, optionnel
            'github' => 'nullable|url',  // URL GitHub, optionnel
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422));
    }
}
