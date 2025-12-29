@extends('adminlte::page')

@section('title', 'Edit Rule')

@section('content_header')
    <h1>{{ __('messages.pricerules.title_edit') }}</h1>
@stop

@section('content')
    <form action="{{ route('pricerules.update', [$pricelist, $rule]) }}" method="POST">
        @csrf
        @method('PUT')
        @include('pricerules._form', ['submitButtonText' => __('messages.save')])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop