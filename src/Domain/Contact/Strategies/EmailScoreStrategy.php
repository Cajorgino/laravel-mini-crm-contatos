<?php

declare(strict_types=1);

namespace Domain\Contact\Strategies;

use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;

final class EmailScoreStrategy implements ScoreStrategyInterface
{
    public function calculate(string $name, Email $email, Phone $phone): int
    {
        $score = 0;

        if ($email->isCorporate()) {
            $score += 20;
        }

        if ($email->isBrazilian()) {
            $score += 10;
        }

        return $score;
    }
}
