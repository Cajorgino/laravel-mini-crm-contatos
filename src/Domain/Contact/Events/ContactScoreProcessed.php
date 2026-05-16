<?php

declare(strict_types=1);

namespace Domain\Contact\Events;

use Domain\Contact\Entities\Contact;

final readonly class ContactScoreProcessed
{
    public function __construct(
        private Contact $contact,
    ) {}

    public function contact(): Contact
    {
        return $this->contact;
    }
}
