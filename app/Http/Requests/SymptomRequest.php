<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SymptomRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'severity' => ['required', 'in:mild,moderate,severe'],
            'description' => ['nullable', 'string'],
            'date_recorded' => ['required', 'date'],
            'notes' => ['nullable', 'string']
        ];
}
}
