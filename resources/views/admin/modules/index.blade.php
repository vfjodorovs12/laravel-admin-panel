@extends('admin.layout')
@section('title', 'Модули')
@section('header', 'Модули')
@section('content')
    <div class="nova-card p-6">
        <h2 class="text-xl font-bold mb-4">Список модулей</h2>
        @if(count($modules))
            <ul class="space-y-2">
                @foreach($modules as $module)
                    <li class="p-4 rounded border border-gray-200 bg-gray-50 flex items-center justify-between">
                        <span class="font-semibold text-gray-800">{{ $module['name'] }}</span>
                        <span class="text-xs text-gray-500">{{ $module['path'] }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">Нет доступных модулей.</p>
        @endif
    </div>
@endsection
