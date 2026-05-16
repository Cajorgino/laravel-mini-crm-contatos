<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Http\Resources;

use Domain\Contact\Entities\Contact as DomainContact;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $contact = $this->resource;
        assert($contact instanceof DomainContact);

        return [
            'id' => $contact->id(),
            'name' => $contact->name(),
            'email' => $contact->email()->value(),
            'phone' => $contact->phone()->value(),
            'score' => $contact->score(),
            'status' => $contact->status()->value,
            'processed_at' => $contact->processedAt()?->format(DATE_ATOM),
            'created_at' => $contact->createdAt()?->format(DATE_ATOM),
            'updated_at' => $contact->updatedAt()?->format(DATE_ATOM),
        ];
    }
}
