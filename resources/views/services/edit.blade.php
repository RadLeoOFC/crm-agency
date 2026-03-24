@extends('adminlte::page')

@section('title', 'Edit Service')

@section('content_header')
    <h1>Create service</h1>
@stop

@section('content')
    <form action="{{ route('services.update', $service)}}" method="POST">
        @csrf
        @method('PUT')
        @include('services._form', ['submitButtonText' => 'Update'])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
