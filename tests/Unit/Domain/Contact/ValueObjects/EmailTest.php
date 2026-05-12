<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Contact\ValueObjects;

use Domain\Contact\ValueObjects\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function test_it_normalizes_and_exposes_a_valid_email(): void
    {
        $email = new Email('  Joao.Silva@Empresa.COM.BR ');

        $this->assertSame('joao.silva@empresa.com.br', $email->value());
        $this->assertSame('joao.silva@empresa.com.br', (string) $email);
    }

    public function test_it_rejects_an_invalid_email(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Email('invalid-email');
    }

    public function test_it_detects_corporate_emails(): void
    {
        $corporateEmail = new Email('joao@empresa.com.br');
        $freeEmail = new Email('joao@gmail.com');

        $this->assertTrue($corporateEmail->isCorporate());
        $this->assertFalse($freeEmail->isCorporate());
    }

    public function test_it_detects_brazilian_emails(): void
    {
        $brazilianEmail = new Email('joao@empresa.com.br');
        $nonBrazilianEmail = new Email('joao@empresa.com');

        $this->assertTrue($brazilianEmail->isBrazilian());
        $this->assertFalse($nonBrazilianEmail->isBrazilian());
    }
}
