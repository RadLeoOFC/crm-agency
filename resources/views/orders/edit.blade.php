@extends('adminlte::page')

@section('title', 'Edit Order')

@section('content_header')
    <h1>Edit order</h1>
@stop

@section('content')
    <form action="{{ route('orders.update', $order)}}" method="POST">
        @csrf
        @method('PUT')
        @include('orders._form', ['submitButtonText' => 'Update'])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
