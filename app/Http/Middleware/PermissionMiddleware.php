<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware для проверки прав доступа пользователя
 * 
 * Использование в маршрутах:
 * Route::post('/news', [NewsController::class, 'store'])->middleware('permission:create_news');
 * Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('permission:delete_users');
 */
class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$permissions
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        // Проверяем, авторизован ли пользователь
        if (!auth()->check()) {
            abort(403, 'Доступ запрещен. Необходима авторизация.');
        }

        $user = auth()->user();

        // Проверяем наличие хотя бы одного из указанных прав
        if (!$user->hasPermission($permissions)) {
            abort(403, 'Доступ запрещен. Недостаточно прав для выполнения этого действия.');
        }

        return $next($request);
    }
}
