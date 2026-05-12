<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Listeners;

use Domain\Contact\Events\ContactScoreProcessed;
use Infrastructure\Laravel\Events\ContactScoreProcessedEvent;

final class BroadcastContactScoreListener
{
    public function handle(ContactScoreProcessed $event): void
    {
        event(new ContactScoreProcessedEvent($event->contact()));
    }
}
