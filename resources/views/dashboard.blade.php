@extends('admin.layout')
@section('title', 'Дашборд')
@section('header', 'Дашборд')
@section('content')
    <div class="nova-card p-6">
        <h2 class="text-xl font-bold mb-4">Добро пожаловать в админ-панель!</h2>
        <div class="p-4 bg-white rounded shadow text-gray-900">
            Вы успешно вошли как <b>{{ auth()->user()->name }}</b> ({{ auth()->user()->email }})
        </div>
    </div>
@endsection
