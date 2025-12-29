@extends('adminlte::page')

@section('title', 'Edit Client')

@section('content_header')
    <h1>{{__('messages.clients.title_edit')}}</h1>
@stop

@section('content')
    <form action="{{ route('clients.update', $client) }}" method="POST">
        @csrf
        @method('PUT')
        @include('clients._form', ['submitButtonText' => __('messages.clients.update_button')])
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
