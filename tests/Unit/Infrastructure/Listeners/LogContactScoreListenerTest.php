<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Listeners;

use Domain\Contact\Entities\Contact;
use Domain\Contact\Events\ContactScoreProcessed;
use Domain\Contact\ValueObjects\ContactStatus;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;
use Infrastructure\Laravel\Listeners\LogContactScoreListener;
use Tests\TestCase;

final class LogContactScoreListenerTest extends TestCase
{
    public function test_it_appends_a_line_to_contact_log(): void
    {
        $logPath = storage_path('logs/contact.log');
        if (is_file($logPath)) {
            unlink($logPath);
        }

        $contact = Contact::reconstitute(
            id: 42,
            name: 'João Silva',
            email: new Email('joao@empresa.com.br'),
            phone: new Phone('11987654321'),
            score: 60,
            status: ContactStatus::Active,
        );

        $listener = new LogContactScoreListener();
        $listener->handle(new ContactScoreProcessed($contact));

        $this->assertFileExists($logPath);

        $contents = (string) file_get_contents($logPath);
        $this->assertStringContainsString('Contact ID: 42', $contents);
        $this->assertStringContainsString('Email: joao@empresa.com.br', $contents);
        $this->assertStringContainsString('Score: 60', $contents);
        $this->assertStringContainsString('Status: active', $contents);
    }
}
