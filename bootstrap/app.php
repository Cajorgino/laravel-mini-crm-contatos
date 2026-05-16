<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {})
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (RuntimeException $e, Request $request) {
            if ($e->getMessage() === 'Contato não encontrado.' && $request->is('api/*')) {
                return response()->json(['message' => 'Contato não encontrado.'], 404);
            }

            return null;
        });
    })->create();
