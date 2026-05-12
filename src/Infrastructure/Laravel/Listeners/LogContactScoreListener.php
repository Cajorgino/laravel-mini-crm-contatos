<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Listeners;

use Domain\Contact\Events\ContactScoreProcessed;

final class LogContactScoreListener
{
    public function handle(ContactScoreProcessed $event): void
    {
        $contact = $event->contact();

        $line = sprintf(
            "[%s] Contact ID: %d | Email: %s | Score: %d | Status: %s%s",
            now()->format('Y-m-d H:i:s'),
            $contact->id(),
            $contact->email()->value(),
            $contact->score(),
            $contact->status()->value,
            PHP_EOL,
        );

        file_put_contents(
            storage_path('logs/contact.log'),
            $line,
            FILE_APPEND,
        );
    }
}
