@extends('adminlte::page')

@section('title', __('messages.orderitems.title_create'))

@section('content_header')
    <h1>{{ __('messages.orderitems.title_create') }}</h1>
@stop

@section('content')
    <form action="{{ route('orderitems.store')}}" method="POST">
        @csrf
        @include('orderitems._form', ['submitButtonText' => __('messages.orderitems.create_button')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
