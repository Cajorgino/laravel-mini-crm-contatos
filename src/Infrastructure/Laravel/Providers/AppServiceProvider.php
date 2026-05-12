<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Providers;

use Application\Contracts\DomainEventDispatcherInterface;
use Domain\Contact\Events\ContactScoreProcessed;
use Domain\Contact\Repositories\ContactRepositoryInterface;
use Domain\Contact\Services\ScoreCalculatorService;
use Domain\Contact\Strategies\EmailScoreStrategy;
use Domain\Contact\Strategies\NameScoreStrategy;
use Domain\Contact\Strategies\PhoneScoreStrategy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Laravel\Events\LaravelDomainEventDispatcher;
use Infrastructure\Laravel\Listeners\BroadcastContactScoreListener;
use Infrastructure\Laravel\Listeners\LogContactScoreListener;
use Infrastructure\Laravel\Models\Contact;
use Infrastructure\Laravel\Observers\ContactObserver;
use Infrastructure\Laravel\Repositories\EloquentContactRepository;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContactRepositoryInterface::class, EloquentContactRepository::class);
        $this->app->bind(DomainEventDispatcherInterface::class, LaravelDomainEventDispatcher::class);
        $this->app->bind(ScoreCalculatorService::class, function (): ScoreCalculatorService {
            return new ScoreCalculatorService([
                new EmailScoreStrategy(),
                new NameScoreStrategy(),
                new PhoneScoreStrategy(),
            ]);
        });
    }

    public function boot(): void
    {
        Contact::observe(ContactObserver::class);

        Event::listen(ContactScoreProcessed::class, LogContactScoreListener::class);
        Event::listen(ContactScoreProcessed::class, BroadcastContactScoreListener::class);
    }
}
