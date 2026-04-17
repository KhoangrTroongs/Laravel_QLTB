<?php

use App\Http\Middleware\CheckBanned;
use App\Http\Middleware\CheckRole;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            CheckBanned::class,
        ]);
        $middleware->alias([
            'role' => CheckRole::class,
            'auth' => Authenticate::class,
            'banned' => CheckBanned::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('app:notify-overdue-borrowings')->dailyAt('08:00');
    })->create();
