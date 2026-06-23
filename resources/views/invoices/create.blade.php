@extends('adminlte::page')

@section('title', __('messages.invoices.title_create'))

@section('content_header')
    <h1>{{ __('messages.invoices.title_create') }}</h1>
@stop

@section('content')
    <form action="{{ route('invoices.store')}}" method="POST">
        @csrf
        @include('invoices._form', ['submitButtonText' => __('messages.invoices.create_button')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
