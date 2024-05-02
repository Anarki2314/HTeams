<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignUpRequest extends FormRequest
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
        $rules = [
            'email' => 'required|email|unique:users,email',
            'role_id' => [
                'required', Rule::in([Role::getIdRoleByTitle('Пользователь'), Role::getIdRoleByTitle('Организатор')]), // Validate role_id exists in the roles table],
            ],
            'phone' => 'required|unique:users,phone|regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/',
            'password' => 'required|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',

        ];

        if ($this->roleIsUser()) {
            $rules['name'] = 'required|min:2|regex:/^[а-яА-ЯёЁ]+$/u';
            $rules['surname'] = 'required|min:2|regex:/^[а-яА-ЯёЁ]+$/u';
        } elseif ($this->roleIsOrganizer()) {
            $rules['orgName'] = 'required|min:2|regex:/^[а-яА-ЯёЁ\s]+$/u';
        }
        return $rules;
    }

    private function roleIsUser()
    {
        return $this->input('role_id') == Role::getIdRoleByTitle('Пользователь');
    }

    private function roleIsOrganizer()
    {
        return $this->input('role_id') == Role::getIdRoleByTitle('Организатор');
    }

    public function messages()
    {
        return [
            'name.regex' => 'Имя должно содержать только кириллицу.',
            'surname.regex' => 'Фамилия должна содержать только кириллицу.',
            'orgName.regex' => 'Название организации должно содержать только кириллицу.',
            'password.regex' => 'Пароль должен содержать цифры, строчные и заглавные буквы.',
            'email.unique' => 'Пользователь с таким email уже существует.',
            'phone.unique' => 'Пользователь с таким номером телефона уже существует.',
            'phone.regex' => 'Неверный формат номера телефона (+7(000)000-00-00).',
            'phone.required' => 'Поле `Телефон`обязателено.',
            'password.required' => 'Поле `Пароль` обязательно.',
            'password.confirmed' => 'Поле `Повтор пароля` должно совпадать с полем `Пароль`.',
            'password.min' => 'Поле `Пароль` должно содержать не менее 8 символов.',
            'role_id.in' => 'Неверно указана роль.',


        ];
    }
}
