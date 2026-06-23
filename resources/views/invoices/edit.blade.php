@extends('adminlte::page')

@section('title', 'Edit Invoice')

@section('content_header')
    <h1>{{__('messages.invoices.title_edit')}}</h1>
@stop

@section('content')
    <form action="{{ route('invoices.update', $invoice) }}" method="POST">
        @csrf
        @method('PUT')
        @include('invoices._form', ['submitButtonText' => __('messages.invoices.update_button')])
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
