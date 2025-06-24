<?php

namespace App\Http\Requests;

use App\Models\Challenge;
use App\Models\Soumission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSoumissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            Soumission::COL_CHALLENGE_ID => Challenge::latest()->first()->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [

            Soumission::COL_USER_ID => 'required|exists:users,id',
            Soumission::COL_PROJECT_ID => 'required|exists:projects,id',
            Soumission::COL_CHALLENGE_ID => 'required|exists:challenges,id',
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
