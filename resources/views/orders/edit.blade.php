@extends('adminlte::page')

@section('title', __('messages.orders.title_edit'))

@section('content_header')
    <h1>{{ __('messages.orders.title_edit') }}</h1>
@stop

@section('content')
    <form action="{{ route('orders.update', $order)}}" method="POST">
        @csrf
        @method('PUT')
        @include('orders._form', ['submitButtonText' => __('messages.orders.update_button')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
