<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Contact\Entities;

use DateTimeImmutable;
use Domain\Contact\Entities\Contact;
use Domain\Contact\ValueObjects\ContactStatus;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ContactTest extends TestCase
{
    public function test_it_creates_a_pending_contact_with_zero_score(): void
    {
        $contact = Contact::create(
            'Joao Silva',
            new Email('joao@empresa.com.br'),
            new Phone('11987654321'),
        );

        $this->assertNull($contact->id());
        $this->assertSame('Joao Silva', $contact->name());
        $this->assertSame('joao@empresa.com.br', $contact->email()->value());
        $this->assertSame('11987654321', $contact->phone()->value());
        $this->assertSame(0, $contact->score());
        $this->assertSame(ContactStatus::Pending, $contact->status());
        $this->assertNull($contact->processedAt());
    }

    public function test_it_starts_processing(): void
    {
        $contact = Contact::create(
            'Joao Silva',
            new Email('joao@empresa.com.br'),
            new Phone('11987654321'),
        );

        $contact->startProcessing();

        $this->assertSame(ContactStatus::Processing, $contact->status());
    }

    public function test_it_completes_with_a_score_and_processed_timestamp(): void
    {
        $contact = Contact::create(
            'Joao Silva',
            new Email('joao@empresa.com.br'),
            new Phone('11987654321'),
        );

        $contact->completeWithScore(50);

        $this->assertSame(ContactStatus::Active, $contact->status());
        $this->assertSame(50, $contact->score());
        $this->assertInstanceOf(DateTimeImmutable::class, $contact->processedAt());
    }

    public function test_it_can_fail_processing(): void
    {
        $contact = Contact::create(
            'Joao Silva',
            new Email('joao@empresa.com.br'),
            new Phone('11987654321'),
        );

        $contact->fail();

        $this->assertSame(ContactStatus::Failed, $contact->status());
        $this->assertInstanceOf(DateTimeImmutable::class, $contact->processedAt());
    }

    public function test_it_rejects_an_empty_name(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Contact::create(
            '   ',
            new Email('joao@empresa.com.br'),
            new Phone('11987654321'),
        );
    }
}
