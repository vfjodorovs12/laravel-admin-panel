<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        <!-- Tailwind CSS для стилизации в стиле Nova -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Alpine.js для интерактивности -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            :root {
                --nova-primary: #6366f1;
                --nova-dark: #1f2937;
                --nova-border: #e5e7eb;
            }
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
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
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 w-full">
                <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                    <h1 class="text-3xl font-bold">{{ config('app.name', 'Laravel') }}</h1>
                </div>
                @if(isset($multilang) && $multilang && isset($languages) && count($languages) > 0)
                    <form method="POST" action="{{ route('set.language') }}" class="mt-6 mb-4 flex gap-2 items-center">
                        @csrf
                        <label for="lang" class="font-medium">Язык:</label>
                        <select name="lang" id="lang" class="border rounded px-2 py-1">
                            @foreach($languages as $lang)
                                <option value="{{ $lang->code }}" @if(app()->getLocale() == $lang->code) selected @endif>
                                    @if($lang_style == 'flag' && $lang->flag)
                                        {!! $lang->flag !!}
                                    @elseif($lang_style == 'emoji' && $lang->flag)
                                        {{ $lang->flag }}
                                    @endif
                                    {{ $lang->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="nova-button-primary px-3 py-1 rounded">OK</button>
                    </form>
                @endif

                <!-- Форма входа в админку -->
                <div class="max-w-md mx-auto mt-10 nova-card p-8 shadow-lg">
                    <h2 class="text-2xl font-bold mb-6 text-center">Вход в админ-панель</h2>
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="email" class="block font-medium mb-1">Email</label>
                            <input id="email" type="email" name="email" required autofocus class="nova-input w-full" autocomplete="username">
                        </div>
                        <div>
                            <label for="password" class="block font-medium mb-1">Пароль</label>
                            <input id="password" type="password" name="password" required class="nova-input w-full" autocomplete="current-password">
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Запомнить меня</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">Забыли пароль?</a>
                            @endif
                        </div>
                        <button type="submit" class="nova-button-primary w-full py-2 rounded">Войти</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
