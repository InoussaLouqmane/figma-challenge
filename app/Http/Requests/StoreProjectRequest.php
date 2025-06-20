<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'cover' => 'nullable|string',
                'category' => 'required|string|max:160',
                'start_date' => 'nullable|date',
                'deadline' => 'required|date|after:today',
                'status' => 'required|in:active,closed',
        ];
    }
}
