<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            'title' => 'required|min:2|max:50',
            'description' => 'required|min:100|max:350',
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
        ];

        return $rules;
    }
}
