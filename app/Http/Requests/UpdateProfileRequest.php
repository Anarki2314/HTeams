<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateProfileRequest extends FormRequest
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
        $rules = [];
        if ($this->user()->isUser()) {
            $rules['name'] = 'required|min:2|max:32|regex:/^[а-яА-ЯёЁ]+$/u';
            $rules['surname'] = 'required|min:2|max:32|regex:/^[а-яА-ЯёЁ]+$/u';
        } elseif ($this->user()->isOrganizer()) {
            $rules['orgName'] = 'required|unique:users,orgName, ' . $this->user()->id . '|min:2|max:50|regex:/^(?!.*\s{2})[а-яА-ЯёЁA-Za-z\s]+$/u';
        }
        return $rules;
    }
}
