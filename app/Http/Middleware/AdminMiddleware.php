<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware для проверки прав доступа к админ-панели
 * 
 * Проверяет, имеет ли текущий пользователь право доступа к административным разделам.
 * Редиректит неавторизованных пользователей на страницу входа.
 * 
 * @package App\Http\Middleware
 * @author ehosting.lv
 * @copyright 2025 ehosting.lv
 */
class AdminMiddleware
{
    /**
     * Обработка входящего запроса
     * 
     * Проверяет:
     * - Авторизован ли пользователь
     * - Имеет ли пользователь роль администратора (если поле is_admin существует)
     * 
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверка авторизации
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Необходимо войти в систему');
        }
        
        // Дополнительная проверка на роль администратора
        // Раскомментируйте, если добавите поле is_admin в таблицу users
        /*
        if (!auth()->user()->is_admin) {
            abort(403, 'У вас нет доступа к административной панели');
        }
        */
        
        return $next($request);
    }
}
