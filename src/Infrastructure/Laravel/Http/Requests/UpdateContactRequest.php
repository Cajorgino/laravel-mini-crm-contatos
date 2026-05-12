<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('contacts', 'email')->ignore($this->route('id')),
            ],
            'phone' => ['required', 'string', 'max:20'],
        ];
    }
}
