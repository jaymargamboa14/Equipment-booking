<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_name' => ['required', 'string', 'max:255'],
            'event_description' => ['nullable', 'string', 'max:2000'],
            'venue' => ['nullable', 'string', 'max:255'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.equipment_id' => ['required', 'integer', 'exists:equipment,id', 'distinct'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Please add at least one piece of equipment to the booking.',
            'items.min' => 'Please add at least one piece of equipment to the booking.',
            'items.*.equipment_id.required' => 'Each line must select an item.',
            'items.*.equipment_id.distinct' => 'The same item cannot be added twice. Combine the quantities instead.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
