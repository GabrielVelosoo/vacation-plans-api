<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HolidayPlan extends FormRequest
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
    public function rules()
    {
        return [
            'title' => 'required|string|max:150',
            'description' => 'required|string|max:2000',
            'date' => 'required|date_format:Y-m-d|unique:holiday_plans',
            'location' => 'required|string|max:150',
            'participants' => 'nullable',
        ];
    }
}
