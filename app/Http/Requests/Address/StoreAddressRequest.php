<?php

declare(strict_types=1);

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'province'     => ['required', 'string', 'max:100'],
            'city'         => ['required', 'string', 'max:100'],
            'district'     => ['required', 'string', 'max:100'],
            'village'      => ['required', 'string', 'max:100'],
            'full_address' => ['required', 'string', 'max:500'],
            'latitude'     => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'    => ['nullable', 'numeric', 'between:-180,180'],
            'is_primary'   => ['nullable', 'boolean'],
        ];
    }
}
