<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Поле `Электронная почта` обязательно.',
            'email.email' => 'Поле `Электронная почта` должно содержать действительную электронную почту.',
            'password.required' => 'Поле `Пароль` обязательно.',
            'password.min' => 'Поле `Пароль` должно содержать не менее 8 символов.',
        ];
    }
}
