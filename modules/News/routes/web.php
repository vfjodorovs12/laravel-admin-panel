<?php

use Illuminate\Support\Facades\Route;
use Modules\News\Http\Controllers\NewsController;

Route::middleware(['web', 'auth'])
    ->prefix('cp/news')
    ->name('cp.news.')
    ->group(function () {
        Route::get('/', [NewsController::class, 'index'])->name('index');
        Route::get('/create', [NewsController::class, 'create'])->name('create');
        Route::post('/', [NewsController::class, 'store'])->name('store');
        Route::get('/{news}/edit', [NewsController::class, 'edit'])->name('edit');
        Route::put('/{news}', [NewsController::class, 'update'])->name('update');
        Route::delete('/{news}', [NewsController::class, 'destroy'])->name('destroy');
    });
