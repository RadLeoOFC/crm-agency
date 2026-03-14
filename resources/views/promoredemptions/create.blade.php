@extends('adminlte::page')

@section('title', 'Create Promoredemption')

@section('content_header')
    <h1>{{ __('messages.promoredemptions.title_create') }}</h1>
@stop

@section('content')
    <form action="{{ route('promoredemptions.store') }}" method="POST">
        @csrf
        @include('promoredemptions._form', ['submitButtonText' => __('messages.promoredemptions.create_button')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop