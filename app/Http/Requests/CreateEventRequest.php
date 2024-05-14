<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

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

        $rules = [
            'title' => 'required|min:2|regex:/^(?!.*\s{2})[а-яА-ЯёЁA-Za-z\d\s\-\_]+$/u',
            'description' => 'required|min:100|regex:/^(?!.*\s{2})[а-яА-ЯёЁA-Za-z\s\d\-\_]+$/u',
            'date_registration' => [
                'required',
                'date',
                'after:' . now('Europe/Moscow')->addWeek()->setTime(0, 0, 0),
                'before:' . now('Europe/Moscow')->addMonth()->setTime(23, 59, 0)
            ],
            'date_start' => [
                'required',
                'date',
                'after:' . Carbon::parse($this->input('date_registration'), 'Europe/Moscow')->addWeek()->setTime(0, 0, 0),
                'before:' . Carbon::parse($this->input('date_registration'), 'Europe/Moscow')->addMonth()->setTime(23, 59, 0)
            ],
            'date_end' => [
                'required',
                'date',
                'after:' . Carbon::parse($this->input('date_start'), 'Europe/Moscow')->addDays(3)->setTime(0, 0, 0),
                'before:' . Carbon::parse($this->input('date_start'), 'Europe/Moscow')->addMonth()->setTime(23, 59, 0)
            ],
            'image' => [
                'required',
                File::types(['png', 'jpg', 'jpeg'])->min('10kb')->max('10mb')
            ],
            'task' => [
                'required',
                File::types(['doc', 'docx', 'pdf'])->min('1kb')->max('4mb')
            ],

            'tags' => [
                'required',
                'array',
            ],
            'tags.*' => [
                'exists:tags,id',
            ],
            'prizes.firstPlace' => [
                'required',
                'integer',
                'min:1000',
                'max:1000000',
            ],
            'prizes.secondPlace' => [
                'required',
                'integer',
                'min:1000',
                'max:' . $this->input('prizes.firstPlace'),
            ],
            'prizes.thirdPlace' => [
                'required',
                'integer',
                'min:1000',
                'max:' . $this->input('prizes.secondPlace'),
            ],
        ];


        return $rules;
    }

    public function messages()
    {
        return [
            'title.regex' => 'Поле `Название` должно содержать только буквы, цифры и пробелы.',
            'title.required' => 'Поле `Название` обязательно.',

            'description.regex' => 'Поле `Описание` должно содержать только буквы, цифры и пробелы.',
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
