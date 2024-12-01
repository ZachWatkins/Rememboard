<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'start_date' => ['required'],
            'end_date' => ['nullable'],
            'latitude' => ['required', 'numeric', 'between:-99.99999999,99.99999999'],
            'longitude' => ['required', 'numeric', 'between:-999.99999999,999.99999999'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'folder_name' => ['required', 'string', 'max:255'],
            'show_on_countdown' => ['required'],
            'is_trip' => ['required'],
        ];
    }
}
