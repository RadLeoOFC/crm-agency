@extends('adminlte::page')

@section('title', __('messages.orderitems.title_edit'))

@section('content_header')
    <h1>{{ __('messages.orderitems.title_edit') }}</h1>
@stop

@section('content')
    <form action="{{ route('orderitems.update', [$order, $orderitem])}}" method="POST">
        @csrf
        @method('PUT')
        @include('orderitems._form', ['submitButtonText' => __('messages.orderitems.update_button')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
