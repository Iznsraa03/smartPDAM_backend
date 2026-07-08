<?php

declare(strict_types=1);

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name'  => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($userId)],
        ];
    }
}
