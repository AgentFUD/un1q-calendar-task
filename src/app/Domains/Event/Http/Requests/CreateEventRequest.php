<?php

namespace App\Domains\Event\Http\Requests;

use DateTimeInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        return [
            'title' => 'required|string|min:3|max:255',
            'description' => 'string|max:255',
            'start' => 'required|event_overlap|date|after:now|date_format:'.DateTimeInterface::ATOM,
            'end' => 'required|event_overlap|date|after:start|date_format:'.DateTimeInterface::ATOM,
            'frequency' => [
                'nullable',
                Rule::in(['daily', 'weekly', 'monthly', 'yearly']),
            ],
            'repeat_until' => 'nullable|date|after:end|date_format:'.DateTimeInterface::ATOM,
        ];
    }
}
