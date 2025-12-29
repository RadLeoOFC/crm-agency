@extends('adminlte::page')

@section('title', 'Create Rule')

@section('content_header')
    <h1>{{ __('messages.pricerules.title_create') }}</h1>
@stop

@section('content')
    <form action="{{ route('pricerules.store', $pricelist) }}" method="POST">
        @csrf
        @include('pricerules._form', ['submitButtonText' => __('messages.save')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop