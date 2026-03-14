@extends('adminlte::page')

@section('title', 'Edit Promoredemption')

@section('content_header')
    <h1>{{ __('messages.promoredemptions.title_edit') }}</h1>
@stop

@section('content')
    <form action="{{ route('promoredemptions.update', $promoredemption) }}" method="POST">
        @csrf
        @method('PUT')
        @include('promoredemptions._form', ['submitButtonText' => __('messages.promoredemptions.update_button')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop