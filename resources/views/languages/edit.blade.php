@extends('adminlte::page')

@section('title', 'Edit Language')

@section('content_header')
    <h1>Edit Language</h1>
@stop

@section('content')
    <form action="{{ route('languages.update') }}" method="POST">
        @csrf
        @method('PUT')
        @include('languages._form', ['submitButtonText' => 'Edit Language'])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
