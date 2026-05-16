<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Jobs;

use Application\Contact\UseCases\ProcessContactScoreUseCase;
use Domain\Contact\Repositories\ContactRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;

final class ProcessContactScoreJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly int $contactId,
    ) {}

    public function handle(
        ContactRepositoryInterface $repository,
        ProcessContactScoreUseCase $useCase,
    ): void {
        $contact = $repository->findById($this->contactId);

        if ($contact === null) {
            throw new RuntimeException('Contato não encontrado.');
        }

        $useCase->execute($contact);
    }
}
