<?php

declare(strict_types=1);

namespace Application\Contracts;

interface DomainEventDispatcherInterface
{
    public function dispatch(object $event): void;
}
