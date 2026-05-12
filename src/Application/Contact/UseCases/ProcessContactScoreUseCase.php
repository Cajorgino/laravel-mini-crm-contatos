<?php

declare(strict_types=1);

namespace Application\Contact\UseCases;

use Application\Contracts\DomainEventDispatcherInterface;
use Domain\Contact\Entities\Contact;
use Domain\Contact\Events\ContactScoreProcessed;
use Domain\Contact\Repositories\ContactRepositoryInterface;
use Domain\Contact\Services\ScoreCalculatorService;
use RuntimeException;
use Throwable;

final readonly class ProcessContactScoreUseCase
{
    public function __construct(
        private ContactRepositoryInterface $repository,
        private ScoreCalculatorService $scoreCalculatorService,
        private DomainEventDispatcherInterface $eventDispatcher,
        private int $processingDelayInSeconds = 2,
    ) {
    }

    public function execute(int $id): Contact
    {
        $contact = $this->repository->findById($id);

        if ($contact === null) {
            throw new RuntimeException('Contact not found.');
        }

        try {
            $contact->startProcessing();
            $this->repository->save($contact);

            sleep($this->processingDelayInSeconds);

            $score = $this->scoreCalculatorService->calculate(
                $contact->name(),
                $contact->email(),
                $contact->phone(),
            );

            $contact->completeWithScore($score);
            $processedContact = $this->repository->save($contact);

            $this->eventDispatcher->dispatch(new ContactScoreProcessed($processedContact));

            return $processedContact;
        } catch (Throwable $exception) {
            $contact->fail();
            $this->repository->save($contact);

            throw $exception;
        }
    }
}
