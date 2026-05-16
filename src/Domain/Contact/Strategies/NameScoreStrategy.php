<?php

declare(strict_types=1);

namespace Domain\Contact\Strategies;

use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;

final class NameScoreStrategy implements ScoreStrategyInterface
{
    public function calculate(string $name, Email $email, Phone $phone): int
    {
        $trimmed = trim($name);
        if ($trimmed === '') {
            return 0;
        }

        $words = preg_split('/\s+/u', $trimmed, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return count($words) >= 2 ? 10 : 0;
    }
}
