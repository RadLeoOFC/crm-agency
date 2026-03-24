@extends('adminlte::page')

@section('title', 'Create Order')

@section('content_header')
    <h1>Create order</h1>
@stop

@section('content')
    <form action="{{ route('orders.store')}}" method="POST">
        @csrf
        @include('orders._form', ['submitButtonText' => __('messages.save')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
