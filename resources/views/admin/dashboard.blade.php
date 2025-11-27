{{-- 
    Главная страница административной панели (Дашборд)
    
    Отображает основные метрики и статистику системы:
    - Карточки с ключевыми показателями
    - Графики активности пользователей
    - Быстрые действия
    
    @extends resources/views/admin/layout.blade.php
    @author ehosting.lv
--}}

@extends('admin.layout')

@section('title', 'Дашборд')
@section('header', 'Дашборд')

@section('content')
<div class="space-y-6">
    <!-- Карточки со статистикой -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Всего пользователей -->
        <div class="nova-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Всего пользователей</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUsers }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('cp.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    Посмотреть всех →
                </a>
            </div>
        </div>
        
        <!-- Новые пользователи -->
        <div class="nova-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Новые за 30 дней</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $newUsers }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600 font-medium">
                    +{{ $newUsers > 0 ? round(($newUsers / max($totalUsers, 1)) * 100, 1) : 0 }}% от общего числа
                </span>
            </div>
        </div>
        
        <!-- Активные пользователи -->
        <div class="nova-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Активные за неделю</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $activeUsers }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-blue-600 font-medium">
                    {{ $activeUsers > 0 ? round(($activeUsers / max($totalUsers, 1)) * 100, 1) : 0 }}% активность
                </span>
            </div>
        </div>
    </div>
    
    <!-- График регистраций -->
    <div class="nova-card p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Регистрации за последние 7 дней</h3>
        <canvas id="registrationChart" height="80"></canvas>
    </div>
    
    <!-- Быстрые действия -->
    <div class="nova-card p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Быстрые действия</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('cp.users.create') }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                <div>
                    <p class="font-semibold text-gray-800">Добавить пользователя</p>
                    <p class="text-sm text-gray-500">Создать нового пользователя</p>
                </div>
            </a>
            
            <a href="{{ route('cp.users.index') }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition">
                <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <div>
                    <p class="font-semibold text-gray-800">Управление пользователями</p>
                    <p class="text-sm text-gray-500">Просмотр и редактирование</p>
                </div>
            </a>
            
            <div class="flex items-center p-4 border border-gray-200 rounded-lg bg-gray-50">
                <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <div>
                    <p class="font-semibold text-gray-800">Настройки</p>
                    <p class="text-sm text-gray-500">Скоро доступно</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Информационный блок -->
    <div class="nova-card p-6 bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-indigo-900">Добро пожаловать в админ-панель ehosting.lv</h3>
                <div class="mt-2 text-sm text-indigo-800">
                    <p>Эта панель создана на базе Laravel 12 с дизайном в стиле Laravel Nova. Здесь вы можете управлять всеми аспектами вашего приложения.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // График регистраций пользователей
    const ctx = document.getElementById('registrationChart').getContext('2d');
    const registrationData = @json($registrationData);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: registrationData.map(item => item.date),
            datasets: [{
                label: 'Регистрации',
                data: registrationData.map(item => item.count),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1f2937',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: '#f3f4f6'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush
