@extends('adminlte::page')

@section('title', 'Create Price override')

@section('content_header')
    <h1>{{ __('messages.priceoverrides.title_create') }}</h1>
@stop

@section('content')
    <form action="{{ route('priceoverrides.store', $pricelist) }}" method="POST">
        @csrf
        @include('priceoverrides._form', ['submitButtonText' => __('messages.save')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
