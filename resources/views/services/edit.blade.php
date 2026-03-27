@extends('adminlte::page')

@section('title', __('messages.services.title_edit'))

@section('content_header')
    <h1>{{ __('messages.services.title_edit') }}</h1>
@stop

@section('content')
    <form action="{{ route('services.update', $service)}}" method="POST">
        @csrf
        @method('PUT')
        @include('services._form', ['submitButtonText' => __('messages.services.update_button')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
