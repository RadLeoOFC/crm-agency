@extends('adminlte::page')

@section('title', 'Create Price override')

@section('content_header')
    <h1>Create Price override</h1>
@stop

@section('content')
    <form action="{{ route('priceoverrides.store', $pricelist) }}" method="POST">
        @csrf
        @include('priceoverrides._form', ['submitButtonText' => 'Save'])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop
