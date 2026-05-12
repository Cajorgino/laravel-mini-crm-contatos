<?php

declare(strict_types=1);

namespace Domain\Contact\ValueObjects;

use InvalidArgumentException;

final readonly class Email
{
    private const FREE_PROVIDERS = [
        'gmail.com',
        'gmail.com.br',
        'hotmail.com',
        'hotmail.com.br',
        'yahoo.com',
        'yahoo.com.br',
    ];

    private string $value;

    public function __construct(string $value)
    {
        $normalizedValue = mb_strtolower(trim($value));

        if (! filter_var($normalizedValue, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid contact email.');
        }

        $this->value = $normalizedValue;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function domain(): string
    {
        return substr($this->value, strpos($this->value, '@') + 1);
    }

    public function isCorporate(): bool
    {
        return ! in_array($this->domain(), self::FREE_PROVIDERS, true);
    }

    public function isBrazilian(): bool
    {
        return str_ends_with($this->domain(), '.br');
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
