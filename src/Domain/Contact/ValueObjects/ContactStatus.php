<?php

declare(strict_types=1);

namespace Domain\Contact\ValueObjects;

enum ContactStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Active = 'active';
    case Failed = 'failed';
}
