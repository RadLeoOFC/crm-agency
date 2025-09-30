@extends('adminlte::page')

@section('title', 'Edit New Client')

@section('content_header')
    <h1>Edit New Client</h1>
@stop

@section('content')
    <form action="{{ route('clients.update', $client) }}" method="POST">
        @csrf
        @method('PUT')
        @include('clients._form', ['submitButtonText' => 'Update Client'])
        <button type="submit" class="btn btn-success">Update client</button>
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
