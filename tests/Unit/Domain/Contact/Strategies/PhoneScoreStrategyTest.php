<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Contact\Strategies;

use Domain\Contact\Strategies\PhoneScoreStrategy;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;
use PHPUnit\Framework\TestCase;

final class PhoneScoreStrategyTest extends TestCase
{
    public function test_it_gives_twenty_points_for_sao_paulo_ddds(): void
    {
        $strategy = new PhoneScoreStrategy();
        $email = new Email('joao@empresa.com');
        $phone = new Phone('11987654321');

        $this->assertSame(20, $strategy->calculate('Joao Silva', $email, $phone));
    }

    public function test_it_gives_ten_points_for_other_valid_ddds(): void
    {
        $strategy = new PhoneScoreStrategy();
        $email = new Email('joao@empresa.com');
        $phone = new Phone('21987654321');

        $this->assertSame(10, $strategy->calculate('Joao Silva', $email, $phone));
    }
}
