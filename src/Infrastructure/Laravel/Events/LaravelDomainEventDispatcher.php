<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Events;

use Application\Contracts\DomainEventDispatcherInterface;

final class LaravelDomainEventDispatcher implements DomainEventDispatcherInterface
{
    public function dispatch(object $event): void
    {
        event($event);
    }
}
