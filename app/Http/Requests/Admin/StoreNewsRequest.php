<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\NewsStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'thumbnail'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'author'       => ['nullable', 'string', 'max:100'],
            'status'       => ['required', Rule::enum(NewsStatus::class)],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
