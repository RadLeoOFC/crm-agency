@extends('adminlte::page')

@section('title', 'Add New Slot')

@section('content_header')
    <h1>Add New Slot</h1>
@stop

@section('content')
    <form action="{{ route('slots.store') }}" method="POST">
        @csrf
        @include('slots._form', ['submitButtonText' => 'Create Slot'])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop