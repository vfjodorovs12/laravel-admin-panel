{{-- 
    Список пользователей с таблицей
    
    Функционал:
    - Таблица с данными пользователей
    - Поиск по имени и email
    - Сортировка по колонкам
    - Пагинация
    - Действия: просмотр, редактирование, удаление
    
    @extends resources/views/admin/layout.blade.php
    @author ehosting.lv
--}}

@extends('admin.layout')

@section('title', 'Пользователи')
@section('header', 'Управление пользователями')

@section('content')
<div class="space-y-6">
    <!-- Панель действий -->
    <div class="flex items-center justify-between">
        <!-- Поиск -->
        <form method="GET" action="{{ route('cp.users.index') }}" class="flex items-center space-x-3">
            <input 
                type="search" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Поиск по имени или email..." 
                class="nova-input w-80"
            >
            <button type="submit" class="nova-button-primary px-6 py-2 rounded-lg font-medium">
                Найти
            </button>
            @if(request('search'))
                <a href="{{ route('cp.users.index') }}" class="text-gray-600 hover:text-gray-900">
                    Сбросить
                </a>
            @endif
        </form>
        
        <!-- Кнопка создания -->
        <a href="{{ route('cp.users.create') }}" class="nova-button-primary px-6 py-2 rounded-lg font-medium inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Создать пользователя
        </a>
    </div>
    
    <!-- Таблица пользователей -->
    <div class="nova-card overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('cp.users.index', ['sort' => 'id', 'dir' => request('sort') === 'id' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" 
                           class="flex items-center hover:text-gray-900">
                            ID
                            @if(request('sort') === 'id')
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    @if(request('dir') === 'asc')
                                        <path d="M5 10l5-5 5 5H5z"/>
                                    @else
                                        <path d="M5 10l5 5 5-5H5z"/>
                                    @endif
                                </svg>
                            @endif
                        </a>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('cp.users.index', ['sort' => 'name', 'dir' => request('sort') === 'name' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" 
                           class="flex items-center hover:text-gray-900">
                            Имя
                            @if(request('sort') === 'name')
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    @if(request('dir') === 'asc')
                                        <path d="M5 10l5-5 5 5H5z"/>
                                    @else
                                        <path d="M5 10l5 5 5-5H5z"/>
                                    @endif
                                </svg>
                            @endif
                        </a>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('cp.users.index', ['sort' => 'created_at', 'dir' => request('sort') === 'created_at' && request('dir') === 'asc' ? 'desc' : 'asc']) }}" 
                           class="flex items-center hover:text-gray-900">
                            Дата регистрации
                            @if(request('sort') === 'created_at' || !request('sort'))
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    @if(request('dir') === 'asc')
                                        <path d="M5 10l5-5 5 5H5z"/>
                                    @else
                                        <path d="M5 10l5 5 5-5H5z"/>
                                    @endif
                                </svg>
                            @endif
                        </a>
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Действия
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $user->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $user->name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('cp.users.show', $user) }}" 
                                   class="text-indigo-600 hover:text-indigo-900"
                                   title="Просмотр">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('cp.users.edit', $user) }}" 
                                   class="text-blue-600 hover:text-blue-900"
                                   title="Редактировать">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('cp.users.destroy', $user) }}" 
                                      onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            title="Удалить">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <p class="text-gray-500 text-lg">Пользователи не найдены</p>
                                @if(request('search'))
                                    <p class="text-gray-400 text-sm mt-2">Попробуйте изменить параметры поиска</p>
                                @else
                                    <a href="{{ route('cp.users.create') }}" class="mt-4 nova-button-primary px-6 py-2 rounded-lg font-medium">
                                        Создать первого пользователя
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Пагинация -->
    @if($users->hasPages())
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Показано с <span class="font-medium">{{ $users->firstItem() }}</span>
                по <span class="font-medium">{{ $users->lastItem() }}</span>
                из <span class="font-medium">{{ $users->total() }}</span> результатов
            </div>
            
            <div class="flex space-x-2">
                {{ $users->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
