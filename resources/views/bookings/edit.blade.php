@extends('adminlte::page')

@section('title', 'Edit Bookings')

@section('content_header')
    <h1>{{ __('messages.bookings.title_edit') }}</h1>
@stop

@section('content')
    <form action="{{ route('bookings.update', $booking) }}" method="POST">
        @csrf
        @method('PUT')
        @include('bookings._form', ['submitButtonText' => __('messages.bookings.update_button')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
