<?php

declare(strict_types=1);

namespace Domain\Contact\Strategies;

use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;

final class NameScoreStrategy implements ScoreStrategyInterface
{
    public function calculate(string $name, Email $email, Phone $phone): int
    {
        $normalizedName = preg_replace('/\s+/', ' ', trim($name)) ?? '';

        return str_contains($normalizedName, ' ') ? 10 : 0;
    }
}
