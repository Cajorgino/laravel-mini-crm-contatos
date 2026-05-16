<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('contacts', 'email')->whereNull('deleted_at'),
            ],
            'phone' => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome.',
            'name.max' => 'Nome muito longo (máx. :max).',
            'email.required' => 'Informe o e-mail.',
            'email.email' => 'E-mail inválido.',
            'email.unique' => 'Esse e-mail já está em uso.',
            'email.max' => 'E-mail muito longo (máx. :max).',
            'phone.required' => 'Informe o telefone.',
            'phone.max' => 'Telefone muito longo (máx. :max).',
        ];
    }
}
