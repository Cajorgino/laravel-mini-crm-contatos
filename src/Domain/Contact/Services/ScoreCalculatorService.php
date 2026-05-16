<?php

declare(strict_types=1);

namespace Domain\Contact\Services;

use Domain\Contact\Strategies\ScoreStrategyInterface;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;

final readonly class ScoreCalculatorService
{
    public function __construct(
        private iterable $strategies,
    ) {
    }

    public function calculate(string $name, Email $email, Phone $phone): int
    {
        $score = 0;

        foreach ($this->strategies as $strategy) {
            $score += $strategy->calculate($name, $email, $phone);
        }

        return $score;
    }
}
