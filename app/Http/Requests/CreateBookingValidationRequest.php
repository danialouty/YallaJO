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
            'adventure_id' => ['required', 'integer', 'exists:adventures,id'],
            'participants_count' => ['required', 'integer', 'min:1'],
            'payment_method' => ['sometimes', 'in:Visa,Cash'],
        ];
    }
    public function messages(): array{
      return [
            'adventure_id.required' => 'Please select an adventure.',
            'adventure_id.exists'   => 'The selected adventure does not exist.',
            'participants_count.required' => 'يرجى إدخال عدد الأشخاص.',
            'participants_count.integer' => 'عدد الأشخاص يجب أن يكون رقمًا صحيحًا.',
            'participants_count.min' => 'يجب حجز مقعد واحد على الأقل.',
        ];
}
}
