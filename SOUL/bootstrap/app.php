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

    ->withMiddleware(function (Middleware $middleware): void {

        // ✅ 1. Middleware Aliases (باش تستعملهم بالأسماء)
        $middleware->alias([
            'role'        => \App\Http\Middleware\RoleMiddleware::class,
            'status'      => \App\Http\Middleware\CheckStatus::class,
            'role.active' => \App\Http\Middleware\CheckRoleAndStatus::class,
            'has.section' => \App\Http\Middleware\EnsureAdminHasSection::class,
        ]);

        // ✅ 2. Global Middleware (يتطبق على جميع routes)
        $middleware->web(append: [
            \App\Http\Middleware\CheckStatus::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();