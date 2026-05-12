<?php

declare(strict_types=1);

namespace Domain\Contact\Strategies;

use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;

interface ScoreStrategyInterface
{
    public function calculate(string $name, Email $email, Phone $phone): int;
}
