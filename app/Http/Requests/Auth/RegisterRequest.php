<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'        => ['required', 'string', 'max:20', 'unique:users,phone'],
            'address'      => ['nullable', 'string', 'max:500'],
            'latitude'     => ['nullable', 'numeric'],
            'longitude'    => ['nullable', 'numeric'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
