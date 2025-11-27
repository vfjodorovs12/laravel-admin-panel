<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Админ-панель') - ehosting.lv</title>
    
    <!-- Tailwind CSS для стилизации в стиле Nova -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js для интерактивности -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js для графиков -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* Кастомные стили в стиле Laravel Nova */
        :root {
            --nova-primary: #6366f1;
            --nova-dark: #1f2937;
            --nova-border: #e5e7eb;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        
        .nova-sidebar {
            background: linear-gradient(to bottom, #1f2937 0%, #111827 100%);
        }
        
        .nova-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        .nova-button-primary {
            background: var(--nova-primary);
            color: white;
            transition: all 0.2s;
        }
        
        .nova-button-primary:hover {
            background: #4f46e5;
        }
        
        .nova-input {
            border: 1px solid var(--nova-border);
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            transition: all 0.2s;
        }
        
        .nova-input:focus {
            outline: none;
            border-color: var(--nova-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Сайдбар -->
        <aside class="nova-sidebar w-64 text-white flex flex-col">
            <!-- Логотип -->
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-2xl font-bold">
                    <span class="text-indigo-400">ehosting</span>.lv
                </h1>
                <p class="text-xs text-gray-400 mt-1">Админ-панель</p>
            </div>
            <!-- Навигация -->
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('cp.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('cp.dashboard') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Дашборд
                </a>
                <a href="{{ route('cp.users.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('cp.users.*') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Пользователи
                </a>
                @include('components.admin.modules-link')
                <div x-data="{ open: {{ request()->routeIs('multilang.settings.*') || request()->routeIs('multilang.translate.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="flex items-center w-full px-4 py-3 rounded-lg hover:bg-gray-700 transition focus:outline-none {{ (request()->routeIs('multilang.settings.*') || request()->routeIs('multilang.translate.*')) ? 'bg-gray-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7v-2a2 2 0 00-2-2h-4a2 2 0 00-2 2v2M5 7h14M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M5 7l7 5 7-5" />
                        </svg>
                        Мультиязычность
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" class="pl-8 mt-1 space-y-1">
                        <a href="{{ route('multilang.settings.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm {{ request()->routeIs('multilang.settings.*') ? 'bg-gray-700 font-bold' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Настройки
                        </a>
                        <a href="{{ route('multilang.translate.index') }}" class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm {{ request()->routeIs('multilang.translate.*') ? 'bg-gray-700 font-bold' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m0 4v12m0 0h6m-6 0H3" /></svg>
                            Перевод
                        </a>
                    </div>
                </div>
            </nav>
            <!-- Информация о пользователе -->
            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-400">{{ auth()->user()->email ?? 'admin@ehosting.lv' }}</p>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Основной контент -->
        <main class="flex-1 overflow-auto">
            <!-- Хедер -->
            <header class="bg-white border-b border-gray-200 px-8 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800">
                        @yield('header', 'Дашборд')
                    </h2>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Поиск -->
                        <div class="relative" x-data="{ open: false }">
                            <input 
                                type="search" 
                                placeholder="Поиск..." 
                                class="nova-input w-64"
                                @focus="open = true"
                                @blur="setTimeout(() => open = false, 200)"
                            >
                            <div x-show="open" 
                                 class="absolute right-0 mt-2 w-96 nova-card shadow-xl z-50"
                                 style="display: none;">
                                <div class="p-4">
                                    <p class="text-sm text-gray-500">Начните вводить для поиска...</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Кнопка выхода -->
                        @if(Route::has('logout'))
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </header>
            
            <!-- Уведомления -->
            @if(session('success'))
                <div class="mx-8 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mx-8 mt-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif
            
            <!-- Основной контент страницы -->
            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
