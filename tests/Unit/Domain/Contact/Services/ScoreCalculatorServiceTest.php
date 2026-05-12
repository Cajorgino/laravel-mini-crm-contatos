<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Contact\Services;

use Domain\Contact\Services\ScoreCalculatorService;
use Domain\Contact\Strategies\ScoreStrategyInterface;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;
use PHPUnit\Framework\TestCase;

final class ScoreCalculatorServiceTest extends TestCase
{
    public function test_it_sums_the_score_from_all_strategies(): void
    {
        $firstStrategy = $this->createMock(ScoreStrategyInterface::class);
        $firstStrategy
            ->expects($this->once())
            ->method('calculate')
            ->willReturn(15);

        $secondStrategy = $this->createMock(ScoreStrategyInterface::class);
        $secondStrategy
            ->expects($this->once())
            ->method('calculate')
            ->willReturn(25);

        $service = new ScoreCalculatorService([$firstStrategy, $secondStrategy]);

        $score = $service->calculate(
            'Joao Silva',
            new Email('joao@empresa.com.br'),
            new Phone('11987654321'),
        );

        $this->assertSame(40, $score);
    }

    public function test_it_returns_zero_when_no_strategies_are_provided(): void
    {
        $service = new ScoreCalculatorService([]);

        $score = $service->calculate(
            'Joao Silva',
            new Email('joao@empresa.com.br'),
            new Phone('11987654321'),
        );

        $this->assertSame(0, $score);
    }
}
