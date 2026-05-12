<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Observers;

use Infrastructure\Laravel\Models\Contact;

final class ContactObserver
{
    public function saving(Contact $contact): void
    {
        $contact->phone = preg_replace('/\D+/', '', (string) $contact->phone) ?? '';
    }
}
