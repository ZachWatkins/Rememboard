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
            'description' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'after:start_date', 'date'],
            'latitude' => ['nullable', 'numeric', 'between:-99.99999999,99.99999999'],
            'longitude' => ['nullable', 'numeric', 'between:-999.99999999,999.99999999'],
            'address' => ['nullable', 'string'],
            'street_address' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'zip' => ['nullable', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:255'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'folder_name' => ['nullable', 'string', 'max:255'],
            'show_on_countdown' => ['nullable', 'boolean'],
            'is_trip' => ['nullable', 'boolean'],
        ];
    }
}
