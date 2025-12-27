<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware для проверки ролей пользователя
 * 
 * Использование в маршрутах:
 * Route::get('/admin', [AdminController::class, 'index'])->middleware('role:admin');
 * Route::get('/editor', [EditorController::class, 'index'])->middleware('role:admin,editor');
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Проверяем, авторизован ли пользователь
        if (!auth()->check()) {
            abort(403, 'Доступ запрещен. Необходима авторизация.');
        }

        $user = auth()->user();

        // Проверяем наличие хотя бы одной из указанных ролей
        if (!$user->hasAnyRole($roles)) {
            abort(403, 'Доступ запрещен. Недостаточно прав.');
        }

        return $next($request);
    }
}
