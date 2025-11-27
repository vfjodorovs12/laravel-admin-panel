<?php

/**
 * Точка входа в админ-панель
 * @author ehosting.lv
 */

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Проверяем режим обслуживания
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Регистрируем автозагрузчик Composer
require __DIR__.'/../vendor/autoload.php';

// Загружаем приложение
$app = require_once __DIR__.'/../bootstrap/app.php';

// Создаем и обрабатываем запрос
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
