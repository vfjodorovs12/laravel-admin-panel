<?php
use Illuminate\Support\Facades\Route;
use Modules\Multilang\Http\Controllers\SettingsController;
use Modules\Multilang\Http\Controllers\TranslateController;

Route::middleware(['web', 'auth'])
    ->prefix('cp/multilang/settings')
    ->name('multilang.settings.')
    ->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/save', [SettingsController::class, 'save'])->name('save');
        Route::post('/add-language', [SettingsController::class, 'addLanguage'])->name('addLanguage');
        Route::delete('/language/{language}', [SettingsController::class, 'deleteLanguage'])->name('deleteLanguage');
    });

Route::middleware(['web', 'auth'])
    ->prefix('cp/multilang/translate')
    ->name('multilang.translate.')
    ->group(function () {
        Route::get('/', [TranslateController::class, 'index'])->name('index');
        // Здесь будут другие маршруты для перевода
    });
