<?php

declare(strict_types=1);

namespace Application\Contact\UseCases;

use Domain\Contact\Repositories\ContactRepositoryInterface;
use RuntimeException;

final readonly class DeleteContactUseCase
{
    public function __construct(
        private ContactRepositoryInterface $repository,
    ) {
    }

    public function execute(int $id): void
    {
        $contact = $this->repository->findById($id);

        if ($contact === null) {
            throw new RuntimeException('Contact not found.');
        }

        $this->repository->delete($contact);
    }
}
