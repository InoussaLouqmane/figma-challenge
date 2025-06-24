<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\Project;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreProjectRequest extends FormRequest
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
            'status' => $this->input('status', 'active'),
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {

        return [
                'title' => 'required|string|max:255',
                 Project::COL_CHALLENGE_ID => 'required|integer|exists:challenges,id',
                'description' => 'nullable|string',
                'cover' => 'nullable|string',
                'category' => 'required|string|max:160',
                'start_date' => 'nullable|date',
                'deadline' => 'required|date|after:today',
                'status' => 'sometimes|in:active,closed',
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
