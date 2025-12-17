<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGdprConsentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // All authenticated users can update their consent
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'marketing_consent' => ['sometimes', 'boolean'],
            'newsletter_subscription' => ['sometimes', 'boolean'],
            'data_processing_consent' => ['required', 'accepted'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'data_processing_consent.required' => 'You must consent to data processing to use our services.',
            'data_processing_consent.accepted' => 'You must consent to data processing to use our services.',
        ];
    }
}
