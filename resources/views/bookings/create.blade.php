@extends('adminlte::page')

@section('title', 'Create Bookings')

@section('content_header')
    <h1>{{ __('messages.bookings.title_create') }}</h1>
@stop

@section('content')
    <form action="{{ route('bookings.store') }}" method="POST">
        @csrf
        @include('bookings._form', ['submitButtonText' => __('messages.bookings.create_button')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop