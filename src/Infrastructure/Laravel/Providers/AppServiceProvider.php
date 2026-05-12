<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Infrastructure\Laravel\Models\Contact;
use Infrastructure\Laravel\Observers\ContactObserver;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Contact::observe(ContactObserver::class);
    }
}
