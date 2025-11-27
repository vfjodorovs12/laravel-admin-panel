@extends('admin.layout')

@section('title', 'Новости')
@section('header', 'Новости')

<div class="p-8">
    <a href="{{ route('cp.news.create') }}" class="nova-button-primary px-4 py-2 rounded">Добавить новость</a>
    <div class="mt-6 bg-white shadow rounded-lg p-6">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="text-left">Заголовок</th>
                    <th class="text-left">Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($news as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>
                            <a href="{{ route('cp.news.edit', $item) }}" class="text-indigo-600">Редактировать</a>
                            <form action="{{ route('cp.news.destroy', $item) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 ml-2">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $news->links() }}</div>
    </div>
</div>
