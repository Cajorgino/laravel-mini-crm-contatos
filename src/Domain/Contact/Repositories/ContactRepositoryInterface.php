<?php

declare(strict_types=1);

namespace Domain\Contact\Repositories;

use Domain\Contact\Entities\Contact;

interface ContactRepositoryInterface
{
    public function save(Contact $contact): Contact;

    public function findById(int $id): ?Contact;

    /**
     * @return array{
     *     items: list<Contact>,
     *     total: int,
     *     page: int,
     *     per_page: int
     * }
     */
    public function paginate(int $page = 1, int $perPage = 15): array;

    public function delete(Contact $contact): void;
}
