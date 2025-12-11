@extends('adminlte::page')

@section('title', 'Edit Price override')

@section('content_header')
    <h1>Edit Price override</h1>
@stop

@section('content')
    <form action="{{ route('priceoverrides.update', [$pricelist, $override]) }}" method="POST">
        @csrf
        @method('PUT')
        @include('priceoverrides._form', [
            'override' => $override,
            'submitButtonText' => 'Update Price override'
        ])

    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Initialize any plugins or custom JavaScript here
        });
    </script>
@stop