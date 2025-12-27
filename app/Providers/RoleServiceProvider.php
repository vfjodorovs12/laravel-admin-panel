<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider для регистрации Blade директив для работы с ролями
 */
class RoleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Регистрируем Blade директивы только если конфигурация view доступна
        try {
            $cachePath = config('view.compiled');
            if (!$cachePath) {
                return;
            }
        } catch (\Throwable $e) {
            // Конфигурация недоступна (например, во время composer install)
            return;
        }

        // Директива для проверки роли
        // Использование: @role('admin') ... @endrole
        Blade::if('role', function (string ...$roles) {
            return auth()->check() && auth()->user()->hasRole($roles);
        });

        // Директива для проверки прав доступа
        // Использование: @permission('create_news') ... @endpermission
        Blade::if('permission', function (string ...$permissions) {
            return auth()->check() && auth()->user()->hasPermission($permissions);
        });

        // Директива для проверки, является ли пользователь администратором
        // Использование: @admin ... @endadmin
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });

        // Директива для проверки любой из ролей
        // Использование: @hasanyrole(['admin', 'moderator']) ... @endhasanyrole
        Blade::if('hasanyrole', function (array $roles) {
            return auth()->check() && auth()->user()->hasAnyRole($roles);
        });

        // Директива для проверки всех ролей
        // Использование: @hasallroles(['admin', 'moderator']) ... @endhasallroles
        Blade::if('hasallroles', function (array $roles) {
            return auth()->check() && auth()->user()->hasAllRoles($roles);
        });
    }
}
