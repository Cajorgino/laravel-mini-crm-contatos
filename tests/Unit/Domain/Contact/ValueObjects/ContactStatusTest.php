<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Contact\ValueObjects;

use Domain\Contact\ValueObjects\ContactStatus;
use PHPUnit\Framework\TestCase;

final class ContactStatusTest extends TestCase
{
    public function test_it_exposes_the_expected_status_values(): void
    {
        $this->assertSame('pending', ContactStatus::Pending->value);
        $this->assertSame('processing', ContactStatus::Processing->value);
        $this->assertSame('active', ContactStatus::Active->value);
        $this->assertSame('failed', ContactStatus::Failed->value);
    }

    public function test_it_can_be_restored_from_a_scalar_value(): void
    {
        $status = ContactStatus::from('processing');

        $this->assertSame(ContactStatus::Processing, $status);
    }
}
