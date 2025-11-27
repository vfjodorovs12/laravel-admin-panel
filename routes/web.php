<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
// use App\Http\Controllers\Admin\SettingsController;
// use App\Http\Controllers\Admin\TranslateController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Маршрут для отображения модулей
Route::get('/cp/modules', [\App\Http\Controllers\Admin\ModuleController::class, 'index'])
    ->middleware(['web', 'auth'])
    ->name('cp.modules.index');

Route::get('/', function () {
    return view('welcome');
});

Route::post('/set-language', function (Request $request) {
    $lang = $request->input('lang');
    if ($lang) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }
    return redirect()->back();
})->name('set.language');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/cp', [DashboardController::class, 'index'])->middleware('auth')->name('cp.dashboard');
Route::get('/cp/users', [UserController::class, 'index'])->middleware('auth')->name('cp.users.index');
Route::get('/cp/users/create', [UserController::class, 'create'])->middleware('auth')->name('cp.users.create');
Route::post('/cp/users', [UserController::class, 'store'])->middleware('auth')->name('cp.users.store');
Route::get('/cp/users/{user}', [UserController::class, 'show'])->middleware('auth')->name('cp.users.show');
Route::get('/cp/users/{user}/edit', [UserController::class, 'edit'])->middleware('auth')->name('cp.users.edit');
Route::put('/cp/users/{user}', [UserController::class, 'update'])->middleware('auth')->name('cp.users.update');
Route::delete('/cp/users/{user}', [UserController::class, 'destroy'])->middleware('auth')->name('cp.users.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Алиасы для обратной совместимости
Route::redirect('/admin', '/cp')->name('admin.dashboard');
Route::redirect('/panel', '/cp');

// Мультиязычность теперь реализована через модуль Multilang
