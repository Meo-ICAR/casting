<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserRole;
use Illuminate\Validation\Rules\Enum;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all users to register
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', new Enum(UserRole::class)],
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
            'name.required' => 'Il nome è obbligatorio',
            'email.required' => "L'email è obbligatoria",
            'email.email' => "Inserisci un indirizzo email valido",
            'email.unique' => "Questa email è già registrata",
            'password.required' => 'La password è obbligatoria',
            'password.min' => 'La password deve essere di almeno 8 caratteri',
            'password.confirmed' => 'Le password non corrispondono',
            'role.required' => 'Seleziona un ruolo',
            'role.Illuminate\Validation\Rules\Enum' => 'Il ruolo selezionato non è valido',
        ];
    }
}
