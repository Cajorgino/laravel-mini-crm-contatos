<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Contact\Strategies;

use Domain\Contact\Strategies\EmailScoreStrategy;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;
use PHPUnit\Framework\TestCase;

final class EmailScoreStrategyTest extends TestCase
{
    public function test_it_gives_thirty_points_for_a_corporate_brazilian_email(): void
    {
        $strategy = new EmailScoreStrategy();
        $email = new Email('joao@empresa.com.br');
        $phone = new Phone('11987654321');

        $this->assertSame(30, $strategy->calculate('Joao Silva', $email, $phone));
    }

    public function test_it_gives_only_ten_points_for_a_free_brazilian_email(): void
    {
        $strategy = new EmailScoreStrategy();
        $email = new Email('joao@yahoo.com.br');
        $phone = new Phone('11987654321');

        $this->assertSame(10, $strategy->calculate('Joao Silva', $email, $phone));
    }

    public function test_it_returns_zero_for_a_free_non_brazilian_email(): void
    {
        $strategy = new EmailScoreStrategy();
        $email = new Email('joao@gmail.com');
        $phone = new Phone('11987654321');

        $this->assertSame(0, $strategy->calculate('Joao Silva', $email, $phone));
    }
}
