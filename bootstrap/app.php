<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Gestion des redirections automatiques
        $middleware->redirectTo(
            guests: '/login',      // Redirige vers login si non connecté
            users: '/dashboard'    // Redirige vers dashboard si DÉJÀ connecté (résout ton problème)
        );

        // 2. Tes alias de middlewares existants
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'prevent.back' => \App\Http\Middleware\PreventBackHistory::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();