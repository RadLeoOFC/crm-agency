@extends('adminlte::page')

@section('title', 'Add New Client')

@section('content_header')
    <h1>Add New Client</h1>
@stop

@section('content')
    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        @include('clients._form', ['submitButtonText' => 'Create Client'])
        <button type="submit" class="btn btn-success">Create client</button>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Initialize any plugins or custom JavaScript here
        });
    </script>
@stop
