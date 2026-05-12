<?php

declare(strict_types=1);

namespace Database\Seeders;

use Application\Contact\DTOs\CreateContactDTO;
use Application\Contact\UseCases\CreateContactUseCase;
use Illuminate\Database\Seeder;
use Infrastructure\Laravel\Models\Contact as ContactModel;

final class ContactSeeder extends Seeder
{
    public function run(): void
    {
        if (ContactModel::query()->where('email', 'demo@empresa.com.br')->exists()) {
            return;
        }

        $useCase = app(CreateContactUseCase::class);

        $useCase->execute(new CreateContactDTO(
            name: 'Contato Demo',
            email: 'demo@empresa.com.br',
            phone: '11987654321',
        ));
    }
}
