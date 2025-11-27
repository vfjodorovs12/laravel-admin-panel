@extends('admin.layout')
@section('title', 'Переводы')
@section('header', 'Переводы')
@section('content')
<h2 class="text-lg font-bold mb-4">Управление переводами</h2>
<p>Здесь будет интерфейс для управления переводами по ключам и языкам.</p>
<div class="mt-4">
    <strong>Языки:</strong>
    @foreach($languages as $lang)
        <span class="inline-block mr-2">@if($lang->flag){!! $lang->flag !!}@endif {{ $lang->name }} ({{ $lang->code }})</span>
    @endforeach
</div>
@endsection
