<?php

declare(strict_types=1);

namespace Domain\Contact\Entities;

use DateTimeImmutable;
use Domain\Contact\ValueObjects\ContactStatus;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;
use InvalidArgumentException;

final class Contact
{
    private function __construct(
        private ?int $id,
        private string $name,
        private Email $email,
        private Phone $phone,
        private int $score,
        private ContactStatus $status,
        private ?DateTimeImmutable $processedAt,
        private ?DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $updatedAt,
        private ?DateTimeImmutable $deletedAt,
    ) {
        $this->name = self::normalizeName($name);

        if ($this->score < 0) {
            throw new InvalidArgumentException('Contact score cannot be negative.');
        }
    }

    public static function create(string $name, Email $email, Phone $phone): self
    {
        return new self(
            id: null,
            name: $name,
            email: $email,
            phone: $phone,
            score: 0,
            status: ContactStatus::Pending,
            processedAt: null,
            createdAt: null,
            updatedAt: null,
            deletedAt: null,
        );
    }

    public static function reconstitute(
        ?int $id,
        string $name,
        Email $email,
        Phone $phone,
        int $score,
        ContactStatus $status,
        ?DateTimeImmutable $processedAt = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
        ?DateTimeImmutable $deletedAt = null,
    ): self {
        return new self(
            id: $id,
            name: $name,
            email: $email,
            phone: $phone,
            score: $score,
            status: $status,
            processedAt: $processedAt,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt,
        );
    }

    public function updateDetails(string $name, Email $email, Phone $phone): void
    {
        $this->name = self::normalizeName($name);
        $this->email = $email;
        $this->phone = $phone;
    }

    public function startProcessing(): void
    {
        $this->status = ContactStatus::Processing;
    }

    public function completeWithScore(int $score): void
    {
        if ($score < 0) {
            throw new InvalidArgumentException('Contact score cannot be negative.');
        }

        $this->score = $score;
        $this->status = ContactStatus::Active;
        $this->processedAt = new DateTimeImmutable;
    }

    public function fail(): void
    {
        $this->status = ContactStatus::Failed;
        $this->processedAt = new DateTimeImmutable;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function phone(): Phone
    {
        return $this->phone;
    }

    public function score(): int
    {
        return $this->score;
    }

    public function status(): ContactStatus
    {
        return $this->status;
    }

    public function processedAt(): ?DateTimeImmutable
    {
        return $this->processedAt;
    }

    public function createdAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    private static function normalizeName(string $name): string
    {
        $normalizedName = preg_replace('/\s+/', ' ', trim($name)) ?? '';

        if ($normalizedName === '') {
            throw new InvalidArgumentException('Contact name is required.');
        }

        return $normalizedName;
    }
}
