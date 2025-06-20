<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreNoteJuryRequest extends FormRequest
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
            'graphisme' => 'required|integer|min:0|max:30',
            'animation' => 'required|integer|min:0|max:10',
            'navigation' => 'required|integer|min:0|max:10',
            'commentaire' => 'nullable|string',  // Permettre un commentaire facultatif
        ];
    }
}
