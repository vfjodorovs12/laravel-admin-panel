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
        // Регистрация middleware для админ-панели
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
        
        // Добавляем глобальное логирование всех запросов
        $middleware->web(append: [
            \App\Http\Middleware\LogRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Логируем все исключения
        $exceptions->report(function (Throwable $e) {
            \Illuminate\Support\Facades\Log::channel('errors')->error('Exception occurred', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
        });
    })->create();
