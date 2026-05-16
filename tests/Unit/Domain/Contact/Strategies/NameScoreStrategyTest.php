<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Contact\Strategies;

use Domain\Contact\Strategies\NameScoreStrategy;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;
use PHPUnit\Framework\TestCase;

final class NameScoreStrategyTest extends TestCase
{
    public function test_it_gives_ten_points_for_a_full_name(): void
    {
        $strategy = new NameScoreStrategy;
        $email = new Email('joao@empresa.com');
        $phone = new Phone('11987654321');

        $this->assertSame(10, $strategy->calculate('Joao Silva', $email, $phone));
    }

    public function test_it_returns_zero_for_a_single_name(): void
    {
        $strategy = new NameScoreStrategy;
        $email = new Email('joao@empresa.com');
        $phone = new Phone('11987654321');

        $this->assertSame(0, $strategy->calculate('Joao', $email, $phone));
    }

    public function test_it_counts_words_with_multiple_spaces(): void
    {
        $strategy = new NameScoreStrategy;
        $email = new Email('joao@empresa.com');
        $phone = new Phone('11987654321');

        $this->assertSame(10, $strategy->calculate('Ana   Maria', $email, $phone));
    }
}
