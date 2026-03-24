@extends('adminlte::page')

@section('title', 'Create Order item')

@section('content_header')
    <h1>Create order item</h1>
@stop

@section('content')
    <form action="{{ route('orderitems.store')}}" method="POST">
        @csrf
        @include('orderitems._form', ['submitButtonText' => __('messages.save')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
