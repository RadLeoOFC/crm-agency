@extends('adminlte::page')

@section('title', __('messages.orders.title_create'))

@section('content_header')
    <h1>{{ __('messages.orders.title_create') }}</h1>
@stop

@section('content')
    <form action="{{ route('orders.store')}}" method="POST">
        @csrf
        @include('orders._form', ['submitButtonText' => __('messages.orders.create_button')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
