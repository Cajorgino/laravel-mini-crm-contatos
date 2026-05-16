<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Repositories;

use Domain\Contact\Entities\Contact as DomainContact;
use Domain\Contact\Repositories\ContactRepositoryInterface;
use Domain\Contact\ValueObjects\ContactStatus;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;
use Infrastructure\Laravel\Models\Contact as ContactModel;

final class EloquentContactRepository implements ContactRepositoryInterface
{
    public function save(DomainContact $contact): DomainContact
    {
        $model = $contact->id() === null
            ? new ContactModel
            : ContactModel::query()->findOrFail($contact->id());

        $model->forceFill([
            'name' => $contact->name(),
            'email' => $contact->email()->value(),
            'phone' => $contact->phone()->value(),
            'score' => $contact->score(),
            'status' => $contact->status()->value,
            'processed_at' => $contact->processedAt(),
        ]);

        $model->save();

        return $this->toDomain($model->refresh());
    }

    public function findById(int $id): ?DomainContact
    {
        $model = ContactModel::query()->find($id);

        return $model === null ? null : $this->toDomain($model);
    }

    public function paginate(int $page = 1, int $perPage = 15): array
    {
        $paginator = ContactModel::query()
            ->orderByDesc('id')
            ->paginate(
                perPage: $perPage,
                pageName: 'page',
                page: $page,
            );

        return [
            'items' => array_map(
                fn (ContactModel $model): DomainContact => $this->toDomain($model),
                $paginator->items(),
            ),
            'total' => $paginator->total(),
            'page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
        ];
    }

    public function delete(DomainContact $contact): void
    {
        if ($contact->id() === null) {
            return;
        }

        ContactModel::query()->findOrFail($contact->id())->delete();
    }

    private function toDomain(ContactModel $model): DomainContact
    {
        return DomainContact::reconstitute(
            id: $model->id,
            name: $model->name,
            email: new Email($model->email),
            phone: new Phone($model->phone),
            score: $model->score,
            status: ContactStatus::from($model->status),
            processedAt: $model->processed_at,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at,
            deletedAt: $model->deleted_at,
        );
    }
}
