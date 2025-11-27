{{-- 
    Детальный просмотр пользователя
    
    Отображает полную информацию о пользователе:
    - Основные данные (имя, email)
    - Дата регистрации
    - Последняя активность
    - Действия (редактирование, удаление)
    
    @extends resources/views/admin/layout.blade.php
    @author ehosting.lv
--}}

@extends('admin.layout')

@section('title', 'Просмотр пользователя')
@section('header', 'Информация о пользователе')

@section('content')
<div class="max-w-4xl">
    <!-- Хлебные крошки -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('cp.dashboard') }}" class="text-gray-500 hover:text-gray-700">Дашборд</a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('cp.users.index') }}" class="text-gray-500 hover:text-gray-700">Пользователи</a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li class="text-gray-900 font-medium">
                    #{{ $user->id }}
                </li>
            </ol>
        </nav>
    </div>
    
    <!-- Карточка с основной информацией -->
    <div class="nova-card p-8">
        <!-- Шапка с аватаром -->
        <div class="flex items-center justify-between pb-8 border-b border-gray-200">
            <div class="flex items-center">
                <div class="h-20 w-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="ml-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600 mt-1">{{ $user->email }}</p>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                            Активен
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Кнопки действий -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('cp.users.edit', $user) }}" 
                   class="nova-button-primary px-6 py-2 rounded-lg font-medium inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Редактировать
                </a>
                
                <form method="POST" action="{{ route('cp.users.destroy', $user) }}" 
                      onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-6 py-2 rounded-lg font-medium inline-flex items-center bg-red-100 text-red-700 hover:bg-red-200 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Удалить
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Детальная информация -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Левая колонка -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Основная информация</h3>
                    
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ID пользователя</dt>
                            <dd class="mt-1 text-base text-gray-900">#{{ $user->id }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Полное имя</dt>
                            <dd class="mt-1 text-base text-gray-900">{{ $user->name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email адрес</dt>
                            <dd class="mt-1 text-base text-gray-900">
                                <a href="mailto:{{ $user->email }}" class="text-indigo-600 hover:text-indigo-800">
                                    {{ $user->email }}
                                </a>
                            </dd>
                        </div>
                        
                        @if($user->email_verified_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email подтвержден</dt>
                            <dd class="mt-1 text-base text-gray-900">
                                <span class="inline-flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $user->email_verified_at->format('d.m.Y H:i') }}
                                </span>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
            
            <!-- Правая колонка -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Временные метки</h3>
                    
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Дата регистрации</dt>
                            <dd class="mt-1 text-base text-gray-900">
                                {{ $user->created_at->format('d.m.Y H:i') }}
                                <span class="text-sm text-gray-500">
                                    ({{ $user->created_at->diffForHumans() }})
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Последнее обновление</dt>
                            <dd class="mt-1 text-base text-gray-900">
                                {{ $user->updated_at->format('d.m.Y H:i') }}
                                <span class="text-sm text-gray-500">
                                    ({{ $user->updated_at->diffForHumans() }})
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Аккаунт активен</dt>
                            <dd class="mt-1 text-base text-gray-900">
                                {{ $user->created_at->diffInDays(now()) }} дней
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Статистика активности -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="nova-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Дней в системе</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $user->created_at->diffInDays(now()) }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="nova-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Статус аккаунта</p>
                    <p class="text-2xl font-bold text-green-600">Активен</p>
                </div>
            </div>
        </div>
        
        <div class="nova-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Последняя активность</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $user->updated_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Кнопка назад -->
    <div class="mt-6">
        <a href="{{ route('cp.users.index') }}" 
           class="inline-flex items-center text-gray-600 hover:text-gray-900 font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Вернуться к списку пользователей
        </a>
    </div>
</div>
@endsection
