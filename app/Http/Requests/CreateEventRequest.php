<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
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
        //! dates min max validation
        return [
            'title' => 'required|min:2|regex:/^(?!.*\s{2})[а-яА-ЯёЁA-Za-z\s]+$/u',
            'description' => 'required|min:100|regex:/^(?!.*\s{2})[а-яА-ЯёЁA-Za-z\s]+$/u',
            'date_registration' => 'required|date|date_format:Y-m-d\TH:i',
            'date_start' => 'required|date|date_format:Y-m-d\TH:i',
            'date_end' => 'required|date|date_format:Y-m-d\TH:i',

            // 'image' => 'required|image',
            // 'task' => 'required|file|mimes:doc,docx,pdf|max:5000',
            // 'tags' => 'required|array',
            // 'prizes' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'title.regex' => 'Поле `Название` должно содержать только кириллицу, латиницу и пробелы.',
            'title.required' => 'Поле `Название` обязательно.',

            'description.regex' => 'Поле `Описание` должно содержать только кириллицу, латиницу и пробелы.',
            'description.required' => 'Поле `Описание` обязательно.',

            'date_registration.required' => 'Поле `Начало регистрации` обязательно.',
            'date_registration.date' => 'Поле `Начало регистрации` должно быть датой.',

            'date_start.required' => 'Поле `Начало события` обязательно.',
            'date_start.date' => 'Поле `Начало события` должно быть датой.',

            'date_end.required' => 'Поле `Конец события` обязательно.',
            'date_end.date' => 'Поле `Конец события` должно быть датой.',

            'tags.required' => 'Поле `Теги` обязательно.',
            'tags.array' => 'Поле `Теги` должно быть массивом.',

            'prizes.required' => 'Поле `Призы` обязательно.',
            'prizes.array' => 'Поле `Призы` должно быть массивом.',
        ];
    }
}
