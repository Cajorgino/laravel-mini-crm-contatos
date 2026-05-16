<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Contact\UseCases;

use Application\Contact\UseCases\ProcessContactScoreUseCase;
use Application\Contracts\DomainEventDispatcherInterface;
use Domain\Contact\Entities\Contact;
use Domain\Contact\Events\ContactScoreProcessed;
use Domain\Contact\Repositories\ContactRepositoryInterface;
use Domain\Contact\Services\ScoreCalculatorService;
use Domain\Contact\Strategies\EmailScoreStrategy;
use Domain\Contact\Strategies\NameScoreStrategy;
use Domain\Contact\Strategies\PhoneScoreStrategy;
use Domain\Contact\Strategies\ScoreStrategyInterface;
use Domain\Contact\ValueObjects\ContactStatus;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ProcessContactScoreUseCaseTest extends TestCase
{
    public function test_it_processes_the_contact_score_and_dispatches_a_domain_event(): void
    {
        $contact = Contact::reconstitute(
            id: 1,
            name: 'Joao Silva',
            email: new Email('joao@empresa.com.br'),
            phone: new Phone('11987654321'),
            score: 0,
            status: ContactStatus::Pending,
        );

        $savedStates = [];

        $repository = $this->createMock(ContactRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($contact);
        $repository
            ->expects($this->exactly(2))
            ->method('save')
            ->willReturnCallback(function (Contact $savedContact) use (&$savedStates): Contact {
                $savedStates[] = [
                    'status' => $savedContact->status(),
                    'score' => $savedContact->score(),
                ];

                return $savedContact;
            });

        $dispatcher = $this->createMock(DomainEventDispatcherInterface::class);
        $dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function (object $event): bool {
                return $event instanceof ContactScoreProcessed
                    && $event->contact()->id() === 1
                    && $event->contact()->score() === 60
                    && $event->contact()->status() === ContactStatus::Active;
            }));

        $useCase = new ProcessContactScoreUseCase(
            repository: $repository,
            scoreCalculatorService: new ScoreCalculatorService([
                new EmailScoreStrategy,
                new NameScoreStrategy,
                new PhoneScoreStrategy,
            ]),
            eventDispatcher: $dispatcher,
            processingDelayInSeconds: 0,
        );

        $processedContact = $useCase->execute(1);

        $this->assertSame(ContactStatus::Processing, $savedStates[0]['status']);
        $this->assertSame(0, $savedStates[0]['score']);
        $this->assertSame(ContactStatus::Active, $savedStates[1]['status']);
        $this->assertSame(60, $savedStates[1]['score']);
        $this->assertSame(60, $processedContact->score());
    }

    public function test_it_marks_the_contact_as_failed_when_processing_throws(): void
    {
        $contact = Contact::reconstitute(
            id: 1,
            name: 'Joao Silva',
            email: new Email('joao@empresa.com.br'),
            phone: new Phone('11987654321'),
            score: 0,
            status: ContactStatus::Pending,
        );

        $savedStates = [];

        $repository = $this->createMock(ContactRepositoryInterface::class);
        $repository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($contact);
        $repository
            ->expects($this->exactly(2))
            ->method('save')
            ->willReturnCallback(function (Contact $savedContact) use (&$savedStates): Contact {
                $savedStates[] = [
                    'status' => $savedContact->status(),
                    'score' => $savedContact->score(),
                ];

                return $savedContact;
            });

        $dispatcher = $this->createMock(DomainEventDispatcherInterface::class);
        $dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function (object $event): bool {
                if (! $event instanceof ContactScoreProcessed) {
                    return false;
                }

                $c = $event->contact();

                return $c->id() === 1
                    && $c->score() === 0
                    && $c->status() === ContactStatus::Failed
                    && $c->processedAt() !== null;
            }));

        $failingService = new ScoreCalculatorService([
            new class implements ScoreStrategyInterface
            {
                public function calculate(string $name, Email $email, Phone $phone): int
                {
                    throw new RuntimeException('Score failed.');
                }
            },
        ]);

        $useCase = new ProcessContactScoreUseCase(
            repository: $repository,
            scoreCalculatorService: $failingService,
            eventDispatcher: $dispatcher,
            processingDelayInSeconds: 0,
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Score failed.');

        try {
            $useCase->execute(1);
        } finally {
            $this->assertSame(ContactStatus::Processing, $savedStates[0]['status']);
            $this->assertSame(ContactStatus::Failed, $savedStates[1]['status']);
        }
    }
}
