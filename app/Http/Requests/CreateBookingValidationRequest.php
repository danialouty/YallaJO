<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateBookingValidationRequest extends FormRequest
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
            'adventure_id'=>'required','integer','exists:adventures,id',
        ];
    }
    public function messages(): array{
      return [
            'adventure_id.required' => 'Please select an adventure.',
            'adventure_id.exists'   => 'The selected adventure does not exist.',
            'count.required'        => 'Please enter the number of people.',
            'count.integer'         => 'The number of people must be a valid integer.',
            'count.min'             => 'You must book for at least 1 person.',
        ];
}
}