# Mini CRM de Contatos

API REST em **PHP 8.3 + Laravel 11** para gerenciar contatos, com processamento assíncrono de score, filas em **Redis** e broadcasts via **Laravel Reverb** (WebSockets). A organização segue **DDD**, **Clean Architecture** e testes orientados a **TDD**.

## Pré-requisitos

- Docker Desktop (ou Docker Engine + Docker Compose)
- Opcional (fora do Sail): PHP 8.3 com extensões `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`

> Os testes de integração (`tests/Feature/ContactApiTest.php`) exigem **MySQL acessível** com o banco `testing` (o Sail cria automaticamente). Se o MySQL não estiver disponível, essa suíte é **ignorada** com mensagem explicando o uso do Sail.

## Subir o ambiente (Laravel Sail)

Na raiz do projeto:

```bash
cp .env.example .env
./vendor/bin/sail up -d
```

Com o Sail em execução, gere a chave da aplicação (uma vez):

```bash
./vendor/bin/sail artisan key:generate
```

## Migrations e seeders

```bash
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
```

O `ContactSeeder` cria um contato de demonstração (`demo@empresa.com.br`) apenas se ainda não existir.

## Worker de filas (Redis)

O processamento de score é enfileirado em `ProcessContactScoreJob`. Com `QUEUE_CONNECTION=redis` (padrão sugerido no `.env.example`), rode:

```bash
./vendor/bin/sail artisan queue:work
```

## Laravel Reverb (WebSockets)

```bash
./vendor/bin/sail artisan reverb:start
```

Por padrão o servidor escuta em `0.0.0.0:8080` (ajustável via variáveis `REVERB_*` no `.env`).

## Testes

```bash
./vendor/bin/sail artisan test
```

Cobertura (requer extensão PCOV ou Xdebug habilitados na imagem PHP):

```bash
./vendor/bin/sail artisan test --coverage
```

## API — Endpoints principais

| Método | Rota | Descrição |
|--------|------|-----------|
| `POST` | `/api/contacts` | Cria contato (`pending`, score `0`) |
| `GET` | `/api/contacts` | Lista com paginação (`page`, `per_page`) |
| `GET` | `/api/contacts/{id}` | Detalhe |
| `PUT` | `/api/contacts/{id}` | Atualiza |
| `DELETE` | `/api/contacts/{id}` | Soft delete |
| `POST` | `/api/contacts/{id}/process-score` | Enfileira cálculo de score |

Resposta de recurso (exemplo): envelope `data` com `id`, `name`, `email`, `phone`, `score`, `status`, `processed_at`, `created_at` em ISO-8601 (`DATE_ATOM`).

## WebSocket — canal e segurança

O evento de infraestrutura `Infrastructure\Laravel\Events\ContactScoreProcessedEvent` faz broadcast no canal **`contacts.{id}`** como **canal público** (`Channel`).

**Justificativa:** em ambiente local e demos, um canal público simplifica o HTML de exemplo (sem endpoint de autenticação Pusher/Reverb). Em produção, o recomendável é migrar para **canal privado** (`PrivateChannel`) ou **presence**, com autenticação em `routes/channels.php` e credenciais protegidas.

## Exemplo HTML/JS (Pusher JS + Reverb)

Substitua `APP_KEY` pelo valor de `REVERB_APP_KEY` do seu `.env`.

```html
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
  const pusher = new Pusher('APP_KEY', { wsHost: 'localhost', wsPort: 8080, forceTLS: false, cluster: '' });
  const channel = pusher.subscribe('contacts.1');
  channel.bind('ContactScoreProcessed', (data) => console.log(data));
</script>
```

## Arquitetura (resumo)

- **Domínio** (`src/Domain`): entidades, value objects, estratégias de score, eventos puros.
- **Aplicação** (`src/Application`): casos de uso e DTOs.
- **Infraestrutura** (`src/Infrastructure/Laravel`): Eloquent, HTTP, jobs, listeners, broadcast, providers.

## Licença

MIT.
