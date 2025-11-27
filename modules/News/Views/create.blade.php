@extends('admin.layout')

@section('title', 'Добавить новость')
@section('header', 'Добавить новость')

<div class="p-8">
    <form method="POST" action="{{ route('cp.news.store') }}" class="space-y-6">
        @csrf
        <div>
            <label class="block font-medium">Заголовок</label>
            <input type="text" name="title" class="nova-input w-full" required>
        </div>
        <div>
            <label class="block font-medium">Текст</label>
            <textarea name="body" class="nova-input w-full" rows="6" required></textarea>
        </div>
        <button type="submit" class="nova-button-primary px-4 py-2 rounded">Сохранить</button>
    </form>
</div>
