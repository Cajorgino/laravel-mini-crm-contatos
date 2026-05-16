<?php

declare(strict_types=1);

namespace Application\Contact\UseCases;

use Domain\Contact\Repositories\ContactRepositoryInterface;

final readonly class ListContactsUseCase
{
    public function __construct(
        private ContactRepositoryInterface $repository,
    ) {
    }

    public function execute(int $page = 1, int $perPage = 15): array
    {
        return $this->repository->paginate($page, $perPage);
    }
}
