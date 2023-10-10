<?php

namespace App\Http\Requests;

use DateTimeInterface;
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
        return [
            'title'         => 'string|min:3|max:255',
            'description'   => 'string|max:255',
            'start'         => 'event_overlap|date|after:now|date_format:'.DateTimeInterface::ATOM,
            'end'           => 'event_overlap|date|after:start|date_format:'.DateTimeInterface::ATOM,
        ];
    }
}
