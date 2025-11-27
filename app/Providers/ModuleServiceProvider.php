<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $modulesPath = base_path('Modules');
        if (!is_dir($modulesPath)) {
            return;
        }
        $modules = array_filter(scandir($modulesPath), function ($dir) use ($modulesPath) {
            return $dir !== '.' && $dir !== '..' && is_dir($modulesPath . DIRECTORY_SEPARATOR . $dir);
        });
        foreach ($modules as $module) {
            $provider = "Modules\\$module\\{$module}ServiceProvider";
            if (class_exists($provider)) {
                $this->app->register($provider);
                Log::info("Модуль $module зарегистрирован.");
            }
        }
    }

    public function boot()
    {
        // Можно добавить хуки для загрузки ресурсов модулей
    }
}
