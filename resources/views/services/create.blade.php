@extends('adminlte::page')

@section('title', 'Create Service')

@section('content_header')
    <h1>Create service</h1>
@stop

@section('content')
    <form action="{{ route('services.store')}}" method="POST">
        @csrf
        @include('services._form', ['submitButtonText' => __('messages.save')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
