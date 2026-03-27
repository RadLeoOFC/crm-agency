@extends('adminlte::page')

@section('title', __('messages.services.title_create'))

@section('content_header')
    <h1>{{ __('messages.services.title_create') }}</h1>
@stop

@section('content')
    <form action="{{ route('services.store')}}" method="POST">
        @csrf
        @include('services._form', ['submitButtonText' => __('messages.services.create_button')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
