{{-- 
    Форма создания нового пользователя
    
    Поля формы:
    - Имя пользователя (обязательное)
    - Email (обязательное, уникальное)
    - Пароль (обязательное, с подтверждением)
    
    @extends resources/views/admin/layout.blade.php
    @author ehosting.lv
--}}

@extends('admin.layout')

@section('title', 'Создать пользователя')
@section('header', 'Создание нового пользователя')

@section('content')
<div class="max-w-3xl">
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
                    Создать
                </li>
            </ol>
        </nav>
    </div>
    
    <!-- Форма -->
    <div class="nova-card p-8">
        <form method="POST" action="{{ route('cp.users.store') }}" class="space-y-6">
            @csrf
            
            <!-- Имя пользователя -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Имя пользователя <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    class="nova-input w-full @error('name') border-red-500 @enderror"
                    placeholder="Введите имя пользователя"
                    required
                    autocomplete="name"
                >
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">Полное имя пользователя для отображения в системе</p>
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email адрес <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}"
                    class="nova-input w-full @error('email') border-red-500 @enderror"
                    placeholder="user@example.com"
                    required
                    autocomplete="email"
                >
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">Уникальный email для входа в систему</p>
            </div>
            
            <!-- Пароль -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Пароль <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="nova-input w-full @error('password') border-red-500 @enderror"
                    placeholder="Минимум 8 символов"
                    required
                    autocomplete="new-password"
                >
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">Минимум 8 символов. Рекомендуется использовать буквы, цифры и символы</p>
            </div>
            
            <!-- Подтверждение пароля -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Подтверждение пароля <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    class="nova-input w-full"
                    placeholder="Повторите пароль"
                    required
                    autocomplete="new-password"
                >
                <p class="mt-2 text-sm text-gray-500">Введите пароль еще раз для подтверждения</p>
            </div>
            
            <!-- Кнопки действий -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('cp.users.index') }}" 
                   class="text-gray-600 hover:text-gray-900 font-medium">
                    Отмена
                </a>
                
                <button type="submit" 
                        class="nova-button-primary px-8 py-3 rounded-lg font-medium inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Создать пользователя
                </button>
            </div>
        </form>
    </div>
    
    <!-- Подсказка -->
    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-800">
                    <strong>Совет:</strong> После создания пользователь сможет войти в систему используя указанный email и пароль. Убедитесь, что пароль достаточно надежный.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
