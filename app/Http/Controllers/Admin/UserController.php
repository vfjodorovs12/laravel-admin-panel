<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

/**
 * Контроллер управления пользователями
 * 
 * CRUD операции для управления пользователями через админ-панель.
 * Реализует функционал аналогичный Laravel Nova для работы с ресурсами.
 * 
 * @package App\Http\Controllers\Admin
 * @author ehosting.lv
 * @copyright 2025 ehosting.lv
 */
class UserController extends Controller
{
    /**
     * Список всех пользователей с пагинацией
     * 
     * Отображает таблицу пользователей с возможностью:
     * - Поиска по имени и email
     * - Сортировки по колонкам
     * - Пагинации (15 записей на страницу)
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            Log::channel('admin')->info('Просмотр списка пользователей', [
                'search' => $request->search,
                'sort' => $request->sort,
                'dir' => $request->dir,
                'user_id' => auth()->id(),
            ]);
            
            $query = User::query();
            
            // Поиск по имени или email
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            // Сортировка
            $sortBy = $request->get('sort', 'created_at');
            $sortDir = $request->get('dir', 'desc');
            $query->orderBy($sortBy, $sortDir);
            
            $users = $query->paginate(15);
            
            Log::channel('admin')->debug('Загружено пользователей', ['count' => $users->count()]);
            
            return view('admin.users.index', compact('users'));
            
        } catch (\Throwable $e) {
            Log::channel('admin')->error('Ошибка загрузки списка пользователей', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
    
    /**
     * Форма создания нового пользователя
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        Log::channel('admin')->info('Открыта форма создания пользователя', ['user_id' => auth()->id()]);
        return view('admin.users.create');
    }
    
    /**
     * Сохранение нового пользователя
     * 
     * Валидация:
     * - name: обязательное поле, максимум 255 символов
     * - email: обязательное, уникальное, валидный email
     * - password: обязательное, минимум 8 символов, с подтверждением
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            Log::channel('admin')->info('Попытка создания пользователя', [
                'name' => $request->name,
                'email' => $request->email,
                'admin_id' => auth()->id(),
            ]);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $validated['password'] = Hash::make($validated['password']);
            
            $user = User::create($validated);
            
            Log::channel('admin')->info('Пользователь успешно создан', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            
            return redirect()
                ->route('cp.users.index')
                ->with('success', 'Пользователь успешно создан');
                
        } catch (\Throwable $e) {
            Log::channel('admin')->error('Ошибка создания пользователя', [
                'error' => $e->getMessage(),
                'data' => $request->only(['name', 'email']),
            ]);
            throw $e;
        }
    }
    
    /**
     * Просмотр детальной информации о пользователе
     * 
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        Log::channel('admin')->info('Просмотр пользователя', [
            'user_id' => $user->id,
            'email' => $user->email,
            'admin_id' => auth()->id(),
        ]);
        return view('admin.users.show', compact('user'));
    }
    
    /**
     * Форма редактирования пользователя
     * 
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        Log::channel('admin')->info('Открыта форма редактирования пользователя', [
            'user_id' => $user->id,
            'email' => $user->email,
            'admin_id' => auth()->id(),
        ]);
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Обновление данных пользователя
     * 
     * Валидация с учетом текущего пользователя (для уникальности email)
     * Пароль обновляется только если указан новый
     * 
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        try {
            Log::channel('admin')->info('Попытка обновления пользователя', [
                'user_id' => $user->id,
                'old_email' => $user->email,
                'new_email' => $request->email,
                'admin_id' => auth()->id(),
            ]);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => 'nullable|string|min:8|confirmed',
            ]);
            
            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
                Log::channel('admin')->info('Пароль пользователя изменен', ['user_id' => $user->id]);
            }
            
            $user->update($validated);
            
            Log::channel('admin')->info('Пользователь успешно обновлен', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            
            return redirect()
                ->route('cp.users.index')
                ->with('success', 'Пользователь успешно обновлен');
                
        } catch (\Throwable $e) {
            Log::channel('admin')->error('Ошибка обновления пользователя', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
            throw $e;
        }
    }
    
    /**
     * Удаление пользователя
     * 
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            Log::channel('admin')->warning('Удаление пользователя', [
                'user_id' => $user->id,
                'email' => $user->email,
                'admin_id' => auth()->id(),
            ]);
            
            $user->delete();
            
            Log::channel('admin')->warning('Пользователь удален', ['user_id' => $user->id]);
            
            return redirect()
                ->route('cp.users.index')
                ->with('success', 'Пользователь успешно удален');
                
        } catch (\Throwable $e) {
            Log::channel('admin')->error('Ошибка удаления пользователя', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
            throw $e;
        }
    }
}
