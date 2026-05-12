<?php

declare(strict_types=1);

namespace Domain\Contact\ValueObjects;

use InvalidArgumentException;

final readonly class Phone
{
    private string $value;

    public function __construct(string $value)
    {
        $normalizedValue = preg_replace('/\D+/', '', $value) ?? '';

        if (! preg_match('/^\d{10,11}$/', $normalizedValue)) {
            throw new InvalidArgumentException('Invalid contact phone.');
        }

        $this->value = $normalizedValue;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function getDDD(): string
    {
        return substr($this->value, 0, 2);
    }

    public function isSaoPauloDDD(): bool
    {
        $ddd = (int) $this->getDDD();

        return $ddd >= 11 && $ddd <= 19;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
