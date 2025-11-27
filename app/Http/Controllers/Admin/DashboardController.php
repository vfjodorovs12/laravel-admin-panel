<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Контроллер панели управления админки
 * 
 * Главный контроллер административной панели в стиле Laravel Nova.
 * Обрабатывает отображение дашборда с статистикой и основными метриками.
 * 
 * @package App\Http\Controllers\Admin
 * @author ehosting.lv
 * @copyright 2025 ehosting.lv
 */
class DashboardController extends Controller
{
    /**
     * Отображение главной страницы админ-панели
     * 
     * Загружает основные метрики системы:
     * - Общее количество пользователей
     * - Новые пользователи за последние 30 дней
     * - Активные пользователи за последнюю неделю
     * - Графики активности
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            Log::channel('admin')->info('Загрузка админского дашборда', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()?->email,
            ]);
            
            // Получаем основные метрики
            $totalUsers = User::count();
            Log::channel('admin')->debug('Подсчитано пользователей', ['total' => $totalUsers]);
            
            $newUsers = User::where('created_at', '>=', now()->subDays(30))->count();
            Log::channel('admin')->debug('Новые пользователи за 30 дней', ['count' => $newUsers]);
            
            $activeUsers = User::where('updated_at', '>=', now()->subDays(7))->count();
            Log::channel('admin')->debug('Активные пользователи за 7 дней', ['count' => $activeUsers]);
            
            // Данные для графиков - регистрации по дням за последние 7 дней
            $registrationData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $count = User::whereDate('created_at', $date)->count();
                $registrationData[] = [
                    'date' => now()->subDays($i)->format('d M'),
                    'count' => $count
                ];
            }
            
            Log::channel('admin')->info('Дашборд успешно загружен');
            
            return view('admin.dashboard', compact(
                'totalUsers',
                'newUsers',
                'activeUsers',
                'registrationData'
            ));
            
        } catch (\Throwable $e) {
            Log::channel('admin')->error('Ошибка загрузки дашборда', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
            ]);
            
            throw $e;
        }
    }
}
