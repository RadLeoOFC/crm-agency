@extends('adminlte::page')

@section('title', 'Add New Pricelist')

@section('content_header')
    <h1>{{ __('messages.pricelists.title_create') }}</h1>
@stop

@section('content')
    <form action="{{ route('pricelists.store') }}" method="POST">
        @csrf
        @include('pricelists._form', ['submitButtonText' => __('messages.pricelists.create_button')])
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
