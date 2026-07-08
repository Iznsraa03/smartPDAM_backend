<?php

declare(strict_types=1);

namespace App\Http\Requests\MeterReading;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeterReadingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'water_meter_id'  => ['required', 'integer', 'exists:water_meters,id'],
            'reading_value'   => ['required', 'numeric', 'min:0'],
            'reading_date'    => ['nullable', 'date', 'before_or_equal:today'],
            'photo_path'      => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], // 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'photo_path.max' => 'Meter photo must not exceed 5MB.',
        ];
    }
}
