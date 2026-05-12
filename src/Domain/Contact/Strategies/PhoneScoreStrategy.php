<?php

declare(strict_types=1);

namespace Domain\Contact\Strategies;

use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;

final class PhoneScoreStrategy implements ScoreStrategyInterface
{
    public function calculate(string $name, Email $email, Phone $phone): int
    {
        return $phone->isSaoPauloDDD() ? 20 : 10;
    }
}
