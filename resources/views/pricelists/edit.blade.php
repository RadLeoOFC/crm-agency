@extends('adminlte::page')

@section('title', 'Edit Pricelist')

@section('content_header')
    <h1>{{ __('messages.pricelists.title_edit') }}</h1>
@stop

@section('content')
    <form action="{{ route('pricelists.update', $pricelist) }}" method="POST">
        @csrf
        @method('PUT')
        @include('pricelists._form', ['submitButtonText' => __('messages.pricelists.update_button')])
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
