<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Http\Resources;

use Domain\Contact\Entities\Contact as DomainContact;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin DomainContact
 */
final class ContactResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var DomainContact $contact */
        $contact = $this->resource;

        return [
            'id' => $contact->id(),
            'name' => $contact->name(),
            'email' => $contact->email()->value(),
            'phone' => $contact->phone()->value(),
            'score' => $contact->score(),
            'status' => $contact->status()->value,
            'processed_at' => $contact->processedAt()?->format(DATE_ATOM),
            'created_at' => $contact->createdAt()?->format(DATE_ATOM),
        ];
    }
}
