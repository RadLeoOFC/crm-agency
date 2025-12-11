@extends('adminlte::page')

@section('title', 'Create Rule')

@section('content_header')
    <h1>Create PriceRule</h1>
@stop

@section('content')
    <form action="{{ route('pricerules.store', $pricelist) }}" method="POST">
        @csrf
        @include('pricerules._form', ['submitButtonText' => 'Save'])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop