@extends('admin.layout')
@section('title', 'Настройки')
@section('header', 'Настройки')
@section('content')
<form method="POST" action="{{ route('multilang.settings.save') }}" class="mb-8 space-y-6">
    @csrf
    <div>
        <label class="font-medium">Включить мультиязычность</label>
        <input type="checkbox" name="multilang_enabled" value="1" @if($multilang) checked @endif class="ml-2">
    </div>
    <div>
        <label class="font-medium">Отображение языка:</label>
        <label class="ml-4"><input type="radio" name="lang_style" value="flag" @if($lang_style=='flag') checked @endif> Флаг</label>
        <label class="ml-4"><input type="radio" name="lang_style" value="emoji" @if($lang_style=='emoji') checked @endif> Emoji</label>
    </div>
    <button type="submit" class="nova-button-primary px-4 py-2 rounded">Сохранить настройки</button>
</form>
<h2 class="text-lg font-bold mb-4">Добавить язык</h2>
<form method="POST" action="{{ route('multilang.settings.addLanguage') }}" class="flex flex-col md:flex-row md:items-end gap-4 mb-8">
    @csrf
    <div>
        <label>Код (например, en, ru)</label>
        <input type="text" name="code" class="nova-input w-full" required>
    </div>
    <div>
        <label>Название</label>
        <input type="text" name="name" class="nova-input w-full" required>
    </div>
    <div>
        <label>Флаг/Emoji</label>
        <input type="text" name="flag" class="nova-input w-full" placeholder="🇷🇺 или https://...">
    </div>
    <button type="submit" class="nova-button-primary px-4 py-2 rounded">Добавить</button>
</form>
<h2 class="text-lg font-bold mb-4">Языки</h2>
<table class="min-w-full">
    <thead>
        <tr>
            <th>Код</th>
            <th>Название</th>
            <th>Флаг/Emoji</th>
            <th>Активен</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($languages as $lang)
            <tr>
                <td>{{ $lang->code }}</td>
                <td>{{ $lang->name }}</td>
                <td>@if($lang->flag) {!! $lang->flag !!} @endif</td>
                <td>{{ $lang->active ? 'Да' : 'Нет' }}</td>
                <td>
                    <form method="POST" action="{{ route('multilang.settings.deleteLanguage', $lang) }}" onsubmit="return confirm('Удалить язык?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
