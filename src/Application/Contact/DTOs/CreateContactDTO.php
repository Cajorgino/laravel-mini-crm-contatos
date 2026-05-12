<?php

declare(strict_types=1);

namespace Application\Contact\DTOs;

final readonly class CreateContactDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
    ) {
    }
}
