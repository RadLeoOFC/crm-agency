@extends('adminlte::page')

@section('title', 'Edit Order item')

@section('content_header')
    <h1>Edit order item</h1>
@stop

@section('content')
    <form action="{{ route('orderitems.update', $orderitem)}}" method="POST">
        @csrf
        @method('PUT')
        @include('orderitems._form', ['submitButtonText' => 'Update'])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
