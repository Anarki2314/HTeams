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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/',
            'role_id' => [
                'required',
                Rule::in([Role::getIdRoleByTitle('Пользователь'), Role::getIdRoleByTitle('Организатор')]), // Validate role_id exists in the roles table
            ],
            'name' => [
                $this->roleIsUser() ? 'required|min:2|regex:/^[а-яА-ЯёЁ]+$/u' : 'nullable',
            ],
            'surname' => [
                $this->roleIsUser() ? 'required|min:2|regex:/^[а-яА-ЯёЁ]+$/u' : 'nullable',
            ],
            'orgName' => [
                $this->roleIsOrganizer() ? 'required|min:2|regex:/^[а-яА-ЯёЁ\s]+$/u' : 'nullable',
            ],
        ];
    }

    private function roleIsUser()
    {
        return $this->input('role_id') == Role::getIdRoleByTitle('Пользователь');
    }

    private function roleIsOrganizer()
    {
        return $this->input('role_id') == Role::getIdRoleByTitle('Организатор');
    }
}
