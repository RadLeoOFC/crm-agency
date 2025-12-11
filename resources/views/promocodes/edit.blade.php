@extends('adminlte::page')

@section('title', 'Edit Promocode')

@section('content_header')
    <h1>Edit Promocode</h1>
@stop

@section('content')
    <form action="{{ route('promocodes.update', $promo) }}" method="POST">
        @csrf
        @method('PUT')
        @include('promocodes._form', ['submitButtonText' => 'Update promocode'])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop