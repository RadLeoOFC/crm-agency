@extends('adminlte::page')

@section('title', 'Add New Language')

@section('content_header')
    <h1>Add New Language</h1>
@stop

@section('content')
    <form action="{{ route('languages.store') }}" method="POST">
        @csrf
        @include('languages._form', ['submitButtonText' => 'Create Language'])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop