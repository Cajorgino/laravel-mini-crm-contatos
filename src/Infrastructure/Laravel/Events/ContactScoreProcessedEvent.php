<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Events;

use Domain\Contact\Entities\Contact;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ContactScoreProcessedEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        private readonly Contact $contact,
    ) {
    }

    /**
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('contacts.'.$this->contact->id()),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ContactScoreProcessed';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->contact->id(),
            'name' => $this->contact->name(),
            'email' => $this->contact->email()->value(),
            'phone' => $this->contact->phone()->value(),
            'score' => $this->contact->score(),
            'status' => $this->contact->status()->value,
            'processed_at' => $this->contact->processedAt()?->format(DATE_ATOM),
            'created_at' => $this->contact->createdAt()?->format(DATE_ATOM),
        ];
    }
}
