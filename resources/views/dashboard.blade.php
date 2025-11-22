@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')


<div class="dashboard-container">
    <h1 style="font-size: 50px; margin-bottom:30px">
        Добро пожаловать на рекламную площадку
    </h1>

    <div class="button-container">
        <a href="{{ url('/') }}" class="btn btn-primary mb-2">На домашнюю страницу</a>
    </div>

</div>

@stop

