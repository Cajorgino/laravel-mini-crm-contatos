<?php

declare(strict_types=1);

namespace Application\Contact\UseCases;

use Application\Contact\DTOs\UpdateContactDTO;
use Domain\Contact\Entities\Contact;
use Domain\Contact\Repositories\ContactRepositoryInterface;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;
use RuntimeException;

final readonly class UpdateContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $repository,
    ) {
    }

    public function execute(int $id, UpdateContactDTO $dto): Contact
    {
        $contact = $this->repository->findById($id);

        if ($contact === null) {
            throw new RuntimeException('Contact not found.');
        }

        $contact->updateDetails(
            $dto->name,
            new Email($dto->email),
            new Phone($dto->phone),
        );

        return $this->repository->save($contact);
    }
}
