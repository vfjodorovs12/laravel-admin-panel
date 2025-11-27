@php
try {
    $url = route('cp.modules.index');
    $active = request()->routeIs('cp.modules.*') ? 'bg-gray-700' : '';
@endphp
    <a href="{{ $url }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ $active }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
        </svg>
        Модули
    </a>
@php
} catch (Exception $e) {
@endphp
    <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-400 opacity-60 cursor-not-allowed">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
        </svg>
        Модули (маршрут не найден)
    </a>
@php
}
@endphp
