<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Contact\ValueObjects;

use Domain\Contact\ValueObjects\Phone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class PhoneTest extends TestCase
{
    public function test_it_normalizes_the_phone_to_digits_only(): void
    {
        $phone = new Phone('(11) 98765-4321');

        $this->assertSame('11987654321', $phone->value());
        $this->assertSame('11987654321', (string) $phone);
    }

    public function test_it_extracts_the_ddd(): void
    {
        $phone = new Phone('21987654321');

        $this->assertSame('21', $phone->getDDD());
    }

    public function test_it_detects_sao_paulo_ddds(): void
    {
        $saoPauloPhone = new Phone('11987654321');
        $rioPhone = new Phone('21987654321');

        $this->assertTrue($saoPauloPhone->isSaoPauloDDD());
        $this->assertFalse($rioPhone->isSaoPauloDDD());
    }

    public function test_it_rejects_invalid_phone_lengths(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Phone('12345');
    }
}
