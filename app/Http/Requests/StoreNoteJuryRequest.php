<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\NoteJury;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            NoteJury::COL_USER_ID => 'required|exists:users,id',
            NoteJury::COL_SOUMISSION_ID => 'required|exists:soumissions,id',
            'graphisme' => 'required|integer|min:0|max:30',
            'animation' => 'required|integer|min:0|max:10',
            'navigation' => 'required|integer|min:0|max:10',
            'commentaire' => 'nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422));
    }
}
