<?php

declare(strict_types=1);

namespace Tests\Feature;

use Domain\Contact\Services\ScoreCalculatorService;
use Domain\Contact\Strategies\ScoreStrategyInterface;
use Domain\Contact\ValueObjects\Email;
use Domain\Contact\ValueObjects\Phone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Infrastructure\Laravel\Events\ContactScoreProcessedEvent;
use Infrastructure\Laravel\Jobs\ProcessContactScoreJob;
use RuntimeException;
use Tests\TestCase;

final class ContactApiTest extends TestCase
{
    use RefreshDatabase;

    public static function setUpBeforeClass(): void
    {
        if (! self::databaseIsReachable()) {
            self::markTestSkipped('MySQL com base `testing` indisponível.');
        }

        parent::setUpBeforeClass();
    }

    private static function databaseIsReachable(): bool
    {
        $host = self::envValue('DB_HOST', '127.0.0.1');
        $port = (int) self::envValue('DB_PORT', '3306');
        $database = self::envValue('DB_DATABASE', 'testing');
        $username = self::envValue('DB_USERNAME', 'root');
        $password = self::envValue('DB_PASSWORD', '');

        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s', $host, $port, $database);

        try {
            new \PDO($dsn, $username, $password, [\PDO::ATTR_TIMEOUT => 2]);

            return true;
        } catch (\PDOException) {
            return false;
        }
    }

    private static function envValue(string $key, string $default = ''): string
    {
        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return (string) $_ENV[$key];
        }

        if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
            return (string) $_SERVER[$key];
        }

        $path = dirname(__DIR__, 2).'/.env';
        if (! is_readable($path)) {
            return $default;
        }

        foreach (file($path, FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (! str_contains($line, '=')) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);
            if (trim($name) !== $key) {
                continue;
            }

            $value = trim($value);
            $value = trim($value, '"\'');

            return $value !== '' ? $value : $default;
        }

        return $default;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $logPath = storage_path('logs/contact.log');
        if (is_file($logPath)) {
            unlink($logPath);
        }
    }

    public function test_creates_a_contact_with_pending_status_and_zero_score(): void
    {
        $response = $this->postJson('/api/contacts', [
            'name' => 'João Silva',
            'email' => 'joao@empresa.com',
            'phone' => '(11) 98765-4321',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.score', 0)
            ->assertJsonPath('data.phone', '11987654321');
    }

    public function test_lists_contacts_with_meta(): void
    {
        $this->postJson('/api/contacts', [
            'name' => 'Maria Souza',
            'email' => 'maria@empresa.com.br',
            'phone' => '21987654321',
        ]);

        $response = $this->getJson('/api/contacts');

        $response->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('meta.page', 1)
            ->assertJsonCount(1, 'data');
    }

    public function test_shows_a_contact(): void
    {
        $created = $this->postJson('/api/contacts', [
            'name' => 'Pedro Santos',
            'email' => 'pedro@empresa.com',
            'phone' => '11911112222',
        ]);

        $id = $created->json('data.id');

        $this->getJson("/api/contacts/{$id}")
            ->assertOk()
            ->assertJsonPath('data.id', $id)
            ->assertJsonPath('data.email', 'pedro@empresa.com');
    }

    public function test_returns_404_when_contact_not_found(): void
    {
        $this->getJson('/api/contacts/999999')
            ->assertNotFound()
            ->assertJsonPath('message', 'Contato não encontrado.');
    }

    public function test_updates_a_contact(): void
    {
        $created = $this->postJson('/api/contacts', [
            'name' => 'Ana',
            'email' => 'ana@empresa.com',
            'phone' => '11933334444',
        ]);

        $id = $created->json('data.id');

        $this->putJson("/api/contacts/{$id}", [
            'name' => 'Ana Paula',
            'email' => 'ana.paula@empresa.com.br',
            'phone' => '11933334444',
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Ana Paula')
            ->assertJsonPath('data.email', 'ana.paula@empresa.com.br');
    }

    public function test_delete_removes_contact_and_get_returns_404(): void
    {
        $created = $this->postJson('/api/contacts', [
            'name' => 'Luiz Costa',
            'email' => 'luiz@empresa.com',
            'phone' => '11955556666',
        ]);

        $id = $created->json('data.id');

        $this->deleteJson("/api/contacts/{$id}")
            ->assertNoContent();

        $this->getJson("/api/contacts/{$id}")
            ->assertNotFound();
    }

    public function test_can_create_contact_reusing_email_after_delete(): void
    {
        $email = 'reuse.after.delete@empresa.com';

        $first = $this->postJson('/api/contacts', [
            'name' => 'Primeiro',
            'email' => $email,
            'phone' => '11911111111',
        ]);

        $id = $first->json('data.id');

        $this->deleteJson("/api/contacts/{$id}")->assertNoContent();

        $second = $this->postJson('/api/contacts', [
            'name' => 'Segundo',
            'email' => $email,
            'phone' => '11922222222',
        ]);

        $second->assertCreated()
            ->assertJsonPath('data.email', $email)
            ->assertJsonPath('data.name', 'Segundo');
    }

    public function test_process_score_endpoint_dispatches_job(): void
    {
        Queue::fake();

        $created = $this->postJson('/api/contacts', [
            'name' => 'João Silva',
            'email' => 'joao@empresa.com.br',
            'phone' => '11987654321',
        ]);

        $id = $created->json('data.id');

        $this->postJson("/api/contacts/{$id}/process-score")
            ->assertAccepted()
            ->assertJsonPath('message', 'Pontuação na fila.');

        Queue::assertPushed(ProcessContactScoreJob::class, function (ProcessContactScoreJob $job) use ($id): bool {
            return $job->contactId === $id;
        });
    }

    public function test_processes_score_sync_and_writes_contact_log(): void
    {
        $created = $this->postJson('/api/contacts', [
            'name' => 'João Silva',
            'email' => 'joao@empresa.com.br',
            'phone' => '11987654321',
        ]);

        $id = $created->json('data.id');

        $this->postJson("/api/contacts/{$id}/process-score")
            ->assertAccepted();

        $this->getJson("/api/contacts/{$id}")
            ->assertOk()
            ->assertJsonPath('data.status', 'active')
            ->assertJsonPath('data.score', 60);

        $logPath = storage_path('logs/contact.log');
        $this->assertFileExists($logPath);

        $contents = (string) file_get_contents($logPath);
        $this->assertStringContainsString("id={$id}", $contents);
        $this->assertStringContainsString('joao@empresa.com.br', $contents);
        $this->assertStringContainsString('novo_score=60', $contents);
        $this->assertStringContainsString('status=active', $contents);
    }

    public function test_process_score_dispatches_broadcastable_laravel_event(): void
    {
        Event::fake([ContactScoreProcessedEvent::class]);

        $created = $this->postJson('/api/contacts', [
            'name' => 'Broadcast Silva',
            'email' => 'broadcast.test@empresa.com.br',
            'phone' => '11987654321',
        ]);

        $id = $created->json('data.id');

        $this->postJson("/api/contacts/{$id}/process-score")
            ->assertAccepted();

        Event::assertDispatched(ContactScoreProcessedEvent::class, function (ContactScoreProcessedEvent $e) use ($id): bool {
            if ($e->broadcastAs() !== 'ContactScoreProcessed') {
                return false;
            }
            $channels = $e->broadcastOn();

            return count($channels) === 1 && $channels[0]->name === 'contacts.'.$id;
        });
    }

    public function test_process_score_failure_marks_contact_failed_and_writes_contact_log(): void
    {
        $created = $this->postJson('/api/contacts', [
            'name' => 'João Silva',
            'email' => 'falha.score@empresa.com.br',
            'phone' => '11987654321',
        ]);

        $id = $created->json('data.id');

        $this->app->bind(
            ScoreCalculatorService::class,
            static fn (): ScoreCalculatorService => new ScoreCalculatorService([
                new class implements ScoreStrategyInterface
                {
                    public function calculate(string $name, Email $email, Phone $phone): int
                    {
                        throw new RuntimeException('Score failed.');
                    }
                },
            ]),
        );

        try {
            Bus::dispatchSync(new ProcessContactScoreJob($id));
        } catch (RuntimeException $e) {
            $this->assertSame('Score failed.', $e->getMessage());
        }

        $response = $this->getJson("/api/contacts/{$id}");
        $response->assertOk()
            ->assertJsonPath('data.status', 'failed')
            ->assertJsonPath('data.score', 0);
        $this->assertNotNull($response->json('data.processed_at'));

        $logPath = storage_path('logs/contact.log');
        $this->assertFileExists($logPath);

        $contents = (string) file_get_contents($logPath);
        $this->assertStringContainsString("id={$id}", $contents);
        $this->assertStringContainsString('falha.score@empresa.com.br', $contents);
        $this->assertStringContainsString('novo_score=0', $contents);
        $this->assertStringContainsString('status=failed', $contents);
    }
}
