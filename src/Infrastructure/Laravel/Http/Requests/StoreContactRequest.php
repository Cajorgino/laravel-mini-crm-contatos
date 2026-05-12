<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreContactRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:contacts,email'],
            'phone' => ['required', 'string', 'max:20'],
        ];
    }
}
