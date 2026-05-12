<?php

declare(strict_types=1);

namespace Application\Contact\UseCases;

use Application\Contact\DTOs\CreateContactDTO;
use Domain\Contact\Entities\Contact;
use Domain\Contact\Repositories\ContactRepositoryInterface;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;

final readonly class CreateContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $repository,
    ) {
    }

    public function execute(CreateContactDTO $dto): Contact
    {
        $contact = Contact::create(
            $dto->name,
            new Email($dto->email),
            new Phone($dto->phone),
        );

        return $this->repository->save($contact);
    }
}
